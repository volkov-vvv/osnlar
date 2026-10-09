@extends('layouts.main2')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}">
    <style>
        .order-create-form .form-control,
        .order-create-form .input-group-text,
        .order-create-form .select2-container--bootstrap4 .select2-selection {
            border-color: #dee2e6;
        }
        .order-create-form .form-control:focus {
            border-color: #ced4da;
            box-shadow: 0 0 0 0.2rem rgba(206, 212, 218, 0.35);
        }
    </style>
@endpush

@section('content')
<main class="blog">
    <div class="container pt-5" style="padding-bottom: 120px;">
        <div class="row mb-4">
            <div class="col">
                <h2 class="text-center">Заказ на обучение</h2>
                <p class="text-center text-muted mb-0">Курс: {{ $course->title }}</p>
            </div>
        </div>

        <form class="order-create-form" action="{{ route('order.store') }}" method="post">
            @csrf

            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">* </span>Фамилия</label>
                        <input name="lastname" type="text" class="form-control" value="{{ old('lastname') }}">
                        @error('lastname')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">* </span>Имя</label>
                        <input name="firstname" type="text" class="form-control" value="{{ old('firstname') }}">
                        @error('firstname')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Отчество</label>
                        <input name="middlename" type="text" class="form-control" value="{{ old('middlename') }}">
                        @error('middlename')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">* </span>Телефон</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input name="phone" id="phone" type="tel" class="form-control" value="{{ old('phone') }}">
                            <input type="hidden" name="phone_prefix" id="phone_prefix" value="{{ old('phone_prefix', '7') }}">
                        </div>
                        @error('phone')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">* </span>Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Выберите Ваш регион</label>
                        <select name="region_id" class="form-control select2">
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ $region->id == old('region_id') ? 'selected' : '' }}>{{ $region->title }}</option>
                            @endforeach
                        </select>
                        @error('region_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ваш персональный агент</label>
                        <select name="agent_id" class="form-control select2">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ $agent->id == old('agent_id') ? 'selected' : '' }}>{{ $agent->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-2 mb-4">
                <div class="col">
                    <div class="form-check">
                        <input name="politic" class="form-check-input" type="checkbox" id="politicCheckbox" value="1" {{ old('politic') ? 'checked' : '' }}>
                        <label for="politicCheckbox" class="form-check-label">
                            <span class="text-danger">* </span>Я соглашаюсь с
                            <a href="{{ asset('files/politic.pdf') }}" target="_blank">политикой обработки персональных данных</a>
                        </label>
                    </div>
                    @error('politic')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="utm_source" value="{{ $utm['utm_source'] }}">
            <input type="hidden" name="utm_medium" value="{{ $utm['utm_medium'] }}">
            <input type="hidden" name="utm_campaign" value="{{ $utm['utm_campaign'] }}">

            <div class="mt-2 mb-4">
                <button type="submit" class="btn btn-primary">Отправить</button>
                <a class="btn btn-outline-secondary" href="{{ route('course.show', $course->id) }}">Назад</a>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/i18n/ru.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/data.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                language: 'ru',
                theme: 'bootstrap4',
                width: '100%'
            });

            var input = document.querySelector('#phone');
            if (input && window.intlTelInput) {
                var iti = window.intlTelInput(input, {
                    strictMode: true,
                    showSelectedDialCode: true,
                    nationalMode: false,
                    initialCountry: 'ru',
                    onlyCountries: ['ru', 'by'],
                    i18n: {
                        ru: 'Россия',
                        by: 'Беларусь'
                    },
                    hiddenInput: function () {
                        return { phone: 'phone' };
                    },
                    utilsScript: "{{ asset('js/intlTelInput/utils.js') }}?1712939239769"
                });

                input.addEventListener('countrychange', function () {
                    document.getElementById('phone_prefix').value = iti.getSelectedCountryData().dialCode;
                });
            }
        });
    </script>
@endpush
