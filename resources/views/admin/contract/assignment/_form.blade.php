@php
    /** @var \App\Models\ContractAssignment|null $assignment */
    $assignment = $assignment ?? null;
    $selectedCodes = collect(old('work_type_codes', $assignment?->work_type_codes ?? []))
        ->map(fn ($code) => (int) $code)
        ->all();
    $presetLabels = [
        \App\Models\ContractAssignment::WORK_TYPE_LECTURES => 'Проведение лекционных и практических занятий по программе обучения в соответствии с утвержденным расписанием.',
        \App\Models\ContractAssignment::WORK_TYPE_NOTEBOOK => 'Проверка работ слушателей в рабочей тетради, оформленной по установленному шаблону.',
        \App\Models\ContractAssignment::WORK_TYPE_CONSULTING => 'Консультативная поддержка слушателей в процессе обучения (ответы на вопросы, возникающие при освоении материала, и разъяснение тем).',
        \App\Models\ContractAssignment::WORK_TYPE_ATTESTATION => 'Проведение итоговой аттестации слушателей по завершении программы обучения.',
        \App\Models\ContractAssignment::WORK_TYPE_CUSTOM => 'Произвольный вид работ (свой текст)',
    ];
@endphp

<div class="mb-3">
    <label for="study_group_id">Учебная группа (поток)</label>
    <select name="study_group_id" id="study_group_id" class="form-control select2" style="width: 100%;" required>
        <option value="">Выберите учебную группу</option>
        @foreach($studyGroups as $group)
            @php
                $dates = collect([
                    $group->starts_at?->format('d.m.Y'),
                    $group->ends_at?->format('d.m.Y'),
                ])->filter()->implode(' – ');
                $label = $group->title ?: ($group->short_title ?: ('#'.$group->id));
                if ($dates !== '') {
                    $label .= ' ('.$dates.')';
                }
            @endphp
            <option
                value="{{ $group->id }}"
                {{ (string) old('study_group_id', $assignment?->study_group_id) === (string) $group->id ? 'selected' : '' }}
            >
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('study_group_id')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
    @if($studyGroups->isEmpty())
        <div class="text-muted mt-1">У курса договора нет учебных групп.</div>
    @endif
</div>

<div class="mb-3">
    <label class="d-block mb-2">Виды работ</label>
    @foreach($presetLabels as $code => $label)
        <div class="custom-control custom-checkbox mb-2">
            <input
                type="checkbox"
                class="custom-control-input work-type-checkbox"
                id="work_type_{{ $code }}"
                name="work_type_codes[]"
                value="{{ $code }}"
                data-work-type="{{ $code }}"
                {{ in_array($code, $selectedCodes, true) ? 'checked' : '' }}
            >
            <label class="custom-control-label" for="work_type_{{ $code }}">{{ $code }}. {{ $label }}</label>
        </div>
    @endforeach
    @error('work_type_codes')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
    @error('work_type_codes.*')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 work-type-extra" data-for-work-type="1" style="{{ in_array(1, $selectedCodes, true) ? '' : 'display:none;' }}">
    <label for="program_url">Ссылка на программу</label>
    <input
        type="text"
        name="program_url"
        id="program_url"
        class="form-control mb-2"
        value="{{ old('program_url', $assignment?->program_url) }}"
        placeholder="https://..."
    >
    <label for="schedule_url">Ссылка на расписание</label>
    <input
        type="text"
        name="schedule_url"
        id="schedule_url"
        class="form-control"
        value="{{ old('schedule_url', $assignment?->schedule_url) }}"
        placeholder="https://..."
    >
</div>

<div class="mb-3 work-type-extra" data-for-work-type="2" style="{{ in_array(2, $selectedCodes, true) ? '' : 'display:none;' }}">
    <label for="notebook_url">Ссылка на шаблон тетради</label>
    <input
        type="text"
        name="notebook_url"
        id="notebook_url"
        class="form-control"
        value="{{ old('notebook_url', $assignment?->notebook_url) }}"
        placeholder="https://..."
    >
</div>

<div class="mb-3 work-type-extra" data-for-work-type="5" style="{{ in_array(5, $selectedCodes, true) ? '' : 'display:none;' }}">
    <label for="custom_work_type">Текст произвольного вида работ</label>
    <textarea
        name="custom_work_type"
        id="custom_work_type"
        class="form-control"
        rows="3"
        placeholder="Опишите вид работ"
    >{{ old('custom_work_type', $assignment?->custom_work_type) }}</textarea>
    @error('custom_work_type')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

@include('admin.contract._status_field', ['selectedStatus' => $assignment?->status])

@php
    $selectedPaymentType = old(
        'payment_type',
        $assignment?->payment_type ?? \App\Models\ContractAssignment::PAYMENT_HOURLY
    );
    $perStudentEnabled = (bool) old(
        'per_student_enabled',
        $assignment?->per_student_enabled ?? false
    );
    $baseAmountLabels = [
        \App\Models\ContractAssignment::PAYMENT_HOURLY => 'Стоимость академического часа, руб.',
        \App\Models\ContractAssignment::PAYMENT_FIXED => 'Стоимость услуг, руб.',
    ];
@endphp

<div class="mb-3">
    <label for="payment_type">Основная оплата</label>
    <select name="payment_type" id="payment_type" class="form-control" required>
        @foreach(\App\Models\ContractAssignment::basePaymentTypeLabels() as $value => $label)
            <option value="{{ $value }}" {{ (string) $selectedPaymentType === (string) $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('payment_type')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3" id="base_payment_amount_block" style="{{ $selectedPaymentType === \App\Models\ContractAssignment::PAYMENT_NONE ? 'display:none;' : '' }}">
    <label for="hourly_rate" id="hourly_rate_label">{{ $baseAmountLabels[$selectedPaymentType] ?? $baseAmountLabels[\App\Models\ContractAssignment::PAYMENT_HOURLY] }}</label>
    <input
        type="number"
        step="0.01"
        min="0"
        name="hourly_rate"
        id="hourly_rate"
        class="form-control"
        value="{{ old('hourly_rate', $assignment?->hourly_rate) }}"
    >
    @error('hourly_rate')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <div class="custom-control custom-checkbox">
        <input
            type="checkbox"
            class="custom-control-input"
            id="per_student_enabled"
            name="per_student_enabled"
            value="1"
            {{ $perStudentEnabled ? 'checked' : '' }}
        >
        <label class="custom-control-label" for="per_student_enabled">За успешно обученного</label>
    </div>
    @error('per_student_enabled')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3" id="per_student_amount_block" style="{{ $perStudentEnabled ? '' : 'display:none;' }}">
    <label for="per_student_amount">Вознаграждение за слушателя, руб.</label>
    <input
        type="number"
        step="0.01"
        min="0"
        name="per_student_amount"
        id="per_student_amount"
        class="form-control"
        value="{{ old('per_student_amount', $assignment?->per_student_amount) }}"
    >
    @error('per_student_amount')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="issued_at">Дата задания</label>
    <input
        type="date"
        name="issued_at"
        id="issued_at"
        class="form-control"
        value="{{ old('issued_at', optional($assignment?->issued_at)->format('Y-m-d')) }}"
    >
    @error('issued_at')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="starts_at">Срок оказания услуг — с</label>
    <input
        type="date"
        name="starts_at"
        id="starts_at"
        class="form-control"
        value="{{ old('starts_at', optional($assignment?->starts_at)->format('Y-m-d')) }}"
        required
    >
    @error('starts_at')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ends_at">Срок оказания услуг — по</label>
    <input
        type="date"
        name="ends_at"
        id="ends_at"
        class="form-control"
        value="{{ old('ends_at', optional($assignment?->ends_at)->format('Y-m-d')) }}"
        required
    >
    @error('ends_at')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<script>
    (function () {
        function syncWorkTypeExtras() {
            document.querySelectorAll('.work-type-extra').forEach(function (block) {
                var code = block.getAttribute('data-for-work-type');
                var checkbox = document.querySelector('.work-type-checkbox[data-work-type="' + code + '"]');
                block.style.display = (checkbox && checkbox.checked) ? '' : 'none';
            });
        }

        document.querySelectorAll('.work-type-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', syncWorkTypeExtras);
        });

        syncWorkTypeExtras();

        var baseAmountLabels = @json($baseAmountLabels);
        var paymentTypeSelect = document.getElementById('payment_type');
        var hourlyRateLabel = document.getElementById('hourly_rate_label');
        var baseAmountBlock = document.getElementById('base_payment_amount_block');
        var perStudentCheckbox = document.getElementById('per_student_enabled');
        var perStudentAmountBlock = document.getElementById('per_student_amount_block');

        function syncPaymentFields() {
            var type = paymentTypeSelect ? paymentTypeSelect.value : 'hourly';
            var hasBase = type !== 'none';

            if (baseAmountBlock) {
                baseAmountBlock.style.display = hasBase ? '' : 'none';
            }
            if (hourlyRateLabel && hasBase) {
                hourlyRateLabel.textContent = baseAmountLabels[type] || baseAmountLabels.hourly;
            }
            if (perStudentAmountBlock && perStudentCheckbox) {
                perStudentAmountBlock.style.display = perStudentCheckbox.checked ? '' : 'none';
            }
        }

        if (paymentTypeSelect) {
            paymentTypeSelect.addEventListener('change', syncPaymentFields);
        }
        if (perStudentCheckbox) {
            perStudentCheckbox.addEventListener('change', syncPaymentFields);
        }
        syncPaymentFields();
    })();
</script>
