@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Сведения об организации</h1>
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
{{--                    <a href="{{route('admin.about.create')}}" type="button" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Создать</a>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>Сайт</th>
                                    <th>Дата создания</th>
                                    <th>Дата обновления</th>
                                    <th colspan="3">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($abouts as $about)
                                    <tr>
                                        <td>osnovanie.info</td>
                                        <td>{{$about->created_at}}</td>
                                        <td>{{$about->updated_at}}</td>
                                        <td><a  href="{{route('admin.about.show', $about->id)}}"><i class="far fa-eye"></i></a></td>
                                        <td><a  href="{{route('admin.about.edit', $about->id)}}" class="text-success"><i class="fas fa-pen"></i></a></td>
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
