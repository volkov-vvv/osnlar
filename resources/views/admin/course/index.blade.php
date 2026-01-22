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
                                        <option></option>
                                        <option value="2026">2026</option>
                                        <option value="2025">2025</option>
                                        <option value="2024">2024</option>
                                    </select>
                                </div>
                                <div class="col col-lg-2">
                                    Компания:
                                    <select id="company" name="company" class="form-control form-control-sm">
                                        <option></option>
                                        @foreach($companies as $company)
                                            <option value="{{$company->title}}">{{$company->title}}</option>
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

                            </div>

                            <div class="col pt-3">
                                <table id="course_table" class="table table-bordered table-striped hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Название</th>
                                        <th>Год</th>
                                        <th>Компания</th>
                                        <th>Публикация</th>
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
                                                {{isset($course->price) ? '1' : '0' }}
                                            </td>
                                            <td>{{$course->created_at}}</td>
                                            <td>
                                                <a href="{{route('admin.course.show', $course->id)}}"><i
                                                        class="far fa-eye"></i></a>
                                                <a href="{{route('admin.course.edit', $course->id)}}"
                                                   class="text-success"><i class="fas fa-pen"></i></a>

                                                <form method="post"
                                                      action="{{route('admin.course.delete', $course->id)}}"
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
                var table = new DataTable('#course_table', {
                    order: [[0, 'desc']],
                    paging: false,
                    columns: [
                        { data: 'id' },
                        { data: 'title' },
                        { data: 'years' },
                        { data: 'company' },
                        { data: 'is_publshed' },
                        { data: 'course_type' },
                        { data: 'created_at' },
                        { data: 'actions' },
                    ],
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["excel", "pdf", "colvis"],
                    'columnDefs': [
                        { targets: [5], visible: false }
                    ],
                    "language": {
                        info: "Записи с _START_ до _END_ из _TOTAL_ записей",
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

                table.buttons().container().appendTo('#course_table_wrapper .col-md-6:eq(0)');

                $('#company').on('change', function (e) {

                    table
                        .column(3)
                        .search(this.value, {exact: true})
                        .draw();
                })


                $('#year').on('change', function (e) {

                    table
                        .column(2)
                        .search(this.value, {exact: true})
                        .draw();
                })

                $('#is_published').on('change', function (e) {

                    table
                        .column(4)
                        .search(this.value, {exact: true})
                        .draw();
                })

                $('#course_type').on('change', function (e) {

                    table
                        .column(5)
                        .search(this.value, {exact: true, })
                        .draw();
                })

            });
        </script>
    @endsection

@endsection
