@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Документы</h1>
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
                    <a href="{{route('admin.document.create')}}" type="button" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Добавить</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Документ</th>
                                    <th>Дата создания</th>
                                    <th>Дата обновления</th>
                                    <th colspan="3">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($documents as $document)
                                    <tr>
                                        <td>{{$document->id}}</td>
                                        <td><a href="{{url('storage/' . $document->file)}}">{{$document->title}}</a></td>
                                        <td>{{$document->created_at}}</td>
                                        <td>{{$document->updated_at}}</td>
                                        <td><a  href="{{route('admin.document.show', $document->id)}}"><i class="far fa-eye"></i></a></td>
                                        <td><a  href="{{route('admin.document.edit', $document->id)}}" class="text-success"><i class="fas fa-pen"></i></a></td>
                                        <td>
                                            <form method="post" action="{{route('admin.document.delete', $document->id)}}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-transparent border-0" type="submit"><i class="fas fa-trash text-danger" role="button"></i></button>
                                            </form>
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
