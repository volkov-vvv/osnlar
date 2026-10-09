@extends($panel . '.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заявки организаций</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row pb-2">
                                <div class="col col-md-2">
                                    Дата:
                                    <input id="date" type="date" class="form-control form-control-sm custom-filters">
                                </div>
                                <div class="col col-md-2">
                                    Источник:
                                    <select id="source" name="source" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        <option value="Биоэкономика">Биоэкономика</option>
                                    </select>
                                </div>
                                <div class="col col-md-8 d-flex justify-content-end align-items-end">
                                    <button id="resetTable" class="btn btn-secondary">Очистить фильтры</button>
                                </div>
                            </div>
                            <div class="row pb-4">
                                <div class="col-12 col-lg-2">
                                    Ответственный:
                                    <select id="responsible" name="responsible" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-5">
                                    Курс:
                                    <select id="course" name="course" class="form-control form-control-sm select2 custom-filters">
                                        <option></option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->title }}">{{ mb_substr($course->title, 0, 70) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-2">
                                    Статус:
                                    <select id="status" name="status" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->title }}">{{ $status->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <table id="org_table" class="table table-bordered table-striped hover">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Ответственный</th>
                                    <th>Краткое название</th>
                                    <th>ИНН</th>
                                    <th>Курсы</th>
                                    <th>Фамилия</th>
                                    <th>Имя</th>
                                    <th>Отчество</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Источник</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Дата обновления</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orgs as $org)
                                    @php
                                        $statusTitle = optional($statuses->firstWhere('id', $org->status_id))->title ?? '';
                                        $sourceTitle = $org->source === 'bioeconomy' ? 'Биоэкономика' : ($org->source ?: '—');
                                        $coursesTitle = $org->courses->isNotEmpty()
                                            ? $org->courses->pluck('title')->implode('; ')
                                            : optional($courses->firstWhere('id', $org->course_id))->title ?? '';
                                    @endphp
                                    <tr>
                                        <td>{{ $org->id }}</td>
                                        <td>{{ $org->responsible->name ?? '' }}</td>
                                        <td>{{ $org->organization_title }}</td>
                                        <td>{{ $org->inn ?? '—' }}</td>
                                        <td>{{ $coursesTitle }}</td>
                                        <td>{{ $org->lastname }}</td>
                                        <td>{{ $org->firstname }}</td>
                                        <td>{{ $org->middlename ?: '—' }}</td>
                                        <td>{{ $org->email ?: '—' }}</td>
                                        <td>
                                            @if($org->phone)
                                                {{ $org->phone_prefix == '7' ? '8'.$org->phone : ($org->phone_prefix.$org->phone) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $sourceTitle }}</td>
                                        <td>
                                            <span class="badge rounded-pill
                                                @switch($org->status_id)
                                                    @case(1) bg-danger @break
                                                    @case(2) bg-warning text-dark @break
                                                    @case(3) bg-info @break
                                                    @case(4) bg-success @break
                                                @endswitch">
                                                {{ $statusTitle }}
                                            </span>
                                        </td>
                                        <td>{{ optional($org->created_at)->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ optional($org->updated_at)->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route($panel . '.org.show', $org->id) }}"><i class="far fa-eye"></i></a>
                                            &nbsp;&nbsp;
                                            <a href="{{ route($panel . '.org.edit', $org->id) }}" class="text-success"><i class="fas fa-pen"></i></a>
                                            &nbsp;&nbsp;
                                            <form method="post" action="{{ route($panel . '.org.destroy', $org->id) }}" class="d-inline" onsubmit="return confirm('Удалить заявку организации?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-transparent border-0 p-0" type="submit"><i class="fas fa-trash text-danger" role="button"></i></button>
                                            </form>
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
        let stateSaveTimer;
        var table = new DataTable('#org_table', {
            stateSave: true,

            stateSaveParams: function (settings, data) {
                data.custom_filters = {
                    date: $('#date').val(),
                    responsible: $('#responsible').val(),
                    course: $('#course').val(),
                    status: $('#status').val(),
                    source: $('#source').val(),
                };
            },

            stateLoadParams: function (settings, data) {
                if (data && data.custom_filters) {
                    $('#date').val(data.custom_filters.date || '');
                    $('#responsible').val(data.custom_filters.responsible || '');
                    $('#course').val(data.custom_filters.course || null).trigger('change');
                    $('#status').val(data.custom_filters.status || '');
                    $('#source').val(data.custom_filters.source || '');
                }
            },

            stateSaveCallback: function (settings, data) {
                clearTimeout(stateSaveTimer);
                stateSaveTimer = setTimeout(function () {
                    $.ajax({
                        url: "{{ route('filters.save') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            page_url: "{{ $panel }}.org.index",
                            state: JSON.stringify(data)
                        }
                    });
                }, 2000);
            },

            stateLoadCallback: function (settings, callback) {
                $.ajax({
                    url: "{{ route('filters.get') }}",
                    data: { page_url: "{{ $panel }}.org.index" },
                    dataType: "json",
                    success: function (json) {
                        callback(json);
                    }
                });
            },

            order: [[0, 'desc']],
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            dom: "<'row mb-3'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6 text-md-right'B>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: ["excel", "colvis"],
            columnDefs: [
                {
                    targets: [4, 8],
                    visible: false
                },
                {
                    targets: [14],
                    orderable: false
                }
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
                    colvis: 'Выбрать колонки',
                    search: 'Поиск'
                }
            }
        });

        $('#responsible').on('change', function () {
            table.column(1).search(this.value, {exact: true}).draw();
        });

        $('#course').on('change', function () {
            table.column(4).search(this.value).draw();
        });

        $('#source').on('change', function () {
            table.column(10).search(this.value, {exact: true}).draw();
        });

        $('#status').on('change', function () {
            table.column(11).search(this.value).draw();
        });

        $('#date').on('change', function () {
            table.column(12).search(this.value).draw();
        });

        $('#resetTable').on('click', function () {
            table.state.clear();
            table.columns().visible(true);
            table.columns([4, 8]).visible(false);
            table
                .search('')
                .columns().search('')
                .column('0:visible')
                .order('desc')
                .page.len(10)
                .page(0)
                .draw();

            $('.custom-filters').not('#course').val('');
            $('#course').val(null).trigger('change');
        });
    </script>
@endsection
