<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueEmailCourseToBioLidsTable extends Migration
{
    public function up()
    {
        $this->softDeleteOlderDuplicates();

        $remaining = $this->findActiveDuplicates();
        if ($remaining->isNotEmpty()) {
            $lines = $remaining->map(function ($row) {
                return sprintf(
                    'email=%s course_id=%s count=%d ids=%s',
                    $row->email,
                    $row->course_id,
                    $row->cnt,
                    $row->ids
                );
            })->implode('; ');

            throw new RuntimeException(
                'Нельзя добавить unique(email, course_id): остались дубли активных заявок БИО. '.$lines
            );
        }

        // Только активные заявки: soft-deleted не блокируют повторную подачу.
        // В MySQL NULL в unique не конфликтуют друг с другом.
        DB::statement('
            CREATE UNIQUE INDEX bio_lids_email_course_active_unique
            ON bio_lids (
                (CASE WHEN deleted_at IS NULL THEN email ELSE NULL END),
                (CASE WHEN deleted_at IS NULL THEN course_id ELSE NULL END)
            )
        ');
    }

    public function down()
    {
        Schema::table('bio_lids', function ($table) {
            $table->dropIndex('bio_lids_email_course_active_unique');
        });
    }

    /**
     * Оставляет самую новую заявку (max id), остальные в группе soft-delete.
     */
    private function softDeleteOlderDuplicates(): void
    {
        $duplicates = $this->findActiveDuplicates();
        if ($duplicates->isEmpty()) {
            return;
        }

        $now = now();

        foreach ($duplicates as $row) {
            $ids = array_values(array_filter(array_map('intval', explode(',', (string) $row->ids))));
            if (count($ids) < 2) {
                continue;
            }

            sort($ids);
            $keepId = array_pop($ids);

            DB::table('bio_lids')
                ->whereIn('id', $ids)
                ->whereNull('deleted_at')
                ->update(['deleted_at' => $now, 'updated_at' => $now]);

            logger()->warning('BioLid duplicate email+course resolved before unique index', [
                'email' => $row->email,
                'course_id' => $row->course_id,
                'kept_id' => $keepId,
                'soft_deleted_ids' => $ids,
            ]);
        }
    }

    private function findActiveDuplicates()
    {
        return DB::table('bio_lids')
            ->selectRaw('email, course_id, COUNT(*) as cnt, GROUP_CONCAT(id ORDER BY id) as ids')
            ->whereNull('deleted_at')
            ->groupBy('email', 'course_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();
    }
}
