@extends('cc.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заказы</h1>
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
                    <a href="{{route('admin.order.create')}}" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="container-fluid">
                                <div class="row pb-2">
                                    <div class="col col-md-2">
                                        Статус:
                                        <select id="status" name="status" class="form-control form-control-sm custom-filters">
                                            <option></option>
                                            @foreach($statuses as $status)
                                                <option value="{{$status->title}}">{{$status->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col col-md-3">
                                        Курс:
                                        <select id="course" name="course" class="form-control form-control-sm select2">
                                            <option></option>
                                            @foreach($courses as $course)
                                                <option
                                                    value="{{$course->title}}">{{mb_substr($course->title, 0, 70)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col col-md-7 d-flex justify-content-end align-items-end">
                                        <button id="resetTable" class="btn btn-secondary">Очистить фильтры</button>
                                    </div>

                                </div>

                            </div>

                            <table id="order_table" class="table table-bordered table-striped hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Курс</th>
                                    <th>ФИО</th>
                                    <th>Регион</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{$order->id}}</td>
                                        <td>{{$order->course->title}}</td>
                                        <td>{{$order->user->lastname}} {{$order->user->name}} {{$order->user->middlename}}</td>
                                        <td>
                                            @if(isset($order->region))
                                                {{$order->region->title}}
                                            @endif
                                        </td>
                                        <td>{{$order->user->email}}</td>
                                        <td>
                                            @if(isset($order->user->phone))
                                                8{{$order->user->phone}}
                                            @endif
                                        </td>
                                        <td>{{$order->amount}}</td>
                                        <td>{{$order->status->title}}</td>
                                        <td>{{$order->created_at}}</td>
                                        <td>
                                            <a  href="{{route('cc.order.show', $order->id)}}"><i class="far fa-eye"></i></a>&nbsp; &nbsp;
                                            <a  href="{{route('cc.order.edit', $order->id)}}" class="text-success"><i class="fas fa-pen"></i></a>
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
        var table = new DataTable('#order_table', {

            stateSave: true,

            stateSaveParams: function (settings, data) {
                data.custom_filters = {
                    course: $('#course').val(),
                    status: $('#status').val(),
                };
            },

            stateLoadParams: function (settings, data) {
                if (data) {
                    $('#course').val(data.custom_filters.course).trigger('change');
                    $('#status').val(data.custom_filters.status);
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
                            page_url: "cc.lid.index",
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
                    data: { page_url: "cc.lid.index" },
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
 //       table.buttons().container().appendTo('#order_table_wrapper .col-md-6:eq(0)');

        $('#status').on('change', function (e) {
            table
                .column(7)
                .search(this.value, {exact: true})
                .draw();
        });

        $('#course').on('change', function (e) {
            table
                .column(1)
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


            $('.custom-filters').not('#course').val('');
            $('#course').val(null).trigger('change');

        });

    </script>
@endsection
