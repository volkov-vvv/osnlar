@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Контакт-центр</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            Год:
                            <select id="year" name="year" class="form-control form-control-sm filter-year">
                                @include('partials.year-options', [
                                    'yearOptions' => $years,
                                    'defaultYear' => $defaultYear,
                                    'selectDefault' => true,
                                ])
                            </select>
                            <table id="dashboard_table_ajax" class="table table-bordered table-striped hover">
                                <thead>
                                <tr>
                                    <th>ФИО</th>
                                    <th>Кол-во обработанных заявок</th>
                                    <th>% обработанных заявок</th>
                                    <th>Среднее время реакции</th>
                                    <th>Завершил обучение</th>
                                    <th>Обучение</th>
                                    <th>Ждем на РР</th>
                                    <th>Недозвон</th>
                                    <th>Отказ</th>
                                    <th>Год</th>
                                </tr>
                                </thead>
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
        var table = new DataTable('#dashboard_table_ajax', {
            order: [[2, 'desc']],
            columnDefs: [
                {
                    targets: [3],
                    orderable: false,
                },
                { targets: [9], visible: false }
            ],
            pageLength: 20,
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            sDom: 'lrtip',
            language: {
                info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                infoFiltered: "(отфильтровано из _MAX_ записей)",
                paginate: {
                    first: "Первая",
                    previous: "Предыдущая",
                    next: "Следующая",
                    last: "Последняя"
                },
                search: "Поиск:",
                buttons: {
                    colvis: 'Выбрать колонки',
                    search: 'Поиск'
                },
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.dashboard.getDashboard') }}",
            columns: [
                { data: 'fio' },
                { data: 'count' },
                { data: 'percent' },
                { data: 'average_time' },
                { data: 'status_end_learning' },
                { data: 'status_learning' },
                { data: 'status_pp' },
                { data: 'status_non_call' },
                { data: 'status_rejection' },
                { data: 'year' },
            ],
            initComplete: function () {
                var year = $('#year').val();
                if (year) {
                    this.api().column(9).search(year, { exact: true }).draw();
                }
            }
        });

        table.buttons().container().appendTo('#dashboard_table_wrapper .col-md-6:eq(0)');

        $('#year').on('change', function () {
            table
                .column(9)
                .search(this.value, { exact: true })
                .draw();
        });
    </script>
@endsection
