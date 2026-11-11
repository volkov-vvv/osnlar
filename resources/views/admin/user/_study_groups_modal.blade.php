@php
    $selectedStudyGroupIds = collect($selectedStudyGroupIds ?? [])
        ->map(static fn ($id) => (int) $id)
        ->filter()
        ->unique()
        ->values()
        ->all();
@endphp

<style>
    #studyGroupsModal .modal-dialog {
        max-width: 640px;
    }

    #studyGroupsModal .modal-content {
        border: 0;
        border-radius: .4rem;
        box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, .18);
        overflow: hidden;
    }

    #studyGroupsModal .modal-header {
        align-items: center;
        background: #094ca1;
        border-bottom: 0;
        color: #fff;
        padding: .9rem 1.15rem;
    }

    #studyGroupsModal .modal-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    #studyGroupsModal .modal-header .close {
        color: #fff;
        opacity: .85;
        text-shadow: none;
    }

    #studyGroupsModal .modal-header .close:hover {
        opacity: 1;
    }

    #studyGroupsModal .modal-body {
        background: #f4f6f9;
        padding: 1rem 1.15rem;
    }

    #studyGroupsModal .study-groups-toolbar {
        background: #fff;
        border: 1px solid #e3e6ea;
        border-radius: .35rem;
        margin-bottom: .85rem;
        padding: .85rem;
    }

    #studyGroupsModal .study-groups-search {
        position: relative;
    }

    #studyGroupsModal .study-groups-search .form-control {
        border-radius: .3rem;
        padding-left: 2.25rem;
    }

    #studyGroupsModal .study-groups-search-icon {
        color: #6c757d;
        left: .75rem;
        pointer-events: none;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    #studyGroupsModal #studyGroupsList {
        background: #fff;
        border: 1px solid #e3e6ea;
        border-radius: .35rem;
        max-height: 380px;
        overflow-y: auto;
        padding: .4rem;
    }

    #studyGroupsModal .study-group-item {
        background: #fff;
        border: 1px solid transparent;
        border-radius: .3rem;
        margin: 0 0 .25rem;
        padding: .55rem .7rem .55rem 2.1rem;
        transition: background-color .12s ease, border-color .12s ease;
    }

    #studyGroupsModal .study-group-item:last-child {
        margin-bottom: 0;
    }

    #studyGroupsModal .study-group-item:hover {
        background: #f8fafc;
        border-color: #dbe3ec;
    }

    #studyGroupsModal .study-group-item.is-checked {
        background: #eaf4ff;
        border-color: #b7d7f7;
    }

    #studyGroupsModal .study-group-item .custom-control-label {
        cursor: pointer;
        font-size: .95rem;
        line-height: 1.35;
        padding-top: .05rem;
    }

    #studyGroupsModal .study-group-item .custom-control-label::before,
    #studyGroupsModal .study-group-item .custom-control-label::after {
        top: .2rem;
    }

    #studyGroupsModal .study-groups-empty {
        color: #6c757d;
        margin: 0;
        padding: 1.25rem .75rem;
        text-align: center;
    }

    #studyGroupsModal .modal-footer {
        background: #fff;
        border-top: 1px solid #e3e6ea;
        padding: .85rem 1.15rem;
    }
</style>

<div class="modal fade" id="studyGroupsModal" tabindex="-1" role="dialog" aria-labelledby="studyGroupsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="studyGroupsModalLabel">
                    <i class="fas fa-users mr-2"></i>Учебные группы
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="study-groups-toolbar">
                    <div class="study-groups-search mb-2">
                        <i class="fas fa-search study-groups-search-icon"></i>
                        <input
                            type="search"
                            id="studyGroupsSearch"
                            class="form-control"
                            placeholder="Поиск по названию группы…"
                            autocomplete="off"
                        >
                    </div>
                    <div class="d-flex flex-wrap mt-2">
                        <button type="button" class="btn btn-sm btn-outline-primary mr-2 mb-1" id="studyGroupsSelectAll">
                            Выбрать все
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary mb-1" id="studyGroupsClearAll">
                            Снять все
                        </button>
                    </div>
                </div>

                <div id="studyGroupsList">
                    @forelse($studyGroups as $studyGroup)
                        @php
                            $groupLabel = trim((string) ($studyGroup->short_title ?: $studyGroup->title));
                            $isChecked = in_array((int) $studyGroup->id, $selectedStudyGroupIds, true);
                        @endphp
                        <div
                            class="custom-control custom-checkbox study-group-item{{ $isChecked ? ' is-checked' : '' }}"
                            data-title="{{ mb_strtolower($groupLabel) }}"
                        >
                            <input
                                type="checkbox"
                                class="custom-control-input study-group-checkbox"
                                id="study-group-{{ $studyGroup->id }}"
                                value="{{ $studyGroup->id }}"
                                data-title="{{ $groupLabel }}"
                                {{ $isChecked ? 'checked' : '' }}
                            >
                            <label class="custom-control-label" for="study-group-{{ $studyGroup->id }}">
                                {{ $groupLabel }}
                            </label>
                        </div>
                    @empty
                        <p class="study-groups-empty">Нет учебных групп.</p>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="studyGroupsApply">
                    <i class="fas fa-check mr-1"></i>Применить
                </button>
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="study-groups-catalog">
@json(
    collect($studyGroups)->mapWithKeys(static function ($studyGroup) {
        $label = trim((string) ($studyGroup->short_title ?: $studyGroup->title));

        return [(string) $studyGroup->id => $label];
    })
)
</script>
