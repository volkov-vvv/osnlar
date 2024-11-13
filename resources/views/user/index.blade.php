@extends('layouts.main2')
@section('content')
    <main class="blog">
        <div class="container">
            <section class="featured-posts-section">
                <div class="row">
                    <div class="col fetured-post blog-post" data-aos="fade-right">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">

                                        <table>
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
                                                    <td>{{$order->status}}</td>
                                                    <td>
                                                        @if($order->status == 'разрешена оплата')
                                                            <form method="post" action="{{route('payment.create')}}">
                                                                @csrf
                                                                <input type="hidden" name="description" id="description" value="Оплата обучения">
                                                                <input type="hidden" name="amount" id="amount" value="{{$order->amount}}">
                                                                <input type="hidden" name="order_id" id="amount" value="{{$order->id}}">
                                                                <button type="submit">
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
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
