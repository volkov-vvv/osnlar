@extends('layouts.main2')

@section('content')
    @include('partials.success-card', [
        'title' => 'Заявка принята!',
        'subtitle' => 'Поздравляем!',
        'lead' => 'Скоро на указанный Вами телефон позвонит сотрудник Контакт-центра. Ожидайте звонка.',
    ])
@endsection
