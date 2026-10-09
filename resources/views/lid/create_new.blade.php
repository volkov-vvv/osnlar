@extends('layouts.main2')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
    <style>
        .lid-create-form .form-control,
        .lid-create-form .input-group-text,
        .lid-create-form .select2-container--bootstrap4 .select2-selection {
            border-color: #dee2e6;
        }
        .lid-create-form .form-control:focus {
            border-color: #ced4da;
            box-shadow: 0 0 0 0.2rem rgba(206, 212, 218, 0.35);
        }
        .lid-create-form .bs-stepper .step-trigger {
            padding: 10px 0;
        }
        .lid-create-form .category-label {
            cursor: pointer;
            font-weight: 400;
        }
    </style>
@endpush

@section('content')
<main class="blog">
    <div class="container pt-5" style="padding-bottom: 120px;">
        <div class="row mb-4">
            <div class="col">
                <h2 class="text-center">Заявка на обучение в 2026 году</h2>
                <p class="text-center text-muted mb-0">сделайте первый шаг к востребованной профессии!</p>
            </div>
        </div>

        <form class="lid-create-form" action="{{ route('lid.store_new') }}" method="post">
            @csrf

            <div class="bs-stepper">
                <div class="bs-stepper-header" role="tablist">
                    <div class="step" data-target="#courses-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="courses-part" id="courses-part-trigger">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">Выбор курса</span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#fio-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="fio-part" id="fio-part-trigger">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">ФИО и регион</span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#contacts-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="contacts-part" id="contacts-part-trigger">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">Контакты</span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#information-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
                            <span class="bs-stepper-circle">4</span>
                            <span class="bs-stepper-label">Информация</span>
                        </button>
                    </div>
                </div>

                <div class="bs-stepper-content">
                    <div id="courses-part" class="content" role="tabpanel" aria-labelledby="courses-part-trigger">
                        <label class="form-label"><span class="text-danger">* </span>Выберите курс, на который хотите записаться</label>
                        @foreach($courses as $course)
                            @if($course->title != '---')
                                <div class="form-check mb-2">
                                    <input class="form-check-input" name="course_id" type="radio" id="course_id_{{ $course->id }}" value="{{ $course->id }}" {{ $course->id == $selectedCourse ? 'checked' : '' }}>
                                    <label class="form-check-label category-label" for="course_id_{{ $course->id }}">{{ $course->title }}</label>
                                </div>
                            @endif
                        @endforeach

                        <div class="mt-4 mb-4">
                            <button type="button" class="btn btn-primary" onclick="stepper.next()">Дальше</button>
                        </div>
                    </div>

                    <div id="fio-part" class="content" role="tabpanel" aria-labelledby="fio-part-trigger">
                        <div class="mb-3">
                            <label class="form-label"><span class="text-danger">* </span>Фамилия</label>
                            <input name="lastname" type="text" class="form-control" value="{{ old('lastname') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><span class="text-danger">* </span>Имя</label>
                            <input name="firstname" type="text" class="form-control" value="{{ old('firstname') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Отчество</label>
                            <input name="middlename" type="text" class="form-control" value="{{ old('middlename') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Дата рождения</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                <input name="data" type="text" class="form-control" data-inputmask-alias="datetime"
                                       data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" value="{{ old('data') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><span class="text-danger">* </span>Выберите Ваш регион</label>
                            <select name="region_id" class="form-control select2">
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ $region->id == old('region_id') ? 'selected' : '' }}>{{ $region->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4 mb-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="stepper.previous()">Назад</button>
                            <button type="button" class="btn btn-primary" onclick="stepper.next()">Дальше</button>
                        </div>
                    </div>

                    <div id="contacts-part" class="content" role="tabpanel" aria-labelledby="contacts-part-trigger">
                        <div class="mb-3">
                            <label class="form-label"><span class="text-danger">* </span>Телефон</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input name="phone" id="phone" type="tel" class="form-control" value="{{ old('phone') }}">
                                <input type="hidden" name="phone_prefix" id="phone_prefix" value="{{ old('phone_prefix', '7') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><span class="text-danger">* </span>Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-check mb-3 mt-4">
                            <input name="politic" class="form-check-input" type="checkbox" id="politicCheckbox" value="1" {{ old('politic') ? 'checked' : '' }}>
                            <label for="politicCheckbox" class="form-check-label">
                                <span class="text-danger">* </span>Я соглашаюсь с
                                <a href="{{ asset('files/politic.pdf') }}" target="_blank">политикой обработки персональных данных</a>
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input name="in_project" class="form-check-input" type="checkbox" id="inProjectCheckbox" value="1" {{ old('in_project') ? 'checked' : '' }}>
                            <label for="inProjectCheckbox" class="form-check-label">Я никогда не обучался(-лась) в рамках проекта "Содействие занятости"</label>
                        </div>

                        <div class="mt-4 mb-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="stepper.previous()">Назад</button>
                            <button type="button" class="btn btn-primary" onclick="stepper.next()">Дальше</button>
                        </div>
                    </div>

                    <div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                        <div class="mb-3">
                            <label class="form-label">Текущий уровень вашего образования</label>
                            <select name="lid_level_edu_id" class="form-control select2">
                                @foreach($levelsedu as $leveledu)
                                    <option value="{{ $leveledu->id }}" {{ $leveledu->id == old('lid_level_edu_id') ? 'selected' : '' }}>{{ $leveledu->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Выберите подходящую для Вас категорию</label>
                            @foreach($categoriesMain as $categoryMain)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" name="category_main" type="radio" id="category_main_{{ $categoryMain->id }}" value="{{ $categoryMain->id }}">
                                    <label class="form-check-label category-label" for="category_main_{{ $categoryMain->id }}">{{ $categoryMain->title }}</label>
                                </div>
                            @endforeach

                            <div id="category_all" class="ms-3 mt-2">
                                <p class="mb-2">Все категории:</p>
                                <select name="category_all" class="form-control select2">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="category_id" value="{{ old('category_id') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ваш персональный агент</label>
                            <select name="agent_id" class="form-control select2">
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $agent->id == old('agent_id') ? 'selected' : '' }}>{{ $agent->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <div
                                style="height: 100px"
                                id="captcha-container"
                                class="smart-captcha"
                                data-sitekey="ysc1_HS8I72wAFfPh2X4mqPtIPHrpIaq8zkaDIX5PZNXtb0548045"
                            ></div>
                            @error('smart-token')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4 mb-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="stepper.previous()">Назад</button>
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <ul class="mt-3 mb-0">
                    @error('course_id')
                    <li class="text-danger">{{ $message }}</li>
                    @enderror
                    @error('lastname')
                    <li class="text-danger">{{ $message }}</li>
                    @enderror
                    @error('firstname')
                    <li class="text-danger">{{ $message }}</li>
                    @enderror
                    @error('region_id')
                    <li class="text-danger">{{ $message }}</li>
                    @enderror
                    @error('email')
                    <li class="text-danger">{{ $message }}</li>
                    @enderror
                    @error('phone')
                    <li class="text-danger">{{ $message }}</li>
                    @enderror
                    @error('politic')
                    <li class="text-danger">{{ $message }}</li>
                    @enderror
                </ul>
            @endif

            <input type="hidden" name="utm_source" value="{{ $utm['utm_source'] }}">
            <input type="hidden" name="utm_medium" value="{{ $utm['utm_medium'] }}">
            <input type="hidden" name="utm_campaign" value="{{ $utm['utm_campaign'] }}">
        </form>
    </div>
</main>

@if ($errors->has('smart-token'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof stepper !== 'undefined') {
                stepper.to(4);
            }
            document.querySelector('.smart-captcha')?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });
    </script>
@endif
@endsection

@push('scripts')
    <script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/i18n/ru.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/data.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <script>
        var stepper = new Stepper(document.querySelector('.bs-stepper'));

        $(document).ready(function () {
            $('[data-mask]').inputmask();

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

            $('input[name="category_main"]').on('change', function () {
                $('input[name="category_id"]').val($(this).val());
            });

            $('select[name="category_all"]').on('change', function () {
                $('input[name="category_main"]').prop('checked', false);
                $('input[name="category_id"]').val($(this).val());
            });
        });
    </script>
@endpush
