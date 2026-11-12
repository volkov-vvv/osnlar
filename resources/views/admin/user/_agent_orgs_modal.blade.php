@php
    $selectedOrgIds = collect($selectedOrgIds ?? [])
        ->map(static fn ($id) => (int) $id)
        ->filter()
        ->unique()
        ->values()
        ->all();
@endphp

<style>
    #agentOrgsModal .modal-dialog {
        max-width: 640px;
    }

    #agentOrgsModal .modal-content {
        border: 0;
        border-radius: .4rem;
        box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, .18);
        overflow: hidden;
    }

    #agentOrgsModal .modal-header {
        align-items: center;
        background: #094ca1;
        border-bottom: 0;
        color: #fff;
        padding: .9rem 1.15rem;
    }

    #agentOrgsModal .modal-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    #agentOrgsModal .modal-header .close {
        color: #fff;
        opacity: .85;
        text-shadow: none;
    }

    #agentOrgsModal .modal-header .close:hover {
        opacity: 1;
    }

    #agentOrgsModal .modal-body {
        background: #f4f6f9;
        padding: 1rem 1.15rem;
    }

    #agentOrgsModal .agent-orgs-toolbar {
        background: #fff;
        border: 1px solid #e3e6ea;
        border-radius: .35rem;
        margin-bottom: .85rem;
        padding: .85rem;
    }

    #agentOrgsModal .agent-orgs-search {
        position: relative;
    }

    #agentOrgsModal .agent-orgs-search .form-control {
        border-radius: .3rem;
        padding-left: 2.25rem;
    }

    #agentOrgsModal .agent-orgs-search-icon {
        color: #6c757d;
        left: .75rem;
        pointer-events: none;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    #agentOrgsModal #agentOrgsList {
        background: #fff;
        border: 1px solid #e3e6ea;
        border-radius: .35rem;
        max-height: 380px;
        overflow-y: auto;
        padding: .4rem;
    }

    #agentOrgsModal .agent-org-item {
        background: #fff;
        border: 1px solid transparent;
        border-radius: .3rem;
        margin: 0 0 .25rem;
        padding: .55rem .7rem .55rem 2.1rem;
        transition: background-color .12s ease, border-color .12s ease;
    }

    #agentOrgsModal .agent-org-item:last-child {
        margin-bottom: 0;
    }

    #agentOrgsModal .agent-org-item:hover {
        background: #f8fafc;
        border-color: #dbe3ec;
    }

    #agentOrgsModal .agent-org-item.is-checked {
        background: #eaf4ff;
        border-color: #b7d7f7;
    }

    #agentOrgsModal .agent-org-item .custom-control-label {
        cursor: pointer;
        font-size: .95rem;
        line-height: 1.35;
        padding-top: .05rem;
    }

    #agentOrgsModal .agent-org-item .custom-control-label::before,
    #agentOrgsModal .agent-org-item .custom-control-label::after {
        top: .2rem;
    }

    #agentOrgsModal .agent-org-inn {
        color: #495057;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: .9rem;
        font-weight: 600;
        margin-right: .35rem;
    }

    #agentOrgsModal .agent-orgs-empty {
        color: #6c757d;
        margin: 0;
        padding: 1.25rem .75rem;
        text-align: center;
    }

    #agentOrgsModal .modal-footer {
        background: #fff;
        border-top: 1px solid #e3e6ea;
        padding: .85rem 1.15rem;
    }
</style>

<div class="modal fade" id="agentOrgsModal" tabindex="-1" role="dialog" aria-labelledby="agentOrgsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="agentOrgsModalLabel">
                    <i class="fas fa-building mr-2"></i>Организации (Заявки по БИО)
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="agent-orgs-toolbar">
                    <div class="agent-orgs-search mb-2">
                        <i class="fas fa-search agent-orgs-search-icon"></i>
                        <input
                            type="search"
                            id="agentOrgsSearch"
                            class="form-control"
                            placeholder="Поиск по ИНН или названию…"
                            autocomplete="off"
                        >
                    </div>
                    <div class="d-flex flex-wrap mt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary mr-2 mb-1" id="agentOrgsSelectAll">
                            Выбрать все
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary mb-1" id="agentOrgsClearAll">
                            Снять все
                        </button>
                    </div>
                </div>

                <div id="agentOrgsList">
                    @forelse($organizations as $organization)
                        @php
                            $orgInn = trim((string) $organization->inn);
                            $orgTitle = trim((string) $organization->organization_title);
                            $orgLabel = $orgInn !== '' ? ($orgInn.' — '.$orgTitle) : $orgTitle;
                            $isChecked = in_array((int) $organization->id, $selectedOrgIds, true);
                        @endphp
                        <div
                            class="custom-control custom-checkbox agent-org-item{{ $isChecked ? ' is-checked' : '' }}"
                            data-title="{{ mb_strtolower($orgLabel) }}"
                        >
                            <input
                                type="checkbox"
                                class="custom-control-input agent-org-checkbox"
                                id="agent-org-{{ $organization->id }}"
                                value="{{ $organization->id }}"
                                data-title="{{ $orgLabel }}"
                                {{ $isChecked ? 'checked' : '' }}
                            >
                            <label class="custom-control-label" for="agent-org-{{ $organization->id }}">
                                @if($orgInn !== '')
                                    <span class="agent-org-inn">{{ $orgInn }}</span>
                                    <span class="text-muted">—</span>
                                    {{ $orgTitle }}
                                @else
                                    {{ $orgTitle }}
                                @endif
                            </label>
                        </div>
                    @empty
                        <p class="agent-orgs-empty">Нет опубликованных организаций биоэкономики.</p>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="agentOrgsApply">
                    <i class="fas fa-check mr-1"></i>Применить
                </button>
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="agent-orgs-catalog">
@json(
    collect($organizations)->mapWithKeys(static function ($org) {
        $inn = trim((string) $org->inn);
        $title = trim((string) $org->organization_title);
        $label = $inn !== '' ? ($inn.' — '.$title) : $title;

        return [(string) $org->id => $label];
    })
)
</script>
