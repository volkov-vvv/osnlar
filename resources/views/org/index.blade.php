@extends('layouts.main2')

@section('content')
    @include('partials.success-card', [
        'title' => 'Заявка принята!',
        'subtitle' => 'Поздравляем!',
        'lead' => 'Скоро с вами свяжется сотрудник Контакт-центра.',
    ])
@endsection
