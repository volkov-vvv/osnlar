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

            <div id="user-action-alerts"></div>

            <div class="row mb-3">
                <div class="col">
                    <a href="{{route('admin.user.create')}}" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row pb-3">
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
                                    <button id="resetTable" class="btn btn-secondary"><i class="fas fa-eraser mr-1"></i>Очистить фильтры</button>
                                </div>
                            </div>

                            <table id="users_table" class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Аватар</th>
                                    <th>Фамилия</th>
                                    <th>Имя</th>
                                    <th>Отчество</th>
                                    <th>Email</th>
                                    <th>Роль</th>
                                    <th>Учебные группы</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr @if($user->is_blocked) class="text-muted" @endif>
                                        <td>{{$user->id}}</td>
                                        <td>
                                            @if($user->avatar)
                                                <img
                                                    src="{{ url('storage/' . $user->avatar) }}"
                                                    alt=""
                                                    class="img-circle elevation-1"
                                                    width="36"
                                                    height="36"
                                                    style="object-fit: cover;"
                                                >
                                            @else
                                                <span
                                                    class="d-inline-block rounded-circle"
                                                    style="width: 36px; height: 36px; background-color: #d6d8db;"
                                                ></span>
                                            @endif
                                        </td>
                                        <td>{{$user->lastname}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->middlename}}</td>
                                        <td>
                                            <span class="user-email">{{$user->email}}</span>
                                            <span class="badge badge-secondary ml-1 user-blocked-badge" @if(!$user->is_blocked) style="display: none;" @endif>Заблокирован</span>
                                        </td>
                                        <td>
                                            @foreach($roles as $id => $role)
                                                {{ $id == $user->role ? $role : '' }}
                                            @endforeach
                                        </td>
                                        <td>
                                            @forelse($user->studyGroups as $studyGroup)
                                                <a href="{{ route('admin.studygroup.show', $studyGroup->id) }}">{{ $studyGroup->short_title ?: $studyGroup->title }}</a>@if(!$loop->last), @endif
                                            @empty
                                                —
                                            @endforelse
                                        </td>
                                        <td>{{$user->created_at}}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{route('admin.user.show', $user->id)}}" title="Просмотр"><i class="far fa-eye"></i></a>&nbsp;&nbsp;
                                                <a href="{{route('admin.user.edit', $user->id)}}" class="text-success" title="Редактировать"><i class="fas fa-pen"></i></a>&nbsp;&nbsp;
                                                @if($user->id !== auth()->id())
                                                    <button
                                                        type="button"
                                                        class="bg-transparent border-0 p-0 user-block-btn"
                                                        data-url="{{ route('admin.user.block', $user->id) }}"
                                                        data-blocked="{{ $user->is_blocked ? 1 : 0 }}"
                                                        title="{{ $user->is_blocked ? 'Разблокировать' : 'Заблокировать' }}"
                                                    >
                                                        <i class="fas {{ $user->is_blocked ? 'fa-unlock text-success' : 'fa-ban text-warning' }}" role="button"></i>
                                                    </button>&nbsp;&nbsp;
                                                @endif
                                                <form method="post" action="{{route('admin.user.destroy', $user->id)}}" class="mb-0">
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
        #users_table td {
            vertical-align: middle;
        }

        #users_table img,
        #users_table .rounded-circle {
            vertical-align: middle;
        }
    </style>
    <script>
        let stateSaveTimer; // Переменная для хранения таймера
        var table = new DataTable('#users_table', {

            stateSave: true,
            stateDuration: 0,

            stateSaveParams: function (settings, data) {
                data.custom_filters = {
                    role: $('#role').val(),
                };
            },

            stateLoadParams: function (settings, data) {
                if (!data || !data.custom_filters) {
                    return;
                }

                $('#role').val(data.custom_filters.role);

                if (data.columns && data.columns[6] && data.columns[6].search) {
                    data.columns[6].search.search = data.custom_filters.role || '';
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

            columnDefs: [
                { orderable: false, searchable: false, targets: 1 }
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
            }
        });

        $('#role').on('change', function (e) {
            table
                .column(6)
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

        function showUserActionAlert(message, type) {
            type = type || 'success';
            var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            var $alert = $(
                '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                    $('<div>').text(message).html() +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                    '</button>' +
                '</div>'
            );

            $('#user-action-alerts').html($alert);
        }

        $(document).on('click', '.user-block-btn', function () {
            var $btn = $(this);
            var isBlocked = $btn.data('blocked') == 1;
            var confirmText = isBlocked
                ? 'Разблокировать этого пользователя?'
                : 'Заблокировать этого пользователя?';

            if (!confirm(confirmText)) {
                return;
            }

            $btn.prop('disabled', true);

            $.ajax({
                url: $btn.data('url'),
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                data: {
                    is_blocked: isBlocked ? 0 : 1
                }
            }).done(function (response) {
                var blocked = !!response.is_blocked;
                var $row = $btn.closest('tr');
                var $icon = $btn.find('i');

                $btn.data('blocked', blocked ? 1 : 0);
                $btn.attr('title', blocked ? 'Разблокировать' : 'Заблокировать');
                $icon
                    .toggleClass('fa-ban text-warning', !blocked)
                    .toggleClass('fa-unlock text-success', blocked);
                $row.toggleClass('text-muted', blocked);
                $row.find('.user-blocked-badge').toggle(blocked);

                showUserActionAlert(response.message, 'success');
            }).fail(function (xhr) {
                var message = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Не удалось изменить статус пользователя.';
                showUserActionAlert(message, 'danger');
            }).always(function () {
                $btn.prop('disabled', false);
            });
        });

    </script>
@endsection
