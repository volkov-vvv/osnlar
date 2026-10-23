@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Учебные группы</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    <a href="{{ route('admin.studygroup.create') }}" type="button" class="btn btn-primary">
                        <i class="fa fa-plus-circle"></i> Создать
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row pb-3">
                                <div class="col col-md-3">
                                    Краткое название:
                                    <select id="short_title" name="short_title" class="form-control form-control-sm custom-filters">
                                        <option></option>
                                        @foreach($studyGroups->pluck('short_title')->filter()->unique()->sort()->values() as $shortTitle)
                                            <option value="{{ $shortTitle }}">{{ $shortTitle }}</option>
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
                                <div class="col col-md-6 d-flex justify-content-end align-items-end">
                                    <button id="resetTable" class="btn btn-secondary"><i class="fas fa-eraser mr-1"></i>Очистить фильтры</button>
                                </div>
                            </div>

                            <table id="studygroups_table" class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Полное название</th>
                                    <th>Краткое название</th>
                                    <th>Курс</th>
                                    <th>Участников</th>
                                    <th>Дата начала обучения</th>
                                    <th>Дата завершения обучения</th>
                                    <th>Срок обучения</th>
                                    <th>Кол-во рабочих дней</th>
                                    <th>Дата создания</th>
                                    <th>Дата обновления</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($studyGroups as $studyGroup)
                                    <tr>
                                        <td>{{ $studyGroup->id }}</td>
                                        <td>{{ $studyGroup->title }}</td>
                                        <td>{{ $studyGroup->short_title ?: '—' }}</td>
                                        <td>
                                            @if($studyGroup->course)
                                                <a href="{{ route('admin.course.show', $studyGroup->course->id) }}">{{ $studyGroup->course->short_title ?: $studyGroup->course->title }}</a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $studyGroup->users_count }}</td>
                                        <td>{{ $studyGroup->starts_at ? $studyGroup->starts_at->format('d.m.Y') : '—' }}</td>
                                        <td>{{ $studyGroup->ends_at ? $studyGroup->ends_at->format('d.m.Y') : '—' }}</td>
                                        <td>{{ $studyGroup->durationLabel() ?: '—' }}</td>
                                        <td>{{ $studyGroup->workdaysLabel() ?: '—' }}</td>
                                        <td>{{ $studyGroup->created_at }}</td>
                                        <td>{{ $studyGroup->updated_at }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('admin.studygroup.show', $studyGroup->id) }}" title="Просмотр"><i class="far fa-eye"></i></a>&nbsp;&nbsp;
                                                <a href="{{ route('admin.studygroup.edit', $studyGroup->id) }}" class="text-success" title="Редактировать"><i class="fas fa-pen"></i></a>&nbsp;&nbsp;
                                                <form method="post" action="{{ route('admin.studygroup.destroy', $studyGroup->id) }}" class="mb-0" onsubmit="return confirm('Удалить учебную группу?');">
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
        var table = new DataTable('#studygroups_table', {
            order: [[0, 'desc']],
            columnDefs: [
                { visible: false, targets: [9, 10] },
                { orderable: false, searchable: false, targets: 11 }
            ],
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            dom: "<'row mb-3'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6 text-md-right'B>>" +
                "<'row'<'col-sm-12'<'table-responsive'tr>>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                "excel",
                { extend: "colvis", align: "button-right" }
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
                    colvis: 'Выбрать колонки'
                },
                emptyTable: "Пока нет учебных групп",
                zeroRecords: "Ничего не найдено"
            }
        });

        $('#short_title').on('change', function () {
            table.column(2).search(this.value, {exact: true}).draw();
        });

        $('#course').on('change', function () {
            table.column(3).search(this.value, {exact: true}).draw();
        });

        $('#resetTable').on('click', function () {
            table.search('').columns().search('').order([[0, 'desc']]).draw();
            $('#short_title').val(null).trigger('change');
            $('#course').val(null).trigger('change');
        });
    </script>
@endsection
