@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Договоры</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row mb-3">
                <div class="col">
                    <a href="{{ route('admin.contract.create') }}" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row pb-3">
                                <div class="col col-md-2">
                                    Статус:
                                    <select id="status" name="status" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach(\App\Models\ContractWorkflowStatus::LABELS as $statusLabel)
                                            <option value="{{ $statusLabel }}">{{ $statusLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    Проект:
                                    <select id="project" name="project" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->short_title }}">{{ $project->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    Автор:
                                    <select id="author" name="author" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->name }}">{{ $author->name }}</option>
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
                                <div class="col col-md-3 d-flex justify-content-end align-items-end">
                                    <button id="resetTable" class="btn btn-secondary"><i class="fas fa-eraser mr-1"></i>Очистить фильтры</button>
                                </div>
                            </div>

                            <table id="contracts_table" class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Номер договора</th>
                                    <th>Дата подписания</th>
                                    <th>Проект</th>
                                    <th>Автор</th>
                                    <th>Курс</th>
                                    <th>Статус договора</th>
                                    <th>Статус задания</th>
                                    <th>Статус отчёта</th>
                                    <th>Статус акта</th>
                                    <th>Дата окончания</th>
                                    <th>Дата создания</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contracts as $contract)
                                    <tr>
                                        <td>{{ $contract->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.contract.show', $contract->id) }}">{{ $contract->number }}</a>
                                        </td>
                                        <td>{{ $contract->signed_at ? $contract->signed_at->format('d.m.Y') : '—' }}</td>
                                        <td>{{ $contract->project->short_title ?? '—' }}</td>
                                        <td>{{ $contract->author->name }}</td>
                                        <td>
                                            @if($contract->course?->short_title)
                                                <span class="d-inline-flex align-items-center">
                                                    {{ $contract->course->short_title }}
                                                    <span
                                                        class="course-short-title-hint ml-1"
                                                        data-full-title="{{ $contract->course->title }}"
                                                        aria-label="{{ $contract->course->title }}"
                                                    >
                                                        <i class="fas fa-question-circle text-muted" aria-hidden="true"></i>
                                                    </span>
                                                </span>
                                            @else
                                                {{ $contract->course->title ?? '—' }}
                                            @endif
                                        </td>
                                        <td>
                                            @include('admin.contract._status_badge', ['status' => $contract->status])
                                        </td>
                                        <td>
                                            @forelse($contract->assignments as $assignment)
                                                <div class="mb-1">
                                                    <span class="text-muted">№{{ $assignment->number }}:</span>
                                                    @include('admin.contract._status_badge', ['status' => $assignment->status])
                                                </div>
                                            @empty
                                                —
                                            @endforelse
                                        </td>
                                        <td>
                                            @forelse($contract->reports as $report)
                                                <div class="mb-1">
                                                    <span class="text-muted">№{{ $report->number }}:</span>
                                                    @include('admin.contract._status_badge', ['status' => $report->status])
                                                </div>
                                            @empty
                                                —
                                            @endforelse
                                        </td>
                                        <td>
                                            @forelse($contract->acts as $act)
                                                <div class="mb-1">
                                                    <span class="text-muted">№{{ $act->number }}:</span>
                                                    @include('admin.contract._status_badge', ['status' => $act->status])
                                                </div>
                                            @empty
                                                —
                                            @endforelse
                                        </td>
                                        <td>{{ $contract->ends_at ? $contract->ends_at->format('d.m.Y') : '—' }}</td>
                                        <td>{{ $contract->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>


@endsection

@section('javascript')
    <style>
        #contracts_table td {
            vertical-align: middle;
        }
        #contracts_table td:nth-child(8),
        #contracts_table td:nth-child(9),
        #contracts_table td:nth-child(10) {
            white-space: normal !important;
            min-width: 10rem;
        }
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
    </style>
    <script>
        let stateSaveTimer;
        var table = new DataTable('#contracts_table', {

            stateSave: true,
            stateDuration: 0,

            stateSaveParams: function (settings, data) {
                data.custom_filters = {
                    status: $('#status').val(),
                    project: $('#project').val(),
                    author: $('#author').val(),
                    course: $('#course').val(),
                };
            },

            stateLoadParams: function (settings, data) {
                if (!data || !data.custom_filters) {
                    return;
                }

                $('#status').val(data.custom_filters.status || '');
                $('#project').val(data.custom_filters.project || '');
                $('#author').val(data.custom_filters.author || '');
                $('#course').val(data.custom_filters.course || '');

                if (data.columns && data.columns[3] && data.columns[3].search) {
                    data.columns[3].search.search = data.custom_filters.project || '';
                }
                if (data.columns && data.columns[4] && data.columns[4].search) {
                    data.columns[4].search.search = data.custom_filters.author || '';
                }
                if (data.columns && data.columns[5] && data.columns[5].search) {
                    data.columns[5].search.search = data.custom_filters.course || '';
                }
                if (data.columns && data.columns[6] && data.columns[6].search) {
                    data.columns[6].search.search = data.custom_filters.status || '';
                }
            },

            stateSaveCallback: function(settings, data) {
                clearTimeout(stateSaveTimer);

                stateSaveTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('filters.save') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            page_url: "admin.contract.index.v8",
                            state: JSON.stringify(data)
                        },
                        success: function() {
                            console.log("Состояние сохранено в БД");
                        }
                    });
                }, 2000);
            },

            stateLoadCallback: function(settings, callback) {
                $.ajax({
                    url: "{{ route('filters.get') }}",
                    data: { page_url: "admin.contract.index.v8" },
                    dataType: "json",
                    success: function(json) {
                        callback(json);
                    }
                });
            },

            order: [[0, 'desc']],

            columnDefs: [
                { visible: false, targets: [3, 10, 11] }
            ],

            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": "<'row mb-3'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6 text-md-right'B>>" +
                "<'row'<'col-sm-12'<'table-responsive'tr>>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [
                "excel",
                {
                    extend: "colvis",
                    align: "button-right"
                }
            ],

            "language": {
                info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                infoFiltered: "(отфильтровано из _MAX_ записей)",
                paginate: {
                    "first": "Первая",
                    "previous": "<<",
                    "next": ">>",
                    "last": "Последняя"
                },
                search: "Поиск:",
                buttons: {
                    colvis: 'Выбрать колонки',
                    search: 'Поиск'
                },
                emptyTable: "Пока нет сохранённых договоров",
                zeroRecords: "Ничего не найдено"
            }
        });

        $('#project').on('change', function () {
            table
                .column(3)
                .search(this.value, {exact: true})
                .draw();
        });

        $('#author').on('change', function () {
            table
                .column(4)
                .search(this.value, {exact: true})
                .draw();
        });

        $('#course').on('change', function () {
            table
                .column(5)
                .search(this.value, {exact: true})
                .draw();
        });

        $('#status').on('change', function () {
            table
                .column(6)
                .search(this.value, {exact: true})
                .draw();
        });

        $('#resetTable').on('click', function() {
            table.state.clear();

            table.columns().visible(true);
            table.columns([3, 10, 11]).visible(false);

            table
                .search('')
                .columns().search('')
                .column('0:visible')
                .order('desc')
                .page.len(10)
                .page(0)
                .draw();

            $('#status').val(null).trigger('change');
            $('#project').val(null).trigger('change');
            $('#author').val(null).trigger('change');
            $('#course').val(null).trigger('change');
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
            $(window).on('scroll.courseShortTitleHint resize.courseShortTitleHint', hidePopup);
        })();
    </script>
@endsection
