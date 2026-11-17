<?php

namespace App\Service;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    public const PREVIEW_MAX_WIDTH = 800;

    public const PREVIEW_QUALITY = 80;

    public const DETAIL_MAX_WIDTH = 1600;

    public const DETAIL_QUALITY = 82;

    private ImageManager $manager;

    public function __construct(?ImageManager $manager = null)
    {
        $this->manager = $manager ?? new ImageManager(Driver::class);
    }

    public function storeCoursePreview(UploadedFile|string $source, string $directory = 'images'): string
    {
        return $this->storeOptimized(
            $source,
            self::PREVIEW_MAX_WIDTH,
            self::PREVIEW_QUALITY,
            $directory
        );
    }

    public function storeCourseDetail(UploadedFile|string $source, string $directory = 'images'): string
    {
        return $this->storeOptimized(
            $source,
            self::DETAIL_MAX_WIDTH,
            self::DETAIL_QUALITY,
            $directory
        );
    }

    public function storeOptimized(
        UploadedFile|string $source,
        int $maxWidth,
        int $quality,
        string $directory = 'images'
    ): string {
        $path = $source instanceof UploadedFile
            ? $source->getRealPath()
            : $source;

        $image = $this->manager->decode($path);
        $image->scaleDown(width: $maxWidth);
        $encoded = $image->encodeUsingFormat(Format::WEBP, quality: $quality);

        $directory = trim($directory, '/');
        $relativePath = $directory.'/'.Str::random(40).'.webp';

        Storage::disk('public')->put($relativePath, (string) $encoded);

        return $relativePath;
    }

    public function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
