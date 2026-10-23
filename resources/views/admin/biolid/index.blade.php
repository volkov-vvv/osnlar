@extends('admin.layouts.main')
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

        .dt-buttons .biolid-questionnaire-btn {
            margin-right: 0.75rem;
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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
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
                                    <th>Дата создания</th>
                                    <th>Дата обновления</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bioLids as $bioLid)
                                    @php
                                        $statusTitle = $bioLid->status->title ?? '';
                                    @endphp
                                    <tr data-id="{{ $bioLid->id }}">
                                        <td>{{ $bioLid->id }}</td>
                                        <td>{{ $bioLid->responsible->name ?? '' }}</td>
                                        <td>{{ $bioLid->organization_title ?: '—' }}</td>
                                        <td>
                                            <span class="d-inline-flex align-items-center">
                                                {{ $bioLid->lastname }}
                                                @if($bioLid->user)
                                                    <a
                                                        href="{{ route('admin.user.show', $bioLid->user->id) }}"
                                                        class="ml-1 text-info"
                                                        title="Учётная запись создана"
                                                    >
                                                        <i class="fas fa-user-circle" aria-hidden="true"></i>
                                                    </a>
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
                                                <a href="{{ route('admin.studygroup.show', $studyGroup->id) }}">{{ $studyGroup->short_title ?: $studyGroup->title }}</a>@if(!$loop->last), @endif
                                            @empty
                                                —
                                            @endforelse
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill
                                                @switch($bioLid->status_id)
                                                    @case(1) bg-danger @break
                                                    @case(2) bg-warning text-dark @break
                                                    @case(3) bg-info @break
                                                    @case(4) bg-success @break
                                                @endswitch">
                                                {{ $statusTitle }}
                                            </span>
                                        </td>
                                        <td>{{ $bioLid->created_at }}</td>
                                        <td>{{ $bioLid->updated_at }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('admin.biolid.show', $bioLid->id) }}" title="Просмотр"><i class="far fa-eye"></i></a>&nbsp;&nbsp;
                                                <a href="{{ route('admin.biolid.edit', $bioLid->id) }}" title="Редактировать"><i class="fas fa-pencil-alt text-success"></i></a>&nbsp;&nbsp;
                                                <form method="post" action="{{ route('admin.biolid.destroy', $bioLid->id) }}" class="mb-0" onsubmit="return confirm('Удалить заявку?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="bg-transparent border-0" type="submit" title="Удалить"><i class="fas fa-trash text-danger" role="button"></i></button>
                                                </form>
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
@endsection

@section('javascript')
    <script>
        var table = new DataTable('#biolids_table', {
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, searchable: false, targets: 13 }
            ],
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            dom: "<'row mb-3'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6 text-md-right'B>>" +
                "<'row'<'col-sm-12'<'table-responsive'tr>>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    text: 'Сформировать анкету для группы',
                    className: 'btn-success biolid-questionnaire-btn',
                    action: function (e, dt) {
                        var ids = [];
                        dt.rows({ search: 'applied' }).every(function () {
                            var id = $(this.node()).data('id');
                            if (id) {
                                ids.push(id);
                            }
                        });

                        if (!ids.length) {
                            alert('Нет заявок для выгрузки по текущим фильтрам');
                            return;
                        }

                        var params = ids.map(function (id) {
                            return 'ids[]=' + encodeURIComponent(id);
                        }).join('&');

                        window.location.href = @json(route('admin.biolid.questionnaire')) + '?' + params;
                    }
                },
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

        $('#studygroup').on('change', function () {
            table.column(9).search(this.value).draw();
        });

        $('#resetTable').on('click', function () {
            table.search('').columns().search('').order([[0, 'desc']]).draw();
            $('#course').val(null).trigger('change');
            $('#organization').val(null).trigger('change');
            $('#responsible').val(null).trigger('change');
            $('#status').val(null).trigger('change');
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
