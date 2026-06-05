@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Пользователи</h1>
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
                    <a href="{{route('admin.user.create')}}" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">

                            <div class="container-fluid">
                                <div class="row pb-2">
                                    <div class="col col-md-2">
                                        Роль:
                                        <select id="role" name="role" class="form-control form-control-sm custom-filters">
                                            <option></option>
                                            <option value="Администратор">Администратор</option>
                                            <option value="Агент">Агент</option>
                                            <option value="Специалист КЦ">Специалист КЦ</option>
                                            <option value="Пользователь">Пользователь</option>
                                        </select>
                                    </div>
                                    <div class="col col-md-10 d-flex justify-content-end align-items-end">
                                        <button id="resetTable" class="btn btn-secondary">Очистить фильтры</button>
                                    </div>

                                </div>

                            </div>

                            <table id="users_table" class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Фамилия</th>
                                    <th>Имя</th>
                                    <th>Отчество</th>
                                    <th>Email</th>
                                    <th>Роль</th>
                                    <th>Компания</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->lastname}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->middlename}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            @foreach($roles as $id => $role)
                                                {{ $id == $user->role ? $role : '' }}
                                            @endforeach
                                        </td>
                                        <td>
                                            @if(!empty($user->company))
                                                {{$user->company->title}}
                                            @endif
                                        </td>
                                        <td>{{$user->created_at}}</td>
                                        <td class="d-flex"><a  href="{{route('admin.user.show', $user->id)}}"><i class="far fa-eye"></i></a>&nbsp;&nbsp;
                                        <a  href="{{route('admin.user.edit', $user->id)}}" class="text-success"><i class="fas fa-pen"></i></a>&nbsp;&nbsp;

                                            <form method="post" action="{{route('admin.user.delete', $user->id)}}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-transparent border-0" type="submit"><i class="fas fa-trash text-danger" role="button"></i></button>
                                            </form>
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
    <script>
        let stateSaveTimer; // Переменная для хранения таймера
        var table = new DataTable('#users_table', {

            stateSave: true,

            stateSaveParams: function (settings, data) {
                data.custom_filters = {
                    role: $('#role').val(),
                };
            },

            stateLoadParams: function (settings, data) {
                if (data) {
                    $('#role').val(data.custom_filters.role).trigger('change');
                }
            },

            stateSaveCallback: function(settings, data) {
                // Очищаем предыдущий таймер, если пользователь нажал что-то еще
                clearTimeout(stateSaveTimer);

                // Устанавливаем задержку 2 секунды (2000 мс)
                stateSaveTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('filters.save') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            page_url: "admin.user.index",
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
                    data: { page_url: "admin.user.index" },
                    dataType: "json",
                    success: function(json) {
                        // Передаем данные обратно в DataTables
                        callback(json);
                    }
                });
            },

            order: [[0, 'desc']],

            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "colvis"],

            initComplete: function () {
                this.api()
                    .buttons()
                    .container()
                    .appendTo('#order_table_wrapper .col-md-6:eq(0)');
            },

            "language": {
                info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                paginate: {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                search: "Поиск:",
                buttons: {
                    colvis: 'Выбрать колонки',
                    search: 'Поиск'
                },
            }
        });

        $('#role').on('change', function (e) {
            table
                .column(5)
                .search(this.value, {exact: true})
                .draw();
        });


        $('#resetTable').on('click', function() {
            // Очищаем сохраненное состояние в localStorage
            table.state.clear();

            table.columns().visible(true);

            // Сбрасываем визуальные и поисковые параметры
            table
                .search('')            // Очищаем общий поиск
                .columns().search('')  // Очищаем поиск в каждой колонке (если есть)
                .column('0:visible')   // Выбираем первую видимую колонку
                .order('desc')     // Устанавливаем дефолтную сортировку
                .page.len(10)          // Возвращаем количество строк на страницу по умолчанию
                .page(0)               // Переходим на первую страницу
                .draw();               // Применяем изменения и перерисовываем таблицу


            $('#role').val(null).trigger('change');

        });

    </script>
@endsection
