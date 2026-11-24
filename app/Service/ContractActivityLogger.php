<?php

namespace App\Service;

use App\Models\Contract;
use App\Models\ContractAct;
use App\Models\ContractAssignment;
use App\Models\ContractReport;
use App\Models\ContractWorkflowStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

class ContractActivityLogger
{
    public const LOG_NAME = 'contract';

    /**
     * @param  array<string, mixed>  $extra
     */
    public function created(Model $subject, ?Contract $contract = null, array $extra = []): void
    {
        $this->log($subject, 'Создание', $contract, $extra);
    }

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     */
    public function updated(Model $subject, array $before, array $after, ?Contract $contract = null): void
    {
        $statusChanged = array_key_exists('status', $before)
            && array_key_exists('status', $after)
            && (string) $before['status'] !== (string) $after['status'];

        $changes = $this->diffAttributes($subject, $before, $after);
        if ($changes === [] && ! $statusChanged) {
            return;
        }

        $properties = ['changes' => $changes];
        if ($statusChanged) {
            $properties['status_old'] = (string) $before['status'];
            $properties['status_new'] = (string) $after['status'];
        }

        $description = $statusChanged && count($changes) <= 1
            ? 'Изменение статуса'
            : 'Изменение информации';

        $this->log($subject, $description, $contract, $properties);
    }

    public function deleted(Model $subject, ?Contract $contract = null): void
    {
        $this->log($subject, 'Удаление', $contract);
    }

    public function scanUploaded(Model $subject, bool $replaced, ?Contract $contract = null): void
    {
        $this->log(
            $subject,
            $replaced ? 'Замена скана' : 'Загрузка скана',
            $contract
        );
    }

    public function scanDeleted(Model $subject, ?Contract $contract = null): void
    {
        $this->log($subject, 'Удаление скана', $contract);
    }

    /**
     * @return Collection<int, object>
     */
    public function historyForContract(Contract $contract): Collection
    {
        $subjects = [
            [Contract::class, [$contract->id]],
            [ContractAssignment::class, $contract->assignments()->withTrashed()->pluck('id')->all()],
            [ContractReport::class, $contract->reports()->withTrashed()->pluck('id')->all()],
            [ContractAct::class, $contract->acts()->withTrashed()->pluck('id')->all()],
        ];

        $query = Activity::query()->where('log_name', self::LOG_NAME)->where(function ($q) use ($subjects, $contract) {
            foreach ($subjects as [$type, $ids]) {
                if ($ids === []) {
                    continue;
                }
                $q->orWhere(function ($inner) use ($type, $ids) {
                    $inner->where('subject_type', $type)->whereIn('subject_id', $ids);
                });
            }

            $q->orWhere(function ($inner) use ($contract) {
                $inner->where('properties->contract_id', $contract->id);
            });
        });

        $activities = $query->orderByDesc('created_at')->get();

        $userNames = User::query()
            ->whereIn('id', $activities->pluck('causer_id')->filter()->unique())
            ->pluck('name', 'id');

        return $activities->map(function (Activity $activity) use ($userNames) {
            $properties = $activity->properties?->toArray() ?? [];

            $userId = $activity->causer_id;

            return (object) [
                'created_at' => $activity->created_at,
                'user' => $userId !== null ? ($userNames[$userId] ?? '—') : '—',
                'entity_label' => $properties['entity_label'] ?? $this->fallbackEntityLabel($activity),
                'description' => $activity->description,
                'details' => $this->formatDetails($properties),
            ];
        });
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    protected function log(Model $subject, string $description, ?Contract $contract = null, array $properties = []): void
    {
        $contractId = $this->resolveContractId($subject, $contract);

        activity(self::LOG_NAME)
            ->performedOn($subject)
            ->withProperties(array_merge([
                'contract_id' => $contractId,
                'entity_type' => $this->entityType($subject),
                'entity_label' => $this->entityLabel($subject),
            ], $properties))
            ->log($description);
    }

    protected function resolveContractId(Model $subject, ?Contract $contract = null): ?int
    {
        if ($contract) {
            return (int) $contract->id;
        }

        if ($subject instanceof Contract) {
            return (int) $subject->id;
        }

        if (isset($subject->contract_id)) {
            return (int) $subject->contract_id;
        }

        return null;
    }

    protected function entityType(Model $subject): string
    {
        return match (true) {
            $subject instanceof Contract => 'contract',
            $subject instanceof ContractAssignment => 'assignment',
            $subject instanceof ContractReport => 'report',
            $subject instanceof ContractAct => 'act',
            default => class_basename($subject),
        };
    }

    protected function entityLabel(Model $subject): string
    {
        return match (true) {
            $subject instanceof Contract => 'Договор'.($subject->number ? ' №'.$subject->number : ''),
            $subject instanceof ContractAssignment => 'Задание №'.$subject->number,
            $subject instanceof ContractReport => 'Отчёт №'.$subject->displayNumber(),
            $subject instanceof ContractAct => 'Акт №'.$subject->displayNumber(),
            default => class_basename($subject),
        };
    }

    protected function fallbackEntityLabel(Activity $activity): string
    {
        return match ($activity->subject_type) {
            Contract::class => 'Договор',
            ContractAssignment::class => 'Задание',
            ContractReport::class => 'Отчёт',
            ContractAct::class => 'Акт',
            default => '—',
        };
    }

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     * @return list<array{field: string, label: string, old: string, new: string}>
     */
    protected function diffAttributes(Model $subject, array $before, array $after): array
    {
        $labels = $this->attributeLabels($subject);
        $changes = [];

        foreach ($labels as $field => $label) {
            if (! array_key_exists($field, $before) && ! array_key_exists($field, $after)) {
                continue;
            }

            $old = $this->normalizeValue($field, $before[$field] ?? null);
            $new = $this->normalizeValue($field, $after[$field] ?? null);
            if ($old === $new) {
                continue;
            }

            $changes[] = [
                'field' => $field,
                'label' => $label,
                'old' => $this->displayValue($field, $old),
                'new' => $this->displayValue($field, $new),
            ];
        }

        return $changes;
    }

    /**
     * @return array<string, string>
     */
    protected function attributeLabels(Model $subject): array
    {
        return match (true) {
            $subject instanceof Contract => [
                'number' => 'Номер',
                'template' => 'Шаблон',
                'project_id' => 'Проект',
                'author_id' => 'Автор',
                'course_id' => 'Курс',
                'status' => 'Статус',
                'signed_at' => 'Дата подписания',
                'nap_registered_at' => 'Дата НПД',
                'ends_at' => 'Дата окончания',
            ],
            $subject instanceof ContractAssignment => [
                'study_group_id' => 'Учебная группа',
                'work_types' => 'Виды работ',
                'payment_type' => 'Основная оплата',
                'hourly_rate' => 'Сумма основной оплаты',
                'per_student_enabled' => 'За успешно обученного',
                'per_student_amount' => 'Вознаграждение за слушателя',
                'status' => 'Статус',
                'issued_at' => 'Дата задания',
                'starts_at' => 'Срок с',
                'ends_at' => 'Срок по',
            ],
            $subject instanceof ContractReport => [
                'kind' => 'Тип отчёта',
                'status' => 'Статус',
                'issued_at' => 'Дата отчёта',
                'starts_at' => 'Период с',
                'ends_at' => 'Период по',
                'lesson_url' => 'Ссылка на занятие',
                'lesson_hours' => 'Акад. часы',
                'successful_students' => 'Успешно обученные',
            ],
            $subject instanceof ContractAct => [
                'kind' => 'Тип акта',
                'status' => 'Статус',
                'issued_at' => 'Дата акта',
                'amount' => 'Сумма',
            ],
            default => [],
        };
    }

    protected function normalizeValue(string $field, mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_bool($value) || in_array($field, ['per_student_enabled'], true)) {
            return (bool) $value ? '1' : '0';
        }

        if (is_numeric($value) && in_array($field, ['hourly_rate', 'per_student_amount', 'lesson_hours', 'amount'], true)) {
            return number_format((float) $value, 2, '.', '');
        }

        if ($field === 'successful_students') {
            if (is_array($value)) {
                return array_values(array_filter(array_map(
                    static fn ($name) => trim((string) $name),
                    $value
                ), static fn (string $name) => $name !== ''));
            }
        }

        return is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    protected function displayValue(string $field, mixed $value): string
    {
        if ($value === null || $value === '') {
            return '—';
        }

        if ($field === 'status') {
            return ContractWorkflowStatus::label((string) $value);
        }

        if ($field === 'payment_type') {
            return match ((string) $value) {
                ContractAssignment::PAYMENT_HOURLY => 'Оплата по часам',
                ContractAssignment::PAYMENT_FIXED => 'Фиксированная оплата',
                ContractAssignment::PAYMENT_NONE => 'Нет основной оплаты',
                default => (string) $value,
            };
        }

        if ($field === 'per_student_enabled') {
            return $value === '1' || $value === true ? 'да' : 'нет';
        }

        if ($field === 'kind') {
            return match ((string) $value) {
                ContractReport::KIND_PER_STUDENT, ContractAct::KIND_PER_STUDENT => 'N.2 (за успешно обученных)',
                default => 'Основной',
            };
        }

        if ($field === 'successful_students') {
            if (is_array($value)) {
                $names = array_values(array_filter(array_map(
                    static fn ($name) => trim((string) $name),
                    $value
                )));

                return $names !== [] ? implode(', ', $names) : '—';
            }

            $decoded = json_decode((string) $value, true);
            if (is_array($decoded)) {
                $names = array_values(array_filter(array_map(
                    static fn ($name) => trim((string) $name),
                    $decoded
                )));

                return $names !== [] ? implode(', ', $names) : '—';
            }
        }

        if (in_array($field, ['signed_at', 'nap_registered_at', 'ends_at', 'issued_at', 'starts_at'], true)) {
            try {
                return \Carbon\Carbon::parse((string) $value)->format('d.m.Y');
            } catch (\Throwable) {
                return (string) $value;
            }
        }

        $text = (string) $value;
        if (mb_strlen($text) > 120) {
            return mb_substr($text, 0, 117).'…';
        }

        return $text;
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    protected function formatDetails(array $properties): string
    {
        if (isset($properties['status_old'], $properties['status_new'])
            && (! isset($properties['changes']) || count($properties['changes']) <= 1)
        ) {
            return ContractWorkflowStatus::label((string) $properties['status_old'])
                .' → '
                .ContractWorkflowStatus::label((string) $properties['status_new']);
        }

        $changes = $properties['changes'] ?? [];
        if (! is_array($changes) || $changes === []) {
            return '—';
        }

        $parts = [];
        foreach ($changes as $change) {
            if (! is_array($change)) {
                continue;
            }
            $label = $change['label'] ?? ($change['field'] ?? 'Поле');
            $old = $change['old'] ?? '—';
            $new = $change['new'] ?? '—';
            $parts[] = $label.': '.$old.' → '.$new;
        }

        return $parts !== [] ? implode('; ', $parts) : '—';
    }

    /**
     * Snapshot tracked attributes for later diff.
     *
     * @return array<string, mixed>
     */
    public function snapshot(Model $subject): array
    {
        return $subject->only(array_keys($this->attributeLabels($subject)));
    }
}
