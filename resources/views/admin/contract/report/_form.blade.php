@php
    /** @var \App\Models\ContractReport|null $report */
    $report = $report ?? null;
    /** @var \App\Models\ContractAssignment|null $assignment */
    $assignment = $assignment ?? $report?->assignment;
    $fixedAssignment = $fixedAssignment ?? (bool) $report;
    $assignments = $assignments ?? collect($assignment ? [$assignment] : []);
@endphp

@if(! $fixedAssignment)
    <div class="mb-3">
        <label for="assignment_id">Задание</label>
        <select name="assignment_id" id="assignment_id" class="form-control select2" style="width: 100%;" required>
            <option value="">Выберите задание</option>
            @foreach($assignments as $item)
                <option
                    value="{{ $item->id }}"
                    data-number="{{ $item->number }}"
                    data-starts="{{ optional($item->starts_at)->format('Y-m-d') }}"
                    data-ends="{{ optional($item->ends_at)->format('Y-m-d') }}"
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
    <label for="number">Номер отчёта</label>
    <input
        type="number"
        min="1"
        name="number"
        id="number"
        class="form-control"
        style="max-width: 10rem;"
        value="{{ old('number', $report?->number ?? $assignment?->number ?? 1) }}"
        required
    >
    @error('number')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

@include('admin.contract._status_field', ['selectedStatus' => $report?->status])

<div class="mb-3">
    <label for="issued_at">Дата отчёта</label>
    <input
        type="date"
        name="issued_at"
        id="issued_at"
        class="form-control"
        style="max-width: 16rem;"
        value="{{ old('issued_at', optional($report?->issued_at)->format('Y-m-d')) }}"
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
        style="max-width: 16rem;"
        value="{{ old('starts_at', optional($report?->starts_at ?? $assignment?->starts_at)->format('Y-m-d')) }}"
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
        style="max-width: 16rem;"
        value="{{ old('ends_at', optional($report?->ends_at ?? $assignment?->ends_at)->format('Y-m-d')) }}"
        required
    >
    @error('ends_at')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="lesson_hours">Количество проведённых акад. часов</label>
    <input
        type="number"
        step="0.01"
        min="0"
        name="lesson_hours"
        id="lesson_hours"
        class="form-control"
        style="max-width: 10rem;"
        value="{{ old('lesson_hours', $report?->lesson_hours) }}"
    >
    @error('lesson_hours')
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

            var numberInput = document.getElementById('number');
            var startsInput = document.getElementById('starts_at');
            var endsInput = document.getElementById('ends_at');

            if (numberInput && option.dataset.number) {
                numberInput.value = option.dataset.number;
            }
            if (startsInput && option.dataset.starts) {
                startsInput.value = option.dataset.starts;
            }
            if (endsInput && option.dataset.ends) {
                endsInput.value = option.dataset.ends;
            }
        }

        select.addEventListener('change', syncFromAssignment);
    })();
</script>
@endif
