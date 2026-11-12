@extends($panel . '.layouts.main')
@section('content')
    <style>
        .course-short-title-hint {
            display: inline-flex;
            align-items: center;
            cursor: help;
        }

        .course-short-title-popup {
            position: fixed;
            z-index: 2000;
            max-width: 360px;
            padding: 0.45rem 0.65rem;
            border-radius: 0.35rem;
            background: #343a40;
            color: #fff;
            font-size: 0.8rem;
            line-height: 1.35;
            white-space: normal;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.2);
            pointer-events: none;
        }

        #biolids_table_wrapper .dt-buttons.btn-group {
            display: inline-flex;
            flex-wrap: nowrap;
            align-items: stretch;
            gap: 1px;
            background-color: #fff;
            border-radius: 0.25rem;
            max-width: 100%;
        }

        #biolids_table_wrapper .dt-buttons.btn-group > .btn,
        #biolids_table_wrapper .dt-buttons.btn-group > .btn-group {
            margin-left: 0 !important;
        }

        #biolids_table_wrapper .dt-buttons.btn-group > .btn-group {
            background-color: transparent;
            display: inline-flex;
            padding: 0;
            font-size: inherit;
        }

        #biolids_table_wrapper .dt-buttons .btn {
            white-space: nowrap;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заявки по БИО</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col d-flex flex-wrap align-items-center" style="gap: 0.5rem;">
                    @if($panel !== 'agent')
                        <a href="{{ route($panel . '.biolid.create') }}" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Создать</a>
                    @endif
                    @if(in_array($panel, ['admin', 'agent'], true))
                        <button type="button" class="btn btn-primary" id="biolidQuestionnaireBtn">
                            <i class="fas fa-file-excel mr-1"></i>Сформировать анкету для группы
                        </button>
                        <button type="button" class="btn btn-primary" id="biolidArchiveBtn">
                            <i class="fas fa-file-archive mr-1"></i>Сформировать архив
                        </button>
                    @endif
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('error_details'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Детали ошибок:</strong>
                    <ul class="mb-0 mt-2 pl-3">
                        @foreach((array) session('error_details') as $detail)
                            <li>{{ $detail }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row pb-3">
                                <div class="col col-md-3">
                                    Организация:
                                    <select id="organization" name="organization" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($organizations as $organization)
                                            <option value="{{ $organization }}">{{ $organization }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-3">
                                    Курс:
                                    <select id="course" name="course" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->short_title ?: $course->title }}">{{ $course->short_title ?: $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-3">
                                    Учебные группы:
                                    <select id="studygroup" name="studygroup" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($studyGroups as $studyGroup)
                                            <option value="{{ $studyGroup->short_title ?: $studyGroup->title }}">{{ $studyGroup->short_title ?: $studyGroup->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-3 d-flex justify-content-end align-items-end">
                                    <button id="resetTable" class="btn btn-secondary"><i class="fas fa-eraser mr-1"></i>Очистить фильтры</button>
                                </div>
                            </div>
                            <div class="row pb-3">
                                @if($panel !== 'agent')
                                    <div class="col col-md-2">
                                        Ответственный:
                                        <select id="responsible" name="responsible" class="form-control form-control-sm custom-filters">
                                            <option></option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col col-md-2">
                                        Статус:
                                        <select id="status" name="status" class="form-control form-control-sm custom-filters">
                                            <option></option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->title }}">{{ $status->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col col-md-2">
                                    Статус документы:
                                    <select id="docs_status" name="docs_status" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        <option value="Не отправлено">Не отправлено</option>
                                        <option value="На проверке">На проверке</option>
                                        <option value="Ожидает этап 2">Ожидает этап 2</option>
                                        <option value="Отклонено">Отклонено</option>
                                        <option value="Проверено">Проверено</option>
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    Статус АРПМ:
                                    <select id="arpm_status" name="arpm_status" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach(\App\Models\BioLid::ARPM_STATUSES as $arpmLabel)
                                            <option value="{{ $arpmLabel }}">{{ $arpmLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <table id="biolids_table" class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ответственный</th>
                                    <th>Организация</th>
                                    <th>Фамилия</th>
                                    <th>Имя</th>
                                    <th>Отчество</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Курс</th>
                                    <th>Учебные группы</th>
                                    <th>Статус</th>
                                    <th>Статус документы</th>
                                    <th>Статус АРПМ</th>
                                    <th>Дата создания</th>
                                    <th>Дата обновления</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bioLids as $bioLid)
                                    @php
                                        $statusTitle = $bioLid->status->title ?? '';
                                        $statusColor = $bioLid->status->color ?? null;
                                        $docsReview = $bioLid->documentsReviewSummary();
                                    @endphp
                                    <tr data-id="{{ $bioLid->id }}">
                                        <td>{{ $bioLid->id }}</td>
                                        <td>{{ $bioLid->responsible->name ?? '' }}</td>
                                        <td>{{ $bioLid->organization_title ?: '—' }}</td>
                                        <td>
                                            <span class="d-inline-flex align-items-center">
                                                {{ $bioLid->lastname }}
                                                @if($bioLid->user)
                                                    @if($panel === 'admin')
                                                        <a
                                                            href="{{ route('admin.user.show', $bioLid->user->id) }}"
                                                            class="ml-1 text-info"
                                                            title="Учётная запись создана"
                                                        >
                                                            <i class="fas fa-user-circle" aria-hidden="true"></i>
                                                        </a>
                                                    @else
                                                        <span class="ml-1 text-info" title="Учётная запись создана">
                                                            <i class="fas fa-user-circle" aria-hidden="true"></i>
                                                        </span>
                                                    @endif
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $bioLid->firstname }}</td>
                                        <td>{{ $bioLid->middlename ?: '—' }}</td>
                                        <td>{{ $bioLid->email }}</td>
                                        <td>{{ $bioLid->phoneDisplay() }}</td>
                                        <td>
                                            @if($bioLid->course && $bioLid->course->short_title)
                                                <span class="d-inline-flex align-items-center">
                                                    {{ $bioLid->course->short_title }}
                                                    <span
                                                        class="course-short-title-hint ml-1"
                                                        data-full-title="{{ $bioLid->course->title }}"
                                                        aria-label="{{ $bioLid->course->title }}"
                                                    >
                                                        <i class="fas fa-question-circle text-muted" aria-hidden="true"></i>
                                                    </span>
                                                </span>
                                            @else
                                                {{ $bioLid->course->title ?? '—' }}
                                            @endif
                                        </td>
                                        <td>
                                            @forelse($bioLid->user?->studyGroups ?? [] as $studyGroup)
                                                @if($panel === 'admin')
                                                    <a href="{{ route('admin.studygroup.show', $studyGroup->id) }}">{{ $studyGroup->short_title ?: $studyGroup->title }}</a>@if(!$loop->last), @endif
                                                @else
                                                    {{ $studyGroup->short_title ?: $studyGroup->title }}@if(!$loop->last), @endif
                                                @endif
                                            @empty
                                                —
                                            @endforelse
                                        </td>
                                        <td>
                                            @if($statusColor)
                                                <span
                                                    class="badge rounded-pill"
                                                    style="background-color: {{ $statusColor }} !important; color: {{ contrast_color($statusColor) }}"
                                                >{{ $statusTitle }}</span>
                                            @else
                                                <span class="badge rounded-pill badge-secondary">{{ $statusTitle ?: '—' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="{{ $docsReview['badge'] }}">{{ $docsReview['label'] }}</span>
                                        </td>
                                        <td>
                                            <span class="{{ $bioLid->arpmStatusBadgeClass() }}"><i class="{{ $bioLid->arpmStatusIconClass() }} mr-1" aria-hidden="true"></i>{{ $bioLid->arpmStatusLabel() }}</span>
                                        </td>
                                        <td>{{ $bioLid->created_at }}</td>
                                        <td>{{ $bioLid->updated_at }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route($panel . '.biolid.show', $bioLid->id) }}" title="Просмотр"><i class="far fa-eye"></i></a>
                                                @if($panel !== 'agent')
                                                    &nbsp;&nbsp;
                                                    <a href="{{ route($panel . '.biolid.edit', $bioLid->id) }}" title="Редактировать"><i class="fas fa-pencil-alt text-success"></i></a>&nbsp;&nbsp;
                                                    <form method="post" action="{{ route($panel . '.biolid.destroy', $bioLid->id) }}" class="mb-0" onsubmit="return confirm('Удалить заявку?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="bg-transparent border-0" type="submit" title="Удалить"><i class="fas fa-trash text-danger" role="button"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="createUsersBulkModal" tabindex="-1" role="dialog" aria-labelledby="createUsersBulkModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createUsersBulkModalLabel">Создать пользователей</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">
                        Будут созданы или привязаны учётные записи для заявок по текущим фильтрам:
                        <strong id="createUsersBulkCount">0</strong>.
                    </p>
                    <p class="text-muted mb-3">Письма с доступом отправляться не будут.</p>

                    <div class="form-group mb-0">
                        <label for="createUsersBulkStudyGroup">Учебная группа для записи</label>
                        <select id="createUsersBulkStudyGroup" class="form-control">
                            <option value="">Не назначать</option>
                            @foreach($studyGroups as $studyGroup)
                                <option value="{{ $studyGroup->id }}">
                                    {{ $studyGroup->short_title ? $studyGroup->short_title.' — '.$studyGroup->title : $studyGroup->title }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Если группа выбрана, слушатели будут добавлены в неё.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="createUsersBulkSubmit">
                        <i class="fas fa-user-plus mr-1"></i>Создать
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @include('partials.datatable-filter-persist', ['pageUrl' => $panel . '.biolid.index'])
    <script>
        var createUsersBulkIds = [];

        function biolidFilteredIds() {
            var ids = [];
            table.rows({ search: 'applied' }).every(function () {
                var id = $(this.node()).data('id');
                if (id) {
                    ids.push(id);
                }
            });
            return ids;
        }

        var table = new DataTable('#biolids_table', {
            stateSave: true,
            stateDuration: 0,

            stateSaveParams: function (settings, data) {
                data.custom_filters = {
                    organization: $('#organization').val(),
                    course: $('#course').val(),
                    studygroup: $('#studygroup').val(),
                    responsible: $('#responsible').val(),
                    status: $('#status').val(),
                    docs_status: $('#docs_status').val(),
                    arpm_status: $('#arpm_status').val(),
                };
            },

            stateLoadParams: function (settings, data) {
                if (!data || !data.custom_filters) {
                    return;
                }

                var filters = data.custom_filters;

                $('#organization').val(filters.organization || '');
                $('#course').val(filters.course || '');
                $('#studygroup').val(filters.studygroup || '');
                $('#responsible').val(filters.responsible || '');
                $('#status').val(filters.status || '');
                $('#docs_status').val(filters.docs_status || '');
                $('#arpm_status').val(filters.arpm_status || '');

                var columnFilters = {
                    1: filters.responsible,
                    2: filters.organization,
                    8: filters.course,
                    9: filters.studygroup,
                    10: filters.status,
                    11: filters.docs_status,
                    12: filters.arpm_status
                };

                if (!data.columns) {
                    return;
                }

                $.each(columnFilters, function (index, value) {
                    if (!data.columns[index] || !data.columns[index].search) {
                        return;
                    }

                    data.columns[index].search.search = value || '';
                });
            },

            stateSaveCallback: function (settings, data) {
                window.OsnlarFilterPersist.scheduleSave(data);
            },

            stateLoadCallback: function (settings, callback) {
                window.OsnlarFilterPersist.load(callback);
            },

            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, searchable: false, targets: 15 },
                @if($panel === 'agent')
                { targets: [1, 6, 7, 10, 13], visible: false }
                @endif
            ],
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            dom: "<'row mb-3 align-items-center'<'col-sm-12 col-lg-3'f><'col-sm-12 col-lg-9 text-lg-right'B>>" +
                "<'row'<'col-sm-12'<'table-responsive'tr>>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                @if($panel === 'admin')
                {
                    text: '<i class="fas fa-user-plus mr-1"></i>Создать пользователей',
                    className: 'btn-secondary',
                    action: function (e, dt) {
                        var ids = [];
                        dt.rows({ search: 'applied' }).every(function () {
                            var id = $(this.node()).data('id');
                            if (id) {
                                ids.push(id);
                            }
                        });

                        if (!ids.length) {
                            alert('Нет заявок для создания пользователей по текущим фильтрам');
                            return;
                        }

                        createUsersBulkIds = ids;
                        $('#createUsersBulkCount').text(ids.length);
                        $('#createUsersBulkStudyGroup').val('');
                        $('#createUsersBulkModal').modal('show');
                    }
                },
                {
                    text: '<i class="fas fa-envelope mr-1"></i>Отправить письмо',
                    className: 'btn-secondary',
                    action: function (e, dt) {
                        var ids = [];
                        dt.rows({ search: 'applied' }).every(function () {
                            var id = $(this.node()).data('id');
                            if (id) {
                                ids.push(id);
                            }
                        });

                        if (!ids.length) {
                            alert('Нет заявок для отправки по текущим фильтрам');
                            return;
                        }

                        if (!confirm(
                            'Будет отправлено писем с логином и паролем: ' + ids.length + '.\n' +
                            'При необходимости будет создана учётная запись, пароль будет обновлён. Продолжить?'
                        )) {
                            return;
                        }

                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = @json(route($panel . '.biolid.send-credentials-bulk'));
                        form.style.display = 'none';

                        var csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = @json(csrf_token());
                        form.appendChild(csrf);

                        ids.forEach(function (id) {
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            form.appendChild(input);
                        });

                        document.body.appendChild(form);
                        form.submit();
                    }
                },
                @endif
                "excel",
                { extend: "colvis", align: "button-right" }
            ],
            language: {
                info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                infoFiltered: "(отфильтровано из _MAX_ записей)",
                paginate: {
                    first: "Первая",
                    previous: "<<",
                    next: ">>",
                    last: "Последняя"
                },
                search: "Поиск:",
                buttons: {
                    colvis: 'Выбрать колонки'
                },
                emptyTable: "Пока нет заявок по Биоэкономике",
                zeroRecords: "Ничего не найдено"
            }
        });

        $('#createUsersBulkSubmit').on('click', function () {
            if (!createUsersBulkIds.length) {
                alert('Нет заявок для создания пользователей по текущим фильтрам');
                return;
            }

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = @json(route($panel . '.biolid.create-users-bulk'));
            form.style.display = 'none';

            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = @json(csrf_token());
            form.appendChild(csrf);

            createUsersBulkIds.forEach(function (id) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            var studyGroupInput = document.createElement('input');
            studyGroupInput.type = 'hidden';
            studyGroupInput.name = 'study_group_id';
            studyGroupInput.value = $('#createUsersBulkStudyGroup').val() || '';
            form.appendChild(studyGroupInput);

            document.body.appendChild(form);
            form.submit();
        });

        $('#biolidQuestionnaireBtn').on('click', function () {
            var ids = biolidFilteredIds();
            if (!ids.length) {
                alert('Нет заявок для выгрузки по текущим фильтрам');
                return;
            }

            var params = ids.map(function (id) {
                return 'ids[]=' + encodeURIComponent(id);
            }).join('&');

            window.location.href = @json(route($panel . '.biolid.questionnaire')) + '?' + params;
        });

        $('#biolidArchiveBtn').on('click', function () {
            var ids = biolidFilteredIds();
            if (!ids.length) {
                alert('Нет заявок для архива по текущим фильтрам');
                return;
            }

            var params = ids.map(function (id) {
                return 'ids[]=' + encodeURIComponent(id);
            }).join('&');

            @if($panel === 'agent')
            var studygroup = $('#studygroup').val();
            if (studygroup) {
                params += '&studygroup=' + encodeURIComponent(studygroup);
            }
            @endif

            window.location.href = @json(route($panel . '.biolid.documents-archive')) + '?' + params;
        });

        $('#organization').on('change', function () {
            table.column(2).search(this.value, {exact: true}).draw();
        });

        $('#course').on('change', function () {
            table.column(8).search(this.value, {exact: true}).draw();
        });

        $('#responsible').on('change', function () {
            table.column(1).search(this.value, {exact: true}).draw();
        });

        $('#status').on('change', function () {
            table.column(10).search(this.value, {exact: true}).draw();
        });

        $('#docs_status').on('change', function () {
            table.column(11).search(this.value, {exact: true}).draw();
        });

        $('#arpm_status').on('change', function () {
            table.column(12).search(this.value, {exact: true}).draw();
        });

        $('#studygroup').on('change', function () {
            table.column(9).search(this.value).draw();
        });

        $('#resetTable').on('click', function () {
            table.state.clear();
            table.columns().visible(true);
            @if($panel === 'agent')
            table.columns([1, 6, 7, 10, 13]).visible(false);
            @endif
            table.search('').columns().search('').order([[0, 'desc']]).draw();
            $('#course').val(null).trigger('change');
            $('#organization').val(null).trigger('change');
            $('#responsible').val(null).trigger('change');
            $('#status').val(null).trigger('change');
            $('#docs_status').val(null).trigger('change');
            $('#arpm_status').val(null).trigger('change');
            $('#studygroup').val(null).trigger('change');
        });

        (function () {
            var $popup = null;

            function hidePopup() {
                if ($popup) {
                    $popup.remove();
                    $popup = null;
                }
            }

            $(document).on('mouseenter', '.course-short-title-hint', function () {
                hidePopup();
                var title = $(this).attr('data-full-title');
                if (!title) {
                    return;
                }

                var rect = this.getBoundingClientRect();
                $popup = $('<div class="course-short-title-popup"></div>').text(title).appendTo('body');

                var popupWidth = $popup.outerWidth();
                var popupHeight = $popup.outerHeight();
                var left = rect.left + (rect.width / 2) - (popupWidth / 2);
                var top = rect.top - popupHeight - 8;

                if (left < 8) {
                    left = 8;
                }
                if (left + popupWidth > window.innerWidth - 8) {
                    left = window.innerWidth - popupWidth - 8;
                }
                if (top < 8) {
                    top = rect.bottom + 8;
                }

                $popup.css({ left: left + 'px', top: top + 'px' });
            });

            $(document).on('mouseleave', '.course-short-title-hint', hidePopup);
            $(window).on('scroll.bioLidShortTitleHint resize.bioLidShortTitleHint', hidePopup);
        })();
    </script>
@endsection
