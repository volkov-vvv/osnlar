@extends('layouts.main2')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
    @if($lidCaptchaEnabled ?? true)
        <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
    @endif
    <style>
        .lid-create-form .lid-create-group {
            margin-bottom: 0.5rem;
            padding: 1.15rem 1.2rem 0.85rem;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
        }
        .lid-create-form .form-label {
            margin-bottom: 0.45rem;
            color: #3d4a5c;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .lid-create-form .form-control,
        .lid-create-form .input-group-text,
        .lid-create-form .select2-container--bootstrap4 .select2-selection {
            border: 1px solid #d5dde8;
            background-color: #f8fafc;
            color: #1f2937;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }
        .lid-create-form .form-control {
            min-height: calc(1.5em + 1rem + 2px);
            padding: 0.55rem 0.95rem;
            border-radius: 0.75rem;
            font-size: 0.975rem;
            line-height: 1.5;
        }
        .lid-create-form .form-control::placeholder {
            color: #94a3b8;
        }
        .lid-create-form .form-control:hover,
        .lid-create-form .select2-container--bootstrap4 .select2-selection:hover {
            border-color: #b7c5d8;
            background-color: #fff;
        }
        .lid-create-form .form-control:focus {
            border-color: #094ca1;
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.14);
            outline: 0;
        }
        .lid-create-form .input-group-text {
            border-radius: 0.75rem 0 0 0.75rem;
            color: #64748b;
        }
        .lid-create-form .input-group > .form-control {
            border-radius: 0 0.75rem 0.75rem 0;
        }
        .lid-create-form .input-group:focus-within .input-group-text,
        .lid-create-form .input-group:focus-within .form-control {
            border-color: #094ca1;
        }
        .lid-create-form .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 1rem + 2px) !important;
            padding: 0;
            border-radius: 0.75rem;
            font-size: 0.975rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 6l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 14px 14px;
        }
        .lid-create-form .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            padding-left: 0.95rem;
            padding-right: 2.4rem;
            line-height: calc(1.5em + 1rem);
            color: #1f2937;
        }
        .lid-create-form .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8;
            line-height: calc(1.5em + 1rem);
        }
        .lid-create-form .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            display: none;
        }
        .lid-create-form .select2-container--bootstrap4.select2-container--focus .select2-selection,
        .lid-create-form .select2-container--bootstrap4.select2-container--open .select2-selection {
            border-color: #094ca1;
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.14);
        }
        .lid-create-form .select2-container--bootstrap4 .select2-dropdown {
            border-color: #d5dde8;
            border-radius: 0.75rem;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.1);
            overflow: hidden;
        }
        .lid-create-form .select2-container--bootstrap4 .select2-results__option--highlighted,
        .lid-create-form .select2-container--bootstrap4 .select2-results__option--highlighted.select2-results__option[aria-selected="true"] {
            background-color: #094ca1;
        }
        .lid-create-form .bs-stepper-header {
            margin-bottom: 1.5rem;
            align-items: center;
        }
        .lid-create-form .bs-stepper .step {
            display: flex;
            align-items: center;
        }
        .lid-create-form .bs-stepper .line {
            align-self: center;
            background-color: #dee2e6;
        }
        .lid-create-form .bs-stepper .step.crossed + .line,
        .lid-create-form .bs-stepper .step.active + .line {
            background-color: #094ca1;
        }
        .lid-create-form .bs-stepper .step-trigger {
            display: inline-flex;
            align-items: center;
            padding: 10px 0;
            color: #6c757d;
            background: transparent;
            border: 0;
            box-shadow: none;
        }
        .lid-create-form .bs-stepper .step-trigger:hover,
        .lid-create-form .bs-stepper .step-trigger:focus {
            color: #495057;
            text-decoration: none;
            outline: 0;
            background: transparent;
            box-shadow: none;
        }
        .lid-create-form .bs-stepper-circle {
            background-color: #adb5bd;
            color: #fff;
        }
        .lid-create-form .bs-stepper .step.active .bs-stepper-circle,
        .lid-create-form .bs-stepper .step.crossed .bs-stepper-circle {
            background-color: #094ca1;
            color: #fff;
        }
        .lid-create-form .bs-stepper-label {
            margin-top: 0;
            margin-bottom: 0;
            color: #6c757d;
            font-weight: 600;
        }
        .lid-create-form .bs-stepper .step.active .bs-stepper-label,
        .lid-create-form .bs-stepper .step.crossed .bs-stepper-label {
            color: #094ca1;
            font-weight: 700;
        }
        .lid-create-form .bs-stepper .step.active .step-trigger,
        .lid-create-form .bs-stepper .step.crossed .step-trigger {
            color: #094ca1;
        }
        .lid-create-form .bs-stepper-content {
            padding-top: 0.5rem;
        }
        .lid-create-form .category-label {
            cursor: pointer;
            font-weight: 400;
        }
        .lid-create-form .step-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            text-align: center;
        }
        .lid-create-form .btn-primary {
            min-width: 8.5rem;
            padding: 0.65rem 1.35rem;
            border: 1px solid #063a7a;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #094ca1 0%, #0b5cc0 100%);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 8px 18px rgba(9, 76, 161, 0.2);
            transition: background 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
        }
        .lid-create-form .btn-primary:hover,
        .lid-create-form .btn-primary:focus {
            border-color: #063a7a;
            background: linear-gradient(135deg, #073a7a 0%, #094ca1 100%);
            color: #fff;
            box-shadow: 0 10px 22px rgba(9, 76, 161, 0.26);
            transform: translateY(-1px);
        }
        .lid-create-form .btn-outline-secondary {
            border-radius: 0.75rem;
            padding: 0.65rem 1.2rem;
            font-weight: 600;
        }

        .lid-create-form,
        .lid-create-form .bs-stepper {
            max-width: 100%;
        }

        .lid-create-form .bs-stepper-content,
        .lid-create-form .content {
            max-width: 100%;
            overflow-x: visible;
        }

        .lid-create-form .form-check {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.7rem 0.9rem;
            min-height: auto;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            background: #f8fafc;
        }

        .lid-create-form .form-check-input {
            position: static;
            float: none;
            flex: 0 0 auto;
            width: 1.15rem;
            height: 1.15rem;
            margin: 0;
        }

        .lid-create-form .form-check-label,
        .lid-create-form .category-label {
            overflow-wrap: anywhere;
            word-break: break-word;
            white-space: normal;
            flex: 1 1 auto;
            min-width: 0;
        }

        .lid-create-form .form-check-label a {
            color: #094ca1;
            font-weight: 600;
        }

        .lid-create-form .course-option-label {
            display: inline;
        }

        .lid-create-form .course-study-hours {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin: 0 0 0 0.55rem;
            padding: 0.22rem 0.65rem;
            border: 1px solid rgba(9, 76, 161, 0.18);
            border-radius: 999px;
            background: linear-gradient(135deg, #eaf2fb 0%, #f7fbff 100%);
            color: #094ca1;
            font-size: 0.8125rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0.01em;
            white-space: nowrap;
            vertical-align: middle;
            box-shadow: 0 2px 8px rgba(9, 76, 161, 0.08);
        }

        .lid-create-form .course-study-hours i {
            font-size: 0.8em;
            color: #094ca1;
            opacity: 0.9;
        }

        .lid-create-form .select2-container {
            width: 100% !important;
            max-width: 100%;
        }

        .lid-create-form .iti {
            width: 100%;
            display: block;
        }

        .lid-create-form .input-group .iti {
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
        }

        .lid-create-form .iti .iti__tel-input,
        .lid-create-form .iti input[type="tel"] {
            width: 100%;
        }

        .lid-create-form .iti__selected-flag {
            border-radius: 0.75rem 0 0 0.75rem;
        }

        .lid-create-form .smart-captcha,
        .lid-create-form #captcha-container {
            max-width: 100%;
            overflow: hidden;
        }

        @media (max-width: 767.98px) {
            .lid-create-form .bs-stepper-header {
                margin: 0;
                flex-wrap: nowrap;
                justify-content: space-between;
                gap: 0;
                overflow: visible;
            }

            .lid-create-form .bs-stepper .step {
                flex: 1 1 0;
                min-width: 0;
            }

            .lid-create-form .bs-stepper .line {
                flex: 0 0 12px;
                min-width: 12px;
                max-width: 16px;
            }

            .lid-create-form .bs-stepper .step-trigger {
                width: 100%;
                padding: 8px 2px;
                flex-direction: column;
                white-space: normal;
            }

            .lid-create-form .bs-stepper-label {
                display: block;
                margin: 0.15rem 0 0;
                font-size: 11px;
                font-weight: 600;
                line-height: 1.2;
                white-space: normal;
                overflow-wrap: anywhere;
                word-break: break-word;
                max-width: 100%;
            }

            .lid-create-form .bs-stepper-circle {
                margin: 0 auto;
            }

            .lid-create-form #information-part .ml-3 {
                margin-left: 0 !important;
            }

            .lid-create-form .input-group {
                flex-wrap: nowrap;
                max-width: 100%;
            }

            .lid-create-form .bs-stepper-content {
                padding-left: 4px;
                padding-right: 4px;
            }
        }

        @media (max-width: 400px) {
            .lid-create-form .bs-stepper-label {
                font-size: 10px;
            }
        }
    </style>
@endpush

@section('content')
<main class="blog">
    <div class="container pt-5" style="padding-bottom: 120px;">
        <div class="row mb-4">
            <div class="col">
                <h2 class="text-center">Заявка на обучение в 2027 году</h2>
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
                            <span class="bs-stepper-label">
                                <span class="d-none d-md-inline">Выбор курса</span>
                                <span class="d-md-none">Курс</span>
                            </span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#fio-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="fio-part" id="fio-part-trigger">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">
                                <span class="d-none d-md-inline">ФИО и регион</span>
                                <span class="d-md-none">ФИО</span>
                            </span>
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
                            <span class="bs-stepper-label">
                                <span class="d-none d-md-inline">Информация</span>
                                <span class="d-md-none">Инфо</span>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="bs-stepper-content">
                    <div id="courses-part" class="content" role="tabpanel" aria-labelledby="courses-part-trigger">
                        <div class="lid-create-group">
                            <label class="form-label d-block mb-3"><span class="text-danger">* </span>Выберите курс, на который хотите записаться</label>
                            @foreach($courses as $course)
                                @if($course->title != '---')
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" name="course_id" type="radio" id="course_id_{{ $course->id }}" value="{{ $course->id }}" {{ $course->id == $selectedCourse ? 'checked' : '' }}>
                                        <label class="form-check-label category-label course-option-label" for="course_id_{{ $course->id }}">{{ $course->title }}@if($course->studyHoursLabel()) <span class="course-study-hours"><i class="fas fa-clock" aria-hidden="true"></i>{{ $course->studyHoursLabel() }}</span>@endif</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="mt-4 mb-4 step-actions">
                            <button type="button" class="btn btn-primary" onclick="stepper.next()">Дальше</button>
                        </div>
                    </div>

                    <div id="fio-part" class="content" role="tabpanel" aria-labelledby="fio-part-trigger">
                        <div class="lid-create-group">
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
                        </div>

                        <div class="mt-4 mb-4 step-actions">
                            <button type="button" class="btn btn-outline-secondary" onclick="stepper.previous()">Назад</button>
                            <button type="button" class="btn btn-primary" onclick="stepper.next()">Дальше</button>
                        </div>
                    </div>

                    <div id="contacts-part" class="content" role="tabpanel" aria-labelledby="contacts-part-trigger">
                        <div class="lid-create-group">
                            <div class="mb-3">
                                <label class="form-label"><span class="text-danger">* </span>Телефон</label>
                                <input name="phone" id="phone" type="tel" class="form-control" value="{{ old('phone') }}">
                                <input type="hidden" name="phone_prefix" id="phone_prefix" value="{{ old('phone_prefix', '7') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><span class="text-danger">* </span>Email</label>
                                <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                            </div>

                            <div class="form-check mb-2 mt-3">
                                <input name="politic" class="form-check-input" type="checkbox" id="politicCheckbox" value="1" {{ old('politic') ? 'checked' : '' }}>
                                <label for="politicCheckbox" class="form-check-label">
                                    <span class="text-danger">* </span>Я соглашаюсь с
                                    <a href="{{ route('privacy') }}" target="_blank" rel="noopener">политикой обработки персональных данных</a>
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input name="in_project" class="form-check-input" type="checkbox" id="inProjectCheckbox" value="1" {{ old('in_project') ? 'checked' : '' }}>
                                <label for="inProjectCheckbox" class="form-check-label">Я никогда не обучался(-лась) в рамках проекта "Содействие занятости"</label>
                            </div>
                        </div>

                        <div class="mt-4 mb-4 step-actions">
                            <button type="button" class="btn btn-outline-secondary" onclick="stepper.previous()">Назад</button>
                            <button type="button" class="btn btn-primary" onclick="stepper.next()">Дальше</button>
                        </div>
                    </div>

                    <div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                        <div class="lid-create-group">
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

                                <div id="category_all" class="ml-3 mt-2">
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

                            @if($lidCaptchaEnabled ?? true)
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
                            @endif
                        </div>

                        <div class="mt-4 mb-4 step-actions">
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
        var stepper = new Stepper(document.querySelector('.bs-stepper'), {
            linear: false
        });

        @if($selectedCourse && !$errors->has('smart-token'))
            stepper.to(2);
        @endif

        $(document).ready(function () {
            $('[data-mask]').inputmask();

            $('.select2').select2({
                language: 'ru',
                theme: 'bootstrap4',
                width: '100%'
            });

            var input = document.querySelector('#phone');
            if (input && window.intlTelInput) {
                var utilsUrl = "{{ asset('js/intlTelInput/utils.js') }}?1712939239769";
                var nationalMaxByIso = { ru: 10, by: 9 };

                function enforceNationalLength(itiInstance) {
                    var iso = (itiInstance.getSelectedCountryData().iso2 || 'ru').toLowerCase();
                    var maxDigits = nationalMaxByIso[iso] || 10;
                    var digits = (input.value || '').replace(/\D/g, '');
                    if (digits.length <= maxDigits) {
                        return;
                    }
                    digits = digits.slice(0, maxDigits);
                    if (window.intlTelInputUtils) {
                        itiInstance.setNumber('+' + itiInstance.getSelectedCountryData().dialCode + digits);
                    } else {
                        input.value = digits;
                    }
                }

                function initPhoneInput() {
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
                        utilsScript: utilsUrl
                    });

                    document.getElementById('phone_prefix').value = iti.getSelectedCountryData().dialCode;

                    input.addEventListener('countrychange', function () {
                        document.getElementById('phone_prefix').value = iti.getSelectedCountryData().dialCode;
                        enforceNationalLength(iti);
                    });

                    input.addEventListener('input', function () {
                        enforceNationalLength(iti);
                    });

                    input.addEventListener('blur', function () {
                        enforceNationalLength(iti);
                    });
                }

                function whenUtilsReady(done) {
                    if (window.intlTelInputUtils) {
                        done();
                        return;
                    }

                    var loadPromise = window.intlTelInputGlobals
                        && typeof window.intlTelInputGlobals.loadUtils === 'function'
                        ? window.intlTelInputGlobals.loadUtils(utilsUrl)
                        : null;

                    if (loadPromise && typeof loadPromise.then === 'function') {
                        loadPromise.then(done).catch(done);
                        return;
                    }

                    var attempts = 0;
                    var timer = setInterval(function () {
                        attempts += 1;
                        if (window.intlTelInputUtils || attempts >= 40) {
                            clearInterval(timer);
                            done();
                        }
                    }, 50);
                }

                whenUtilsReady(initPhoneInput);
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
