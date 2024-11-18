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
            <div  class="col">
                <form action="{{route('admin.order.update', $order->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3 form-group">
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
