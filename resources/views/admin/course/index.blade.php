@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Каталог курсов</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

            <div class="row mb-3">
                <div class="col">
                    <a href="{{route('admin.course.create')}}" type="button" class="btn btn-primary"><i
                            class="fa fa-plus-circle"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row pb-2">
                                <div class="col col-lg-1">
                                    Год:
                                    <select id="year" name="year" class="form-control form-control-sm">
                                        @include('partials.year-options', [
                                            'yearOptions' => $years,
                                            'includeEmpty' => true,
                                        ])
                                    </select>
                                </div>
                                <div class="col col-lg-2">
                                    Автор:
                                    <select id="author" name="author" class="form-control form-control-sm">
                                        <option></option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->display_name }}">{{ $author->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-lg-2">
                                    Публикация:
                                    <select id="is_published" name="is_published" class="form-control form-control-sm">
                                        <option></option>
                                        <option value="Архив">Архив</option>
                                        <option value="Опубликован">Опубликован</option>
                                    </select>
                                </div>
                                <div class="col col-lg-2">
                                    Тип:
                                    <select id="course_type" name="course_type" class="form-control form-control-sm">
                                        <option></option>
                                        <option value="1">Платный</option>
                                        <option value="0">Бесплатный</option>
                                    </select>
                                </div>
                                <div class="col col-lg-2">
                                    Источник:
                                    <select id="source" name="source" class="form-control form-control-sm">
                                        <option></option>
                                        <option value="Биоэкономика">Биоэкономика</option>
                                    </select>
                                </div>
                                <div class="col d-flex justify-content-end align-items-end">
                                    <button id="resetTable" type="button" class="btn btn-secondary"><i class="fas fa-eraser mr-1"></i>Очистить фильтры</button>
                                </div>

                            </div>

                            <div class="col pt-3">
                                <table id="course_table" class="table table-bordered table-striped hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Название</th>
                                        <th>Автор</th>
                                        <th>Год</th>
                                        <th>Компания</th>
                                        <th>Публикация</th>
                                        <th>Источник</th>
                                        <th>Платный</th>
                                        <th>Дата создания</th>
                                        <th>Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($courses as $course)
                                        <tr>
                                            <td>{{$course->id}}</td>
                                            <td>{{$course->title}}</td>
                                            <td>{{ $course->authors->map->display_name->filter()->implode(', ') }}</td>
                                            <td>{{$course->years}}</td>
                                            <td>
                                                @if(!empty($course->company))
                                                    {{$course->company->title}}
                                                @else
                                                    Основание
                                                @endif
                                            </td>
                                            <td>
                                                {{$course->is_published == 1 ? 'Опубликован' : 'Архив' }}
                                            </td>
                                            <td>
                                                {{ $course->bioeconomy ? 'Биоэкономика' : '—' }}
                                            </td>
                                            <td>
                                                {{isset($course->price) ? '1' : '0' }}
                                            </td>
                                            <td>{{$course->created_at}}</td>
                                            <td>
                                                <a href="{{route('admin.course.show', $course->id)}}"><i
                                                        class="far fa-eye"></i></a>
                                                <a href="{{route('admin.course.edit', $course->id)}}"
                                                   class="text-success"><i class="fas fa-pen"></i></a>

                                                <form method="post"
                                                      action="{{route('admin.course.destroy', $course->id)}}"
                                                      class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="bg-transparent border-0" type="submit"><i
                                                            class="fas fa-trash text-danger" role="button"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>

    @section('javascript')
        <script>
            $(document).ready(function () {
                let stateSaveTimer;
                var table = new DataTable('#course_table', {
                    stateSave: true,
                    stateDuration: 0,

                    stateSaveParams: function (settings, data) {
                        data.custom_filters = {
                            year: $('#year').val(),
                            author: $('#author').val(),
                            is_published: $('#is_published').val(),
                            course_type: $('#course_type').val(),
                            source: $('#source').val(),
                        };
                    },

                    stateLoadParams: function (settings, data) {
                        if (!data || !data.custom_filters) {
                            return;
                        }

                        var filters = data.custom_filters;

                        $('#year').val(filters.year);
                        $('#author').val(filters.author);
                        $('#is_published').val(filters.is_published);
                        $('#course_type').val(filters.course_type);
                        $('#source').val(filters.source);

                        if (!data.columns) {
                            return;
                        }

                        var columnFilters = {
                            2: filters.author,
                            3: filters.year,
                            5: filters.is_published,
                            6: filters.source,
                            7: filters.course_type
                        };

                        if (data.columns[4] && data.columns[4].search) {
                            data.columns[4].search.search = '';
                        }

                        $.each(columnFilters, function (index, value) {
                            if (!data.columns[index] || !data.columns[index].search) {
                                return;
                            }

                            data.columns[index].search.search = value || '';
                        });
                    },

                    stateSaveCallback: function (settings, data) {
                        clearTimeout(stateSaveTimer);

                        stateSaveTimer = setTimeout(function () {
                            $.ajax({
                                url: "{{ route('filters.save') }}",
                                method: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    page_url: "admin.course.index",
                                    state: JSON.stringify(data)
                                },
                                success: function () {
                                    console.log("Состояние сохранено в БД");
                                }
                            });
                        }, 2000);
                    },

                    stateLoadCallback: function (settings, callback) {
                        $.ajax({
                            url: "{{ route('filters.get') }}",
                            data: { page_url: "admin.course.index" },
                            dataType: "json",
                            success: function (json) {
                                callback(json);
                            }
                        });
                    },

                    order: [[0, 'desc']],
                    paging: false,
                    columns: [
                        { data: 'id' },
                        { data: 'title' },
                        { data: 'author' },
                        { data: 'years' },
                        { data: 'company' },
                        { data: 'is_publshed' },
                        { data: 'source' },
                        { data: 'course_type' },
                        { data: 'created_at' },
                        { data: 'actions' },
                    ],
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "dom": "<'row mb-3'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6 text-md-right'B>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    "buttons": ["excel", "colvis"],
                    'columnDefs': [
                        { targets: [4, 7], visible: false }
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
                    }
                });

                $('#author').on('change', function (e) {

                    table
                        .column(2)
                        .search(this.value)
                        .draw();
                })

                $('#year').on('change', function (e) {

                    table
                        .column(3)
                        .search(this.value, {exact: true})
                        .draw();
                })

                $('#is_published').on('change', function (e) {

                    table
                        .column(5)
                        .search(this.value, {exact: true})
                        .draw();
                })

                $('#course_type').on('change', function (e) {

                    table
                        .column(7)
                        .search(this.value, {exact: true, })
                        .draw();
                })

                $('#source').on('change', function (e) {

                    table
                        .column(6)
                        .search(this.value, {exact: true})
                        .draw();
                })

                $('#resetTable').on('click', function () {
                    table.state.clear();

                    table.columns().visible(true);
                    table.columns([4, 7]).visible(false);

                    table
                        .search('')
                        .columns().search('')
                        .column('0:visible')
                        .order('desc')
                        .page.len(10)
                        .page(0)
                        .draw();

                    $('#year, #author, #is_published, #course_type, #source').val(null).trigger('change');
                });

            });
        </script>
    @endsection

@endsection
