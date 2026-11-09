@php
    $selectedOrgIds = collect($selectedOrgIds ?? [])
        ->map(static fn ($id) => (int) $id)
        ->filter()
        ->unique()
        ->values()
        ->all();
@endphp

<div class="mb-3 form-group" id="agent-orgs-picker">
    <label>Организации (Заявки по БИО)</label>

    <div class="d-flex flex-wrap align-items-center mb-2">
        <button
            type="button"
            class="btn btn-outline-primary btn-sm"
            data-toggle="modal"
            data-target="#agentOrgsModal"
        >
            <i class="fas fa-building mr-1"></i> Выбрать организации
        </button>
        <span class="ml-2 text-muted small" id="agent-orgs-count"></span>
    </div>

    <div id="agent-orgs-summary" class="mb-1"></div>
    <div id="agent-orgs-inputs">
        @foreach($selectedOrgIds as $orgId)
            <input type="hidden" name="org_ids[]" value="{{ $orgId }}">
        @endforeach
    </div>

    @error('org_ids')
    <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('org_ids.*')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
