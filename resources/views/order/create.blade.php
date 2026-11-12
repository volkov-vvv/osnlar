@extends('layouts.main2')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}">
    <style>
        .order-course-title {
            font-size: 1.2rem;
        }
        .order-course-title .course-study-hours {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin: 0 0 0 0.55rem;
            padding: 0.28rem 0.75rem;
            border: 1px solid #063a7a;
            border-radius: 999px;
            background: linear-gradient(135deg, #094ca1 0%, #0b5cc0 55%, #0a4fa8 100%);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0.01em;
            white-space: nowrap;
            vertical-align: middle;
            position: relative;
            top: -0.12em;
            box-shadow: 0 4px 12px rgba(9, 76, 161, 0.28);
        }
        .order-course-title .course-study-hours i {
            font-size: 0.85em;
            color: #fff;
            opacity: 0.95;
        }

        .order-create-form .order-group {
            margin-bottom: 1.15rem;
            padding: 1.15rem 1.2rem 0.55rem;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
        }
        .order-create-form .order-group__title {
            margin: 0 0 1rem;
            color: #094ca1;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }
        .order-create-form .form-label {
            margin-bottom: 0.45rem;
            color: #3d4a5c;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .order-create-form .form-control,
        .order-create-form .select2-container--bootstrap4 .select2-selection {
            border: 1px solid #d5dde8;
            border-radius: 0.75rem;
            background-color: #f8fafc;
            color: #1f2937;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }
        .order-create-form .form-control {
            min-height: calc(1.5em + 1rem + 2px);
            padding: 0.55rem 0.95rem;
            font-size: 0.975rem;
            line-height: 1.5;
        }
        .order-create-form .form-control::placeholder {
            color: #94a3b8;
        }
        .order-create-form .form-control:hover,
        .order-create-form .select2-container--bootstrap4 .select2-selection:hover {
            border-color: #b7c5d8;
            background-color: #fff;
        }
        .order-create-form .form-control:focus {
            border-color: #094ca1;
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.14);
            outline: 0;
        }
        .order-create-form select.form-control:not(.select2-hidden-accessible) {
            padding-top: 0.55rem;
            padding-bottom: 0.55rem;
            padding-right: 2.4rem;
            line-height: 1.5;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 6l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 14px 14px;
        }
        .order-create-form .select2-container {
            width: 100% !important;
            margin-bottom: 1rem;
        }
        .order-create-form .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 1rem + 2px) !important;
            padding: 0;
            font-size: 0.975rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 6l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 14px 14px;
        }
        .order-create-form .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            padding-left: 0.95rem;
            padding-right: 2.4rem;
            line-height: calc(1.5em + 1rem);
            color: #1f2937;
        }
        .order-create-form .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8;
            line-height: calc(1.5em + 1rem);
        }
        .order-create-form .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            display: none;
        }
        .order-create-form .select2-container--bootstrap4.select2-container--focus .select2-selection,
        .order-create-form .select2-container--bootstrap4.select2-container--open .select2-selection {
            border-color: #094ca1;
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.14);
        }
        .order-create-form .select2-container--bootstrap4 .select2-dropdown {
            border-color: #d5dde8;
            border-radius: 0.75rem;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.1);
            overflow: hidden;
        }
        .order-create-form .select2-container--bootstrap4 .select2-results__option--highlighted,
        .order-create-form .select2-container--bootstrap4 .select2-results__option--highlighted.select2-results__option[aria-selected="true"] {
            background-color: #094ca1;
        }

        .order-create-form .iti {
            width: 100%;
            display: block;
        }
        .order-create-form .iti .iti__tel-input,
        .order-create-form .iti input[type="tel"] {
            width: 100%;
        }
        .order-create-form .iti__selected-flag {
            border-radius: 0.75rem 0 0 0.75rem;
        }

        .order-create-form .form-check {
            padding: 0.85rem 1rem 0.85rem 2.2rem;
            border: 1px solid #d5dde8;
            border-radius: 0.75rem;
            background: #f8fafc;
        }
        .order-create-form .form-check-input {
            margin-top: 0.35rem;
        }
        .order-create-form .form-check-label a {
            color: #094ca1;
            font-weight: 600;
        }

        .order-create-form .btn-submit {
            min-width: 9.5rem;
            padding: 0.65rem 1.35rem;
            border: 1px solid #063a7a;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #094ca1 0%, #0b5cc0 100%);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 8px 18px rgba(9, 76, 161, 0.2);
            transition: background 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
        }
        .order-create-form .btn-submit:hover,
        .order-create-form .btn-submit:focus {
            background: linear-gradient(135deg, #073a7a 0%, #094ca1 100%);
            border-color: #063a7a;
            color: #fff;
            box-shadow: 0 10px 22px rgba(9, 76, 161, 0.26);
            transform: translateY(-1px);
        }
        .order-create-form .btn-outline-secondary {
            border-radius: 0.75rem;
            padding: 0.65rem 1.2rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
<main class="blog">
    <div class="container pt-5" style="padding-bottom: 120px;">
        <div class="row mb-4">
            <div class="col">
                <h2 class="text-center">Заказ на обучение</h2>
                <p class="text-center mb-0 order-course-title">
                    <strong>{{ $course->title }}</strong>
                    @if($course->studyHoursLabel())
                        <span class="course-study-hours"><i class="fas fa-clock" aria-hidden="true"></i>{{ $course->studyHoursLabel() }}</span>
                    @endif
                </p>
            </div>
        </div>

        <form class="order-create-form" action="{{ route('order.store') }}" method="post">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="utm_source" value="{{ $utm['utm_source'] }}">
            <input type="hidden" name="utm_medium" value="{{ $utm['utm_medium'] }}">
            <input type="hidden" name="utm_campaign" value="{{ $utm['utm_campaign'] }}">

            <div class="order-group">
                <h3 class="order-group__title">Личные данные</h3>

                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">* </span>Фамилия</label>
                        <input name="lastname" type="text" class="form-control mb-3" value="{{ old('lastname') }}">
                        @error('lastname')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">* </span>Имя</label>
                        <input name="firstname" type="text" class="form-control mb-3" value="{{ old('firstname') }}">
                        @error('firstname')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Отчество</label>
                        <input name="middlename" type="text" class="form-control mb-3" value="{{ old('middlename') }}">
                        @error('middlename')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="order-group">
                <h3 class="order-group__title">Контакты</h3>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label"><span class="text-danger">* </span>Телефон</label>
                        <div class="mb-3">
                            <input name="phone" id="phone" type="tel" class="form-control" value="{{ old('phone') }}">
                            <input type="hidden" name="phone_prefix" id="phone_prefix" value="{{ old('phone_prefix', '7') }}">
                        </div>
                        @error('phone')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span class="text-danger">* </span>Email</label>
                        <input name="email" type="email" class="form-control mb-3" value="{{ old('email') }}">
                        @error('email')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Выберите Ваш регион</label>
                        <select name="region_id" class="form-control select2">
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ $region->id == old('region_id') ? 'selected' : '' }}>{{ $region->title }}</option>
                            @endforeach
                        </select>
                        @error('region_id')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ваш персональный агент</label>
                        <select name="agent_id" class="form-control select2">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ $agent->id == old('agent_id') ? 'selected' : '' }}>{{ $agent->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input name="politic" class="form-check-input" type="checkbox" id="politicCheckbox" value="1" {{ old('politic') ? 'checked' : '' }}>
                    <label for="politicCheckbox" class="form-check-label">
                        <span class="text-danger">* </span>Я соглашаюсь с
                        <a href="{{ route('privacy') }}" target="_blank" rel="noopener">политикой обработки персональных данных</a>
                    </label>
                    @error('politic')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <button type="submit" class="btn btn-submit">Отправить</button>
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
                    formatAsYouType: true,
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

                document.getElementById('phone_prefix').value = iti.getSelectedCountryData().dialCode;

                input.addEventListener('countrychange', function () {
                    document.getElementById('phone_prefix').value = iti.getSelectedCountryData().dialCode;
                });
            }
        });
    </script>
@endpush
