@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заказы</h1>
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
                    <a href="{{route('admin.order.create')}}" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Создать</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="link_table" class="table table-bordered table-striped hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Курс</th>
                                    <th>ФИО</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{$order->id}}</td>
                                        <td>{{$order->course->title}}</td>
                                        <td>{{$order->user->name}}</td>
                                        <td>{{$order->user->email}}</td>
                                        <td></td>
                                        <td>{{$order->amount}}</td>
                                        <td>{{$order->status}}</td>
                                        <td>{{$order->created_at}}</td>
                                        <td>
                                            <a  href="{{route('admin.order.show', $order->id)}}"><i class="far fa-eye"></i></a>
                                            <a  href="{{route('admin.order.edit', $order->id)}}" class="text-success"><i class="fas fa-pen"></i></a>

                                            <form method="post" action="{{route('admin.order.destroy', $order->id)}}" class="d-inline-block">
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
