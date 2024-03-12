@extends('layouts.main2')
@section('content')

    <main>
        <div class="container">
            <div class="row">
                <div class="col-lg-11 mx-auto">
                    <section class="edica-404">
                        <h1 class="page-title" data-aos="fade-up">Заявка принята!</h1>
                        <h5 class="edica-404-subtitle" data-aos="fade-up" data-aos-delay="100">Поздравляем!</h5>
                        <p class="edics-404-text" data-aos="fade-up" data-aos-delay="200">Скоро на указанный Вами телефон позвонит сотрудник Контакт центра. Ожидайте звонка.</p>
                        <p class="edics-404-text" data-aos="fade-up" data-aos-delay="200">Вы всегда можете написать нам на почту, или позвонить по телефону: +7 (499) 609-60-20.</p>
                        <a href="{{route('main.index')}}" class="btn btn-primary" data-aos="fade-up" data-aos-delay="300">На главную</a>
                    </section>
                </div>
            </div>
        </div>
    </main>










@endsection
