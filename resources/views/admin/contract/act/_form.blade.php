@php
    /** @var \App\Models\ContractAct|null $act */
    $act = $act ?? null;
    /** @var \App\Models\ContractAssignment|null $assignment */
    $assignment = $assignment ?? $act?->assignment;
    $fixedAssignment = $fixedAssignment ?? (bool) $act;
    $assignments = $assignments ?? collect($assignment ? [$assignment] : []);
    $nextNumber = $nextNumber ?? 1;
    $suggestedAmount = $suggestedAmount ?? ($assignment ? \App\Models\ContractAct::suggestedAmountForAssignment($assignment) : null);
@endphp

@if(! $fixedAssignment)
    <div class="mb-3">
        <label for="assignment_id">Задание</label>
        <select name="assignment_id" id="assignment_id" class="form-control select2" style="width: 100%;" required>
            <option value="">Выберите задание</option>
            @foreach($assignments as $item)
                @php
                    $itemSuggested = \App\Models\ContractAct::suggestedAmountForAssignment($item);
                @endphp
                <option
                    value="{{ $item->id }}"
                    data-amount="{{ $itemSuggested !== null ? $itemSuggested : '' }}"
                    data-rate="{{ $item->hourly_rate }}"
                    data-hours="{{ $item->report?->lesson_hours }}"
                    {{ (string) old('assignment_id', $assignment?->id) === (string) $item->id ? 'selected' : '' }}
                >
                    №{{ $item->number }}
                    @if($item->studyGroup)
                        — {{ $item->studyGroup->title }}
                    @endif
                </option>
            @endforeach
        </select>
        @error('assignment_id')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
@else
    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
    <div class="mb-3">
        <label>Задание</label>
        <input type="text" class="form-control" value="№{{ $assignment->number }}" readonly>
    </div>
@endif

<div class="mb-3">
    <label for="number">Номер акта</label>
    <input
        type="number"
        min="1"
        name="number"
        id="number"
        class="form-control"
        style="max-width: 10rem;"
        value="{{ old('number', $act?->number ?? $nextNumber) }}"
        required
    >
    @error('number')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

@include('admin.contract._status_field', ['selectedStatus' => $act?->status])

<div class="mb-3">
    <label for="issued_at">Дата акта</label>
    <input
        type="date"
        name="issued_at"
        id="issued_at"
        class="form-control"
        style="max-width: 16rem;"
        value="{{ old('issued_at', optional($act?->issued_at)->format('Y-m-d')) }}"
    >
    @error('issued_at')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="amount">Стоимость оказанных услуг, руб.</label>
    <input
        type="number"
        step="0.01"
        min="0"
        name="amount"
        id="amount"
        class="form-control"
        style="max-width: 12rem;"
        value="{{ old('amount', $act?->amount ?? $suggestedAmount) }}"
    >
    <small class="form-text text-muted" id="amount_hint">
        По умолчанию: ставка задания × часы из отчёта
        @if($assignment && $suggestedAmount !== null)
            ({{ $assignment->hourlyRateFormatted() }} × {{ $assignment->report?->lessonHoursFormatted() }} = {{ number_format($suggestedAmount, 2, ',', ' ') }})
        @endif
    </small>
    @error('amount')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

@if(! $fixedAssignment)
<script>
    (function () {
        var select = document.getElementById('assignment_id');
        if (!select) return;

        function syncFromAssignment() {
            var option = select.options[select.selectedIndex];
            if (!option || !option.value) return;

            var amountInput = document.getElementById('amount');
            var hint = document.getElementById('amount_hint');
            if (amountInput && option.dataset.amount !== undefined) {
                amountInput.value = option.dataset.amount || '';
            }
            if (hint) {
                if (option.dataset.amount) {
                    hint.textContent = 'По умолчанию: ставка задания × часы из отчёта (' +
                        (option.dataset.rate || '') + ' × ' + (option.dataset.hours || '') +
                        ' = ' + option.dataset.amount + ')';
                } else {
                    hint.textContent = 'По умолчанию: ставка задания × часы из отчёта (отчёт или часы отсутствуют — укажите сумму вручную)';
                }
            }
        }

        select.addEventListener('change', syncFromAssignment);
    })();
</script>
@endif
