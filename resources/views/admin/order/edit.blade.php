@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Редактирование заказа № "{{$order->id}}"</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div  class="col-xl-4">
                <form action="{{route('admin.order.update', $order->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label>Курс</label>
                        <input type="text" class="form-control" value="{{$order->course->title}}}" disabled>
                    </div>
                    <div class="mb-3">
                        <label>ФИО</label>
                        <input type="text" class="form-control" value="{{$order->user->firstname}} {{$order->user->name}} {{$order->user->middlename}}" disabled>
                    </div>
                    <div class="mb-3">
                        <label>Телефон</label>
                        <input type="text" class="form-control" value="{{$order->user->phone_prefix}}{{$order->user->phone}}" disabled>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="text" class="form-control" value="{{$order->user->email}}" disabled>
                    </div>
                    <div class="mb-3">
                        <label>Регион</label>
                        <input type="text" class="form-control" value="@if(isset($order->region->title)) {{$order->region->title}} @endif" disabled>
                    </div>
                    <div class="mb-3 form-group alert alert-secondary">
                        <label>Ответственный</label>
                        <select name="responsible_id" class="form-control select2">
                            <option value="">---</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}"
                                    {{ $user->id == $order->responsible_id ? ' selected' : '' }}
                                >{{$user->name}}</option>
                            @endforeach
                        </select>
                        @error('responsible_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group alert alert-secondary">
                        <label>Статус</label>
                        <select name="status" class="form-control">
                            @foreach($statuses as $status)
                                <option value="{{$status}}"
                                    {{ $order->status == $status ? ' selected' : '' }}
                                >{{$status}}</option>
                            @endforeach
                        </select>
                        @error('status')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label>Сумма</label>
                        <input name="amount" type="text" class="form-control" aria-describedby="Сумма" value="{{$order->amount}}">
                        @error('amount')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>


                    <button type="submit" id="link-edit-submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.order.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>

@endsection
