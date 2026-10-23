@extends('layouts.main2')

@push('styles')
    <style>
        .lk-hero {
            padding: 2.5rem 1rem 2rem;
            text-align: center;
        }
        .lk-hero h1 {
            margin-bottom: 0;
        }
        .lk-welcome {
            margin-bottom: 2.5rem;
        }
        .lk-welcome-card {
            padding: 2.25rem 1.5rem;
            text-align: center;
            border: 1px solid #c5e6ce;
            border-radius: 1rem;
            background: linear-gradient(135deg, #e5f6ea 0%, #f3fbf5 55%, #ffffff 100%);
        }
        .lk-welcome-title {
            margin: 0 0 0.75rem;
            color: #1b7a3a;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.3;
        }
        .lk-welcome-text {
            margin: 0;
            color: #3d444b;
            font-size: 1.05rem;
            line-height: 1.5;
        }
        .lk-links {
            display: flex;
            align-items: stretch;
            gap: 1rem;
            margin: 0 auto 5rem;
        }
        .lk-link {
            display: flex;
            align-items: center;
            flex: 1 1 0;
            min-width: 0;
            margin: 0;
            padding: 1.1rem 1.15rem;
            border: 1px solid #e3e6ea;
            border-radius: 1rem;
            background: linear-gradient(135deg, #f3f4f6 0%, #f8f9fa 60%, #ffffff 100%);
            color: inherit;
            text-decoration: none;
            text-align: left;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .lk-link:hover,
        .lk-link:focus {
            color: inherit;
            text-decoration: none;
            border-color: #1f8a42;
            box-shadow: 0 6px 18px rgba(31, 138, 66, 0.16);
        }
        .lk-link-icon {
            flex: 0 0 3.5rem;
            width: 3.5rem;
            height: 3.5rem;
            margin-right: 1rem;
            border-radius: 50%;
            background: #fff;
            color: #094ca1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            box-shadow: 0 4px 14px rgba(9, 76, 161, 0.12);
        }
        .lk-link-title {
            color: #094ca1;
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.25;
        }
        .lk-orders {
            margin-bottom: 3rem;
        }
        .lk-orders-title {
            margin: 0 0 1.25rem;
            color: #094ca1;
            font-size: 1.35rem;
            font-weight: 700;
            text-align: center;
        }
        .lk-orders-card {
            padding: 1.5rem;
            border: 1px solid #c5e6ce;
            border-radius: 1rem;
            background: linear-gradient(135deg, #f7fbf8 0%, #ffffff 60%);
        }
        .lk-orders-card .table {
            margin-bottom: 0;
            background: #fff;
        }
        .lk-orders-card .table thead th {
            border-top: 0;
            color: #094ca1;
            font-weight: 700;
            white-space: nowrap;
        }
        .lk-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.45rem 1rem;
            border: 0;
            border-radius: 0.65rem;
            background: #1f8a42;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
            box-shadow: 0 6px 14px rgba(31, 138, 66, 0.2);
            transition: background-color 0.15s ease, box-shadow 0.15s ease;
            cursor: pointer;
        }
        .lk-btn:hover,
        .lk-btn:focus {
            background: #176b33;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 5px 12px rgba(23, 107, 51, 0.26);
        }
        @media (max-width: 575.98px) {
            .lk-hero {
                padding: 1.75rem 0 1.5rem;
            }
            .lk-welcome-title {
                font-size: 1.25rem;
            }
            .lk-welcome-text {
                font-size: 0.95rem;
            }
            .lk-links {
                flex-direction: column;
            }
            .lk-link {
                padding: 1rem;
            }
            .lk-link-title {
                font-size: 1.05rem;
            }
            .lk-orders-card {
                padding: 1rem;
                overflow-x: auto;
            }
        }
    </style>
@endpush

@section('content')
    <main class="blog">
        <div class="container">
            <div class="row" data-aos="fade-up">
                <div class="col lk-hero">
                    <h1>Личный кабинет</h1>
                </div>
            </div>

            <section class="lk-welcome" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <div class="lk-welcome-card">
                            <h2 class="lk-welcome-title">Приветствуем Вас на Платформе «Основание».</h2>
                            <p class="lk-welcome-text">
                                Перед тем как начать учиться, просим Вас заполнить свой Профиль
                                и приступить к загрузке сканов Документов, проверьте паспортные данные и документ об образовании.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="row" data-aos="fade-up">
                <div class="col-lg-10 mx-auto">
                    <div class="lk-links">
                        <a class="lk-link" href="{{ route('profile.edit') }}">
                            <div class="lk-link-icon" aria-hidden="true"><i class="fas fa-user"></i></div>
                            <div class="lk-link-title">Профиль</div>
                        </a>
                        <a class="lk-link" href="{{ route('user.documents') }}">
                            <div class="lk-link-icon" aria-hidden="true"><i class="fas fa-folder-open"></i></div>
                            <div class="lk-link-title">Документы</div>
                        </a>
                    </div>
                </div>
            </div>

            @if($userOrders->isNotEmpty())
                <section class="lk-orders" data-aos="fade-up">
                    <h3 class="lk-orders-title">Мои заказы</h3>
                    <div class="lk-orders-card">
                        <div class="table-responsive">
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
                                </thead>
                                <tbody>
                                @foreach($userOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>{{ $order->course->title }}</td>
                                        <td>{{ $order->amount }}</td>
                                        <td>{{ $order->status->title }}</td>
                                        <td>
                                            @if($order->status->code == 'payment-allowed')
                                                <form method="post" action="{{ route('payment.create') }}">
                                                    @csrf
                                                    <input type="hidden" name="description" value="Оплата обучения">
                                                    <input type="hidden" name="amount" value="{{ $order->amount }}">
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                    <button type="submit" class="lk-btn">Оплатить</button>
                                                </form>
                                            @endif
                                            @if($order->status->code == 'waiting-documents')
                                                <a href="{{ route('user.docs', ['order' => $order->id]) }}" class="lk-btn">Загрузить документы</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </main>
@endsection
