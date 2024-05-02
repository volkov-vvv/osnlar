@extends('cc.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$link->link}}</h1>
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
                <div class="col-6">

                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>Ссылка</td>
                                    <td>{{$link->link}}</td>
                                </tr>
                                <tr>
                                    <td>Регион</td>
                                    <td>{{$link->region->title}}</td>
                                </tr>
                                <tr>
                                    <td>Курс</td>
                                    <td>{{$link->course->title}}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$link->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$link->updated_at}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>



            </div>

            <!-- /.row -->
            <div class="row">

                                    <div class="col-1">
                                        <form method="post" action="{{route('cc.link.destroy', $link->id)}}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger float-end" type="submit" >Удалить</button>
                                        </form>
                                    </div>
                <div class="col-2">
                    <a class="btn btn-outline-primary mr-2"
                       href="{{route('cc.link.edit', $link->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('cc.link.index')}}">Назад</a>
                </div>


            </div>

        </div><!-- /.container-fluid -->
    </section>

@endsection
