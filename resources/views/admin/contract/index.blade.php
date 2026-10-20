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
                                <div class="col col-md-3">
                                    Автор:
                                    <select id="author" name="author" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->full_name ?: $author->name }}">{{ $author->full_name ?: $author->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-3">
                                    Курс:
                                    <select id="course" name="course" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->title }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-6 d-flex justify-content-end align-items-end">
                                    <button id="resetTable" class="btn btn-secondary"><i class="fas fa-eraser mr-1"></i>Очистить фильтры</button>
                                </div>
                            </div>

                            <table id="contracts_table" class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Номер договора</th>
                                    <th>Шаблон</th>
                                    <th>Автор</th>
                                    <th>Курс</th>
                                    <th>Дата подписания</th>
                                    <th>Дата окончания</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contracts as $contract)
                                    <tr>
                                        <td>{{ $contract->id }}</td>
                                        <td>{{ $contract->number }}</td>
                                        <td>{{ $contract->template_title }}</td>
                                        <td>{{ $contract->author->full_name ?: $contract->author->name }}</td>
                                        <td>{{ $contract->course->title ?? '—' }}</td>
                                        <td>{{ $contract->signed_at ? $contract->signed_at->format('d.m.Y') : '—' }}</td>
                                        <td>{{ $contract->ends_at ? $contract->ends_at->format('d.m.Y') : '—' }}</td>
                                        <td>{{ $contract->created_at }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('admin.contract.show', $contract->id) }}" title="Просмотр"><i class="far fa-eye"></i></a>&nbsp;&nbsp;
                                                <a href="{{ route('admin.contract.edit', $contract->id) }}" class="text-success" title="Редактировать"><i class="fas fa-pen"></i></a>&nbsp;&nbsp;
                                                <a href="{{ route('admin.contract.generate', $contract->id) }}" class="text-danger" title="Скачать PDF"><i class="fas fa-file-pdf"></i></a>&nbsp;&nbsp;
                                                <form method="post" action="{{ route('admin.contract.destroy', $contract->id) }}" class="mb-0">
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
    </style>
    <script>
        let stateSaveTimer;
        var table = new DataTable('#contracts_table', {

            stateSave: true,

            stateSaveParams: function (settings, data) {
                data.custom_filters = {
                    author: $('#author').val(),
                    course: $('#course').val(),
                };
            },

            stateLoadParams: function (settings, data) {
                if (!data || !data.custom_filters) {
                    return;
                }

                $('#author').val(data.custom_filters.author || '');
                $('#course').val(data.custom_filters.course || '');

                if (data.columns && data.columns[3] && data.columns[3].search) {
                    data.columns[3].search.search = data.custom_filters.author || '';
                }
                if (data.columns && data.columns[4] && data.columns[4].search) {
                    data.columns[4].search.search = data.custom_filters.course || '';
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
                            page_url: "admin.contract.index",
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
                    data: { page_url: "admin.contract.index" },
                    dataType: "json",
                    success: function(json) {
                        callback(json);
                    }
                });
            },

            order: [[0, 'desc']],

            columnDefs: [
                { orderable: false, searchable: false, targets: 8 }
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

        $('#author').on('change', function () {
            table
                .column(3)
                .search(this.value, {exact: true})
                .draw();
        });

        $('#course').on('change', function () {
            table
                .column(4)
                .search(this.value, {exact: true})
                .draw();
        });

        $('#resetTable').on('click', function() {
            table.state.clear();

            table.columns().visible(true);

            table
                .search('')
                .columns().search('')
                .column('0:visible')
                .order('desc')
                .page.len(10)
                .page(0)
                .draw();

            $('#author').val(null).trigger('change');
            $('#course').val(null).trigger('change');
        });
    </script>
@endsection
