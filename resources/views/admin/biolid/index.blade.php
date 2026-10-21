@extends('admin.layouts.main')
@section('content')
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
                                <div class="col col-md-4">
                                    Организация:
                                    <select id="organization" name="organization" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($organizations as $organization)
                                            <option value="{{ $organization }}">{{ $organization }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-4">
                                    Курс:
                                    <select id="course" name="course" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->title }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-4 d-flex justify-content-end align-items-end">
                                    <button id="resetTable" class="btn btn-secondary"><i class="fas fa-eraser mr-1"></i>Очистить фильтры</button>
                                </div>
                            </div>

                            <table id="biolids_table" class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Организация</th>
                                    <th>Фамилия</th>
                                    <th>Имя</th>
                                    <th>Отчество</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Курс</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bioLids as $bioLid)
                                    <tr>
                                        <td>{{ $bioLid->id }}</td>
                                        <td>{{ $bioLid->organization_title ?: '—' }}</td>
                                        <td>{{ $bioLid->lastname }}</td>
                                        <td>{{ $bioLid->firstname }}</td>
                                        <td>{{ $bioLid->middlename ?: '—' }}</td>
                                        <td>{{ $bioLid->email }}</td>
                                        <td>{{ $bioLid->phone }}</td>
                                        <td>{{ $bioLid->course->title ?? '—' }}</td>
                                        <td>{{ $bioLid->created_at }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('admin.biolid.show', $bioLid->id) }}" title="Просмотр"><i class="far fa-eye"></i></a>&nbsp;&nbsp;
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
                { orderable: false, searchable: false, targets: 9 }
            ],
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            dom: "<'row mb-3'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6 text-md-right'B>>" +
                "<'row'<'col-sm-12'<'table-responsive'tr>>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: ["excel", { extend: "colvis", align: "button-right" }],
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
            table.column(1).search(this.value, {exact: true}).draw();
        });

        $('#course').on('change', function () {
            table.column(7).search(this.value, {exact: true}).draw();
        });

        $('#resetTable').on('click', function () {
            table.search('').columns().search('').order([[0, 'desc']]).draw();
            $('#course').val(null).trigger('change');
            $('#organization').val(null).trigger('change');
        });
    </script>
@endsection
