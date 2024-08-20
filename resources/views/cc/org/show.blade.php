@extends('cc.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$org->lastname . ' ' . $org->firstname . ' ' . $org->middlename}}</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

            <div class="row">
                <div class="col-xl-6">
{{--                    <div class="alert alert-{{ $org->status_id == 1 ? 'danger' : 'warning' }}" role="alert">--}}
{{--                        @foreach($statuses as $status)--}}
{{--                            {{ $status->id == $org->status_id ? $status->title : '' }}--}}
{{--                        @endforeach--}}
{{--                    </div>--}}

                    <div class="alert @switch($org->status_id) @case(1) {{$org->status_id == 1 ? 'alert-danger' : ''}} @break @case(2) {{$org->status_id == 2 ? 'alert-warning' : ''}} @break @case(3) {{$org->status_id == 3 ? 'alert-info' : ''}}@endswitch" role="alert">
                        @foreach($statuses as $status)
                            {{$status->id == $org->status_id ? $status->title : '' }}
                        @endforeach
                    </div>

                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>Номер заявки</td>
                                    <td>{{$org->id}}</td>
                                </tr>
                                <tr>
                                    <td>Название компании</td>
                                    <td>{{$org->organization_title}}</td>
                                </tr>
                                <tr>
                                    <td>Время первой реакции</td>
                                    <td>{{$activites->interval}}</td>
                                </tr>

                                <tr>
                                    <td>Курс</td>
                                    <td>
                                        @foreach($courses as $course)
                                            {{ $course->id == $org->course_id ? $course->title : '' }}
                                        @endforeach
                                    </td>

                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{$org->email}}</td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>{{$org->phone}}</td>
                                </tr>
                                <tr>
                                    <td>Регион</td>
                                    <td>
                                        @foreach($regions as $region)
                                            {{ $region->id == $org->region_id ? $region->title : '' }}
                                        @endforeach
                                    </td>
                                </tr>

                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$org->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$org->updated_at}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="alert"><b>История изменений</b></div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tr>
                                    <th>Дата</th>
                                    <th>Сотрудник</th>
                                    <th>Что изменилось</th>
                                    <th>Изменение</th>
                                </tr>
                                @forelse ($activites as $key => $activity)
                                    <tr>
                                        <td>{{$activity->updated_at}}</td>
                                        <td>{{$activity->user}}</td>
                                        <td>{{$activity->description}}</td>
                                        <td>{{$activity->status_old}}  <i class="fas fa-arrow-right"></i>  {{$activity->status_new}}</td>
                                        <td>
                                            @if(!empty($activity->comment))
                                                <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true"><span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span></div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="collapse{{$key}}">
                                        <td colspan="5">
                                            <b>Комментарий: </b>{{$activity->comment}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td rowspan="3">Нет изменений</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">

                {{--                    <div class="col-1">--}}
                {{--                        <form method="post" action="{{route('cc.lid.delete', $lid->id)}}">--}}
                {{--                            @csrf--}}
                {{--                            @method('DELETE')--}}
                {{--                            <button class="btn btn-danger float-end" type="submit" disabled>Удалить</button>--}}
                {{--                        </form>--}}
                {{--                    </div>--}}
                <div class="col-2">
                    <a class="btn btn-outline-primary mr-2"
                       href="{{route('cc.org.edit', $org->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('cc.org.index')}}">Назад</a>
                </div>


            </div>

        </div><!-- /.container-fluid -->
    </section>

@endsection
