@extends('layouts.main-lid')
@section('content')
    <div class="container mb-5">
        <div class="row mb-3">
            <div class="col">
                <h1 class="text-center">Поздравляем!</h1>
                <h4 class="text-center">Вы успешно оформили заказ на обучение по курсу: {{$course->title}}</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>Статус заказа Вы можете увидеть в Личном кабинете.</p>
                <h4 class="text-center">Данные аккаунта</h4>
                <p>Логин: <b>{{$userData['email']}}</b></p>
                <p>Пароль: <b>{{$userData['password']}}</b></p>
            </div>
        </div>




    </div>
@endsection
