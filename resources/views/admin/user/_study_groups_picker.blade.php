@php
    $selectedStudyGroupIds = collect($selectedStudyGroupIds ?? [])
        ->map(static fn ($id) => (int) $id)
        ->filter()
        ->unique()
        ->values()
        ->all();
@endphp

<div class="mb-0 form-group" id="study-groups-picker">
    <label>Группы</label>

    <div class="d-flex flex-wrap align-items-center mb-2">
        <button
            type="button"
            class="btn btn-outline-primary btn-sm"
            data-toggle="modal"
            data-target="#studyGroupsModal"
        >
            <i class="fas fa-users mr-1"></i> Выбрать группы
        </button>
        <span class="ml-2 text-muted small" id="study-groups-count"></span>
    </div>

    <div id="study-groups-summary" class="mb-1"></div>
    <div id="study-groups-inputs">
        @foreach($selectedStudyGroupIds as $studyGroupId)
            <input type="hidden" name="study_group_ids[]" value="{{ $studyGroupId }}">
        @endforeach
    </div>

    @error('study_group_ids')
    <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('study_group_ids.*')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
