@php
    $selectedOrgIds = collect($selectedOrgIds ?? [])
        ->map(static fn ($id) => (int) $id)
        ->filter()
        ->unique()
        ->values()
        ->all();
@endphp

<div class="modal fade" id="agentOrgsModal" tabindex="-1" role="dialog" aria-labelledby="agentOrgsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="agentOrgsModalLabel">Организации (Заявки по БИО)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input
                        type="search"
                        id="agentOrgsSearch"
                        class="form-control"
                        placeholder="Поиск по названию…"
                        autocomplete="off"
                    >
                </div>

                <div class="d-flex mb-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary mr-2" id="agentOrgsSelectAll">Выбрать все</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="agentOrgsClearAll">Снять все</button>
                </div>

                <div id="agentOrgsList" style="max-height: 360px; overflow-y: auto;">
                    @forelse($organizations as $organization)
                        <div class="custom-control custom-checkbox mb-2 agent-org-item" data-title="{{ mb_strtolower($organization->organization_title) }}">
                            <input
                                type="checkbox"
                                class="custom-control-input agent-org-checkbox"
                                id="agent-org-{{ $organization->id }}"
                                value="{{ $organization->id }}"
                                data-title="{{ $organization->organization_title }}"
                                {{ in_array((int) $organization->id, $selectedOrgIds, true) ? 'checked' : '' }}
                            >
                            <label class="custom-control-label" for="agent-org-{{ $organization->id }}">
                                {{ $organization->organization_title }}
                            </label>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Нет опубликованных организаций биоэкономики.</p>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="agentOrgsApply">Применить</button>
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="agent-orgs-catalog">
@json(
    collect($organizations)->mapWithKeys(static fn ($org) => [
        (string) $org->id => $org->organization_title,
    ])
)
</script>
