<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Service\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class OptimizeCourseImages extends Command
{
    protected $signature = 'courses:optimize-images
                            {--dry-run : Только показать, что будет изменено}';

    protected $description = 'Пережать prev_img и image курсов в WebP с ресайзом';

    public function handle(ImageOptimizer $images): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $disk = Storage::disk('public');

        $courses = Course::query()
            ->where(function ($query) {
                $query->whereNotNull('prev_img')->orWhereNotNull('image');
            })
            ->orderBy('id')
            ->get();

        if ($courses->isEmpty()) {
            $this->info('Курсов с изображениями не найдено.');

            return self::SUCCESS;
        }

        $optimized = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($courses as $course) {
            foreach (['prev_img' => 'preview', 'image' => 'detail'] as $field => $kind) {
                $currentPath = $course->{$field};
                if (! $currentPath) {
                    continue;
                }

                if (! $disk->exists($currentPath)) {
                    $this->warn("Курс #{$course->id}: файл {$field} не найден ({$currentPath})");
                    $skipped++;

                    continue;
                }

                $absolutePath = $disk->path($currentPath);
                $oldSize = $disk->size($currentPath);

                if ($dryRun) {
                    $this->line(sprintf(
                        '[dry-run] Курс #%d %s: %s (%.1f KB) → WebP max %dpx',
                        $course->id,
                        $field,
                        $currentPath,
                        $oldSize / 1024,
                        $kind === 'preview' ? ImageOptimizer::PREVIEW_MAX_WIDTH : ImageOptimizer::DETAIL_MAX_WIDTH
                    ));
                    $optimized++;

                    continue;
                }

                try {
                    $newPath = $kind === 'preview'
                        ? $images->storeCoursePreview($absolutePath)
                        : $images->storeCourseDetail($absolutePath);

                    $course->forceFill([$field => $newPath])->save();

                    if ($newPath !== $currentPath) {
                        $images->deleteIfExists($currentPath);
                    }

                    $newSize = $disk->size($newPath);
                    $this->info(sprintf(
                        'Курс #%d %s: %.1f KB → %.1f KB (%s)',
                        $course->id,
                        $field,
                        $oldSize / 1024,
                        $newSize / 1024,
                        $newPath
                    ));
                    $optimized++;
                } catch (Throwable $e) {
                    $failed++;
                    report($e);
                    $this->error("Курс #{$course->id} {$field}: ".$e->getMessage());
                }
            }
        }

        $this->newLine();
        $this->info("Готово. Обработано: {$optimized}, пропущено: {$skipped}, ошибок: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
