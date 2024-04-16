@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заявки</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

{{--            <div class="row mb-3">--}}
{{--                <div class="col">--}}
{{--                    <a href="{{route('admin.lid.create')}}" type="button" class="btn btn-primary"><i--}}
{{--                            class="fa-solid fa-plus"></i> Создать</a>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="row">
                <div class="col">
                        <div class="card">

                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped hover">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Агент</th>
                                        <th>Регион</th>
                                        <th>Курс</th>
                                        <th>Поток</th>
                                        <th>Фамилия</th>
                                        <th>Имя</th>
                                        <th>Категория</th>
                                        <th>Статус</th>
                                        <th>Дата создания</th>
                                        <th>utm_source</th>
                                        <th>utm_medium</th>
                                        <th>utm_campaign</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lids as $lid)
                                        <tr>
                                            <td>{{$lid->id}}</td>
                                            <td>
                                                @foreach($agents as $agent)
                                                    {{ $agent->id == $lid->agent_id ? $agent->title : '' }}
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($regions as $region)
                                                    {{ $region->id == $lid->region_id ? $region->title : '' }}
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($courses as $course)
                                                    {{ $course->id == $lid->course_id ? $course->title : '' }}
                                                @endforeach
                                            </td>
                                            <td> </td>
                                            <td>{{$lid->lastname}}</td>
                                            <td>{{$lid->firstname}}</td>
                                            <td>
                                                @foreach($categories as $category)
                                                    {{ $category->id == $lid->category_id ? $category->title : '' }}
                                                @endforeach
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill
                                                @switch($lid->status_id)
                                                @case(1) {{$lid->status_id == 1 ? 'bg-danger' : ''}}
                                                @break
                                                @case(2) {{$lid->status_id == 2 ? 'bg-warning text-dark' : ''}}
                                                @break
                                                @case(3) {{$lid->status_id == 3 ? 'bg-info' : ''}}
                                                @break
                                                @case(4) {{$lid->status_id == 4 ? 'bg-success' : ''}}
                                                @endswitch">
                                                    @foreach($statuses as $status)
                                                        {{$status->id == $lid->status_id ? $status->title : '' }}
                                                    @endforeach
                                                </span>
                                            </td>
                                            <td>{{$lid->created_at}}</td>
                                            <td>{{$lid->utm_source}}</td>
                                            <td>{{$lid->utm_medium}}</td>
                                            <td>{{$lid->utm_campaign}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card-->
                </div>
            </div>

            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>

@endsection
