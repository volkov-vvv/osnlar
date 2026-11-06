@extends('layouts.main2')

@section('content')
    @include('partials.success-card', [
        'title' => 'Заказ оформлен!',
        'subtitle' => 'Поздравляем!',
        'lead' => 'Скоро на указанный Вами телефон позвонит сотрудник Контакт-центра. Ожидайте звонка.',
        'courseTitle' => $course->title ?? null,
    ])
@endsection
