@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$lid->lastname . ' ' . $lid->firstname . ' ' . $lid->middlename}}</h1>
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

                    <div class="alert" style="background-color: {{$lid->status->color}} !important; color:{{contrast_color($lid->status->color)}}">
                        {{$lid->status->title}}
                    </div>

                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td>Номер заявки</td>
                                    <td>{{$lid->id}}</td>
                                </tr>
                                <tr>
                                    <td>Время первой реакции</td>
                                    <td>{{$activites->interval}}</td>
                                </tr>
                                <tr>
                                    <td>Дата рождения</td>
                                    <td>{{$lid->data}}</td>
                                </tr>
                                <tr>
                                    <td>Курс</td>
                                    <td>
                                        @foreach($courses as $course)
                                            {{ $course->id == $lid->course_id ? $course->title : '' }}
                                        @endforeach
                                    </td>

                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{$lid->email}}</td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>{{$lid->phone}}</td>
                                </tr>
                                <tr>
                                    <td>Регион</td>
                                    <td>
                                        @foreach($regions as $region)
                                            {{ $region->id == $lid->region_id ? $region->title : '' }}
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>Категория</td>
                                    <td>
                                        @foreach($categories as $category)
                                            {{ $category->id == $lid->category_id ? $category->title : '' }}
                                        @endforeach</td>
                                </tr>
                                <tr>
                                    <td>Образование</td>
                                    <td>
                                        @foreach($levels_edu as $level_edu)
                                            {{ $level_edu->id == $lid->lid_level_edu_id ? $level_edu->title : '' }}
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$lid->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$lid->updated_at}}</td>
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
                            <table class="table table-hover ">
                                <tr>
                                    <th>Дата</th>
                                    <th>Автор</th>
                                    <th>Что изменилось</th>
                                    <th>Изменение</th>
                                </tr>
                                @forelse ($activites as $key => $activity)
                                    <tr>
                                        <td>{{$activity->updated_at}}</td>
                                        <td>{{$activity->user}}</td>
                                        <td>{{$activity->description}}</td>
                                        <td>{{$activity->status_old}} <i class="fas fa-arrow-right"></i> {{$activity->status_new}}</td>
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

                                    <div class="col-1">
                                        <form method="post" action="{{route('admin.lid.destroy', $lid->id)}}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger float-end" type="submit" >Удалить</button>
                                        </form>
                                    </div>
                <div class="col-2">
                    <a class="btn btn-outline-primary mr-2"
                       href="{{route('admin.lid.edit', $lid->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.lid.index')}}">Назад</a>
                </div>


            </div>

        </div><!-- /.container-fluid -->
    </section>

@endsection
