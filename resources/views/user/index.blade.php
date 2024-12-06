@extends('layouts.main2')
@section('content')

    <div class="row pt-5 pb-5">
        <div class="col text-center">
            <h1>Мои заказы</h1>
        </div>
    </div>

    <main class="blog">
        <div class="container">
            <section class="featured-posts-section">
                <div class="row">
                    <div class="col fetured-post blog-post" data-aos="fade-right">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">

                                        <table id="user_orders_table" class="table table-bordered table-striped hover">
                                            <thead>
                                            <tr>
                                                <th>№</th>
                                                <th>Дата</th>
                                                <th>Курс</th>
                                                <th>Стоимость</th>
                                                <th>Статус</th>
                                                <th>Действия</th>
                                            </tr>
                                            <tbody>
                                            @foreach($userOrders as $order)
                                                <tr>
                                                    <td>{{$order->id}}</td>
                                                    <td>{{$order->created_at}}</td>
                                                    <td>{{$order->course->title}}</td>
                                                    <td>{{$order->amount}}</td>
                                                    <td>{{$order->status->title}}</td>
                                                    <td>
                                                        @if($order->status->code == 'payment-allowed')
                                                            <form method="post" action="{{route('payment.create')}}">
                                                                @csrf
                                                                <input type="hidden" name="description" id="description" value="Оплата обучения">
                                                                <input type="hidden" name="amount" id="amount" value="{{$order->amount}}">
                                                                <input type="hidden" name="order_id" id="amount" value="{{$order->id}}">
                                                                <button type="submit" class="btn btn-primary">
                                                                    Оплатить
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            </thead>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card-->
                    </div>
                </div>
            </section>

        </div>

    </main>
@endsection
