@php
    /** @var \App\Models\ContractReport|null $report */
    $report = $report ?? null;
    /** @var \App\Models\ContractAssignment|null $assignment */
    $assignment = $assignment ?? $report?->assignment;
    $fixedAssignment = $fixedAssignment ?? (bool) $report;
    $assignments = $assignments ?? collect($assignment ? [$assignment] : []);
    $kind = $kind ?? $report?->kind ?? \App\Models\ContractReport::KIND_MAIN;
    $isPerStudent = $kind === \App\Models\ContractReport::KIND_PER_STUDENT;
    $students = old('successful_students', $report?->successfulStudentsList() ?: ['']);
    if (! is_array($students) || $students === []) {
        $students = [''];
    }
@endphp

<input type="hidden" name="kind" value="{{ $kind }}">

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
    @if($isPerStudent)
        <div class="input-group" style="max-width: 12rem;">
            <input
                type="number"
                min="1"
                name="number"
                id="number"
                class="form-control"
                value="{{ old('number', $report?->number ?? $assignment?->number ?? 1) }}"
                required
                {{ $fixedAssignment ? 'readonly' : '' }}
            >
            <div class="input-group-append">
                <span class="input-group-text">.2</span>
            </div>
        </div>
        <small class="form-text text-muted">Отображается как №{{ $assignment?->number ?? 'N' }}.2</small>
    @else
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
    @endif
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

@if($isPerStudent)
    <div class="mb-3">
        <label>Успешно обученные слушатели</label>
        <div id="successful_students_list">
            @foreach($students as $index => $studentName)
                <div class="input-group mb-2 successful-student-row">
                    <input
                        type="text"
                        name="successful_students[]"
                        class="form-control"
                        placeholder="Фамилия И.О."
                        value="{{ $studentName }}"
                    >
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary js-remove-student" title="Удалить">×</button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" id="js-add-student">Добавить слушателя</button>
        @error('successful_students')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
        @error('successful_students.*')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <script>
        (function () {
            var list = document.getElementById('successful_students_list');
            var addBtn = document.getElementById('js-add-student');
            if (!list || !addBtn) return;

            function bindRemove(btn) {
                btn.addEventListener('click', function () {
                    var rows = list.querySelectorAll('.successful-student-row');
                    if (rows.length <= 1) {
                        var input = rows[0] && rows[0].querySelector('input');
                        if (input) input.value = '';
                        return;
                    }
                    btn.closest('.successful-student-row').remove();
                });
            }

            list.querySelectorAll('.js-remove-student').forEach(bindRemove);

            addBtn.addEventListener('click', function () {
                var row = document.createElement('div');
                row.className = 'input-group mb-2 successful-student-row';
                row.innerHTML =
                    '<input type="text" name="successful_students[]" class="form-control" placeholder="Фамилия И.О." value="">' +
                    '<div class="input-group-append">' +
                    '<button type="button" class="btn btn-outline-secondary js-remove-student" title="Удалить">×</button>' +
                    '</div>';
                list.appendChild(row);
                bindRemove(row.querySelector('.js-remove-student'));
                row.querySelector('input').focus();
            });
        })();
    </script>
@else
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
@endif

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
