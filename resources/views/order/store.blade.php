@extends('layouts.main-lid')
@section('content')
    <main>
        <div class="container">
            <div class="row">
                <div class="col-lg-11 mx-auto">
                    <section class="edica-404">
                        <h3 class="page-title" data-aos="fade-up">Заказ оформлен!</h3>
                        <h5 class="edica-404-subtitle" data-aos="fade-up" data-aos-delay="100">Поздравляем!</h5>
                        <p class="edics-404-text" data-aos="fade-up" data-aos-delay="200">Статус заказа Вы можете увидеть в Личном кабинете.</p>
                        <p class="edics-404-text" data-aos="fade-up" data-aos-delay="200">На указанный Вами почтовый ящик отправлено письмо с логином и паролем для входа в Личный кабинет.</p>
                        <p class="edics-404-text" data-aos="fade-up" data-aos-delay="200">Вы всегда можете написать нам на почту, или позвонить по телефону: +7 (499) 609-60-20.</p>
                        <a href="{{route('main.index')}}" class="btn btn-primary" data-aos="fade-up" data-aos-delay="300">На главную</a>
                        <br><br>
                        <p>Логин: <b>{{$userData['email']}}</b></p>
                        <p>Пароль: <b>{{$userData['password']}}</b></p>
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection
