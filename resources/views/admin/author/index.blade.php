@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Авторы</h1>
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
                    <a href="{{route('admin.author.create')}}" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row pb-3">
                                <div class="col col-md-2">
                                    Публикация:
                                    <select id="is_published" name="is_published" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        <option value="Опубликован">Опубликован</option>
                                        <option value="Не опубликован">Не опубликован</option>
                                    </select>
                                </div>
                                <div class="col col-md-10 d-flex justify-content-end align-items-end">
                                    <button id="resetTable" class="btn btn-secondary"><i class="fas fa-eraser mr-1"></i>Очистить фильтры</button>
                                </div>
                            </div>

                            <table id="authors_table" class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Фото</th>
                                    <th>Фамилия</th>
                                    <th>Имя</th>
                                    <th>Отчество</th>
                                    <th>Псевдоним</th>
                                    <th>Телефон</th>
                                    <th>E-mail</th>
                                    <th>Дата создания</th>
                                    <th>Дата редактирования</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($authors as $author)
                                    <tr>
                                        <td>{{$author->id}}</td>
                                        <td>
                                            @if($author->photo)
                                                <img
                                                    src="{{ url('storage/' . $author->photo) }}"
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
                                        <td>{{ $author->lastname }}</td>
                                        <td>{{ $author->firstname }}</td>
                                        <td>{{ $author->middlename }}</td>
                                        <td>{{ $author->name }}</td>
                                        <td>{{ $author->phone }}</td>
                                        <td>{{ $author->email }}</td>
                                        <td>{{$author->created_at}}</td>
                                        <td>{{$author->updated_at}}</td>
                                        <td data-search="{{ $author->is_published ? 'Опубликован' : 'Не опубликован' }}">
                                            <div class="d-flex align-items-center">
                                            <div class="custom-control custom-switch mb-0 mr-3">
                                                <input
                                                    type="checkbox"
                                                    class="custom-control-input author-publish-switch"
                                                    id="author-publish-{{ $author->id }}"
                                                    data-url="{{ route('admin.author.publish', $author->id) }}"
                                                    {{ $author->is_published ? 'checked' : '' }}
                                                >
                                                <label class="custom-control-label font-weight-normal" for="author-publish-{{ $author->id }}">Публикация</label>
                                            </div>
                                            <a href="{{route('admin.author.show', $author->id)}}"><i class="far fa-eye"></i></a>&nbsp;&nbsp;
                                            <a href="{{route('admin.author.edit', $author->id)}}" class="text-success"><i class="fas fa-pen"></i></a>&nbsp;&nbsp;
                                            <form method="post" action="{{route('admin.author.destroy', $author->id)}}" class="mb-0">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-transparent border-0" type="submit"><i class="fas fa-trash text-danger" role="button"></i></button>
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
        #authors_table td {
            vertical-align: middle;
        }

        #authors_table img,
        #authors_table .rounded-circle {
            vertical-align: middle;
        }
    </style>
    <script>
        let stateSaveTimer;
        var table = new DataTable('#authors_table', {

            stateSave: true,

            stateSaveParams: function (settings, data) {
                data.custom_filters = {
                    is_published: $('#is_published').val(),
                };
            },

            stateLoadParams: function (settings, data) {
                if (data && data.custom_filters) {
                    $('#is_published').val(data.custom_filters.is_published).trigger('change');
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
                            page_url: "admin.author.index",
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
                    data: { page_url: "admin.author.index" },
                    dataType: "json",
                    success: function(json) {
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

        $('#is_published').on('change', function () {
            var value = this.value;
            table
                .column(10)
                .search(value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '', true, false)
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

            $('#is_published').val(null).trigger('change');
        });

        $(document).on('change', '.author-publish-switch', function () {
            var input = $(this);
            var cell = input.closest('td');
            input.prop('disabled', true);

            $.ajax({
                url: input.data('url'),
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                data: {
                    is_published: input.is(':checked') ? 1 : 0
                }
            }).done(function () {
                cell.attr('data-search', input.is(':checked') ? 'Опубликован' : 'Не опубликован');
                table.cell(cell).invalidate().draw(false);
            }).fail(function () {
                input.prop('checked', !input.is(':checked'));
            }).always(function () {
                input.prop('disabled', false);
            });
        });
    </script>
@endsection
