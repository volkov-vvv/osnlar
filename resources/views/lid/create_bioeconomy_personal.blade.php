@extends('layouts.main2')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}">
    <style>
        .bio-personal-course-title {
            font-size: 1.2rem;
        }
        .bio-personal-course-title .course-study-hours {
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
        .bio-personal-course-title .course-study-hours i {
            font-size: 0.85em;
            color: #fff;
            opacity: 0.95;
        }
        .bio-personal-form .bio-personal-group {
            margin-bottom: 1.15rem;
            padding: 1.15rem 1.2rem 0.55rem;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
        }
        .bio-personal-form .bio-personal-group__title {
            margin: 0 0 1rem;
            color: #094ca1;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }
        .bio-personal-form .form-label {
            margin-bottom: 0.45rem;
            color: #3d4a5c;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .bio-personal-form .form-control,
        .bio-personal-form .select2-container--bootstrap4 .select2-selection {
            border: 1px solid #d5dde8;
            border-radius: 0.75rem;
            background-color: #f8fafc;
            color: #1f2937;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }
        .bio-personal-form .form-control {
            min-height: calc(1.5em + 1rem + 2px);
            padding: 0.55rem 0.95rem;
            font-size: 0.975rem;
            line-height: 1.5;
            max-width: 100%;
            box-sizing: border-box;
        }
        /* type=date на мобильных имеет большую intrinsic width и вылезает из колонки */
        .bio-personal-form .row > [class*="col-"] {
            min-width: 0;
        }
        .bio-personal-form input[type="date"] {
            width: 100%;
            min-width: 0;
            -webkit-appearance: none;
            appearance: none;
        }
        .bio-personal-form input[type="date"]::-webkit-date-and-time-value {
            text-align: left;
            min-height: 1.5em;
        }
        @media (max-width: 575.98px) {
            .bio-personal-form .bio-personal-group {
                padding-left: 0.85rem;
                padding-right: 0.85rem;
            }
            .bio-personal-form input[type="date"] {
                padding-left: 0.7rem;
                padding-right: 0.7rem;
                font-size: 1rem; /* iOS не зумит поле при фокусе */
            }
        }
        .bio-personal-form textarea.form-control {
            min-height: auto;
            padding-top: 0.7rem;
            padding-bottom: 0.7rem;
            resize: vertical;
        }
        .bio-personal-form .form-control::placeholder {
            color: #94a3b8;
        }
        .bio-personal-form .form-control:hover,
        .bio-personal-form .select2-container--bootstrap4 .select2-selection:hover {
            border-color: #b7c5d8;
            background-color: #fff;
        }
        .bio-personal-form .form-control:focus {
            border-color: #094ca1;
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.14);
            outline: 0;
        }
        .bio-personal-form .form-control[readonly] {
            background: #eef2f7;
            color: #64748b;
            border-color: #d5dde8;
            cursor: default;
        }
        .bio-personal-form select.form-control:not(.select2-hidden-accessible) {
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
        .bio-personal-form .select2-container {
            width: 100% !important;
            margin-bottom: 1rem;
        }
        .bio-personal-form .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 1rem + 2px) !important;
            padding: 0;
            font-size: 0.975rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 6l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 14px 14px;
        }
        .bio-personal-form .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            padding-left: 0.95rem;
            padding-right: 2.4rem;
            line-height: calc(1.5em + 1rem);
            color: #1f2937;
        }
        .bio-personal-form .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8;
            line-height: calc(1.5em + 1rem);
        }
        .bio-personal-form .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            display: none;
        }
        .bio-personal-form .select2-container--bootstrap4.select2-container--focus .select2-selection,
        .bio-personal-form .select2-container--bootstrap4.select2-container--open .select2-selection {
            border-color: #094ca1;
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.14);
        }
        .bio-personal-form .select2-container--bootstrap4 .select2-dropdown {
            border-color: #d5dde8;
            border-radius: 0.75rem;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.1);
            overflow: hidden;
        }
        .bio-personal-form .select2-container--bootstrap4 .select2-results__option--highlighted,
        .bio-personal-form .select2-container--bootstrap4 .select2-results__option--highlighted.select2-results__option[aria-selected="true"] {
            background-color: #094ca1;
        }
        .bio-personal-form .iti {
            width: 100%;
            display: block;
        }
        .bio-personal-form .iti .iti__tel-input,
        .bio-personal-form .iti input[type="tel"] {
            width: 100%;
        }
        .bio-personal-form .iti__selected-flag {
            border-radius: 0.75rem 0 0 0.75rem;
        }

        .bio-personal-form .org-name-label {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .bio-personal-form .org-name-help {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 0;
            background: transparent;
            color: #dc3545;
            line-height: 1;
            cursor: pointer;
        }
        .bio-personal-form .org-name-help:hover,
        .bio-personal-form .org-name-help:focus {
            color: #a71d2a;
            outline: none;
        }
        .bio-personal-form .org-name-help i {
            font-size: 1.05em;
            line-height: 1;
        }

        .bio-personal-form .form-check {
            padding: 0.85rem 1rem 0.85rem 2.2rem;
            border: 1px solid #d5dde8;
            border-radius: 0.75rem;
            background: #f8fafc;
        }
        .bio-personal-form .form-check-input {
            margin-top: 0.35rem;
        }
        .bio-personal-form .form-check-label a {
            color: #094ca1;
            font-weight: 600;
        }

        #orgMissingModal .modal-body p:last-child {
            margin-bottom: 0;
        }
        #orgMissingModal .bioeconomy-apply-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 14rem;
            margin: 0.75rem 0 1rem;
            padding: 0.9rem 1.75rem;
            border-radius: 0.8rem;
            background: #1f8a42;
            color: #fff;
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(31, 138, 66, 0.22);
            transition: background-color 0.15s ease, box-shadow 0.15s ease;
        }
        #orgMissingModal .bioeconomy-apply-btn i {
            margin-right: 0.6rem;
            color: #fff;
            font-size: 0.95em;
        }
        #orgMissingModal .bioeconomy-apply-btn:hover,
        #orgMissingModal .bioeconomy-apply-btn:focus {
            background: #176b33;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 8px 16px rgba(23, 107, 51, 0.28);
        }

        .bio-personal-form .btn-submit {
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
        .bio-personal-form .btn-submit:hover,
        .bio-personal-form .btn-submit:focus {
            background: linear-gradient(135deg, #073a7a 0%, #094ca1 100%);
            border-color: #063a7a;
            color: #fff;
            box-shadow: 0 10px 22px rgba(9, 76, 161, 0.26);
            transform: translateY(-1px);
        }
        .bio-personal-form .btn-outline-secondary {
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
                <h2 class="text-center">Персональная запись на курс по Биоэкономике</h2>
                <p class="text-center text-muted mb-1">Национальный проект «Технологическое обеспечение биоэкономики»</p>
                <p class="text-center mb-0 bio-personal-course-title">
                    <strong>{{ $course->title }}</strong>
                    @if($course->studyHoursLabel())
                        <span class="course-study-hours"><i class="fas fa-clock" aria-hidden="true"></i>{{ $course->studyHoursLabel() }}</span>
                    @endif
                </p>
            </div>
        </div>

        <form class="bio-personal-form" action="{{ route('lid.bioeconomy.personal.store') }}" method="post">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="citizenship" value="РФ">

            <div class="bio-personal-group">
                <h3 class="bio-personal-group__title">Личные данные</h3>

                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">* </span>Фамилия</label>
                        <input name="lastname" type="text" class="form-control mb-3" value="{{ old('lastname') }}" required>
                        @error('lastname')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">* </span>Имя</label>
                        <input name="firstname" type="text" class="form-control mb-3" value="{{ old('firstname') }}" required>
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

                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">* </span>Дата рождения</label>
                        <input name="birth_date" type="date" class="form-control mb-3" value="{{ old('birth_date') }}" required>
                        @error('birth_date')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">* </span>Пол</label>
                        <select name="gender" class="form-control mb-3" required>
                            <option value="">Выберите</option>
                            @foreach($genders as $value => $label)
                                <option value="{{ $value }}" {{ old('gender') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('gender')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">* </span>СНИЛС</label>
                        <input
                            name="snils"
                            id="snils"
                            type="text"
                            class="form-control mb-3"
                            inputmode="numeric"
                            placeholder="111-222-333 00"
                            value="{{ old('snils') }}"
                            required
                        >
                        @error('snils')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><span class="text-danger">* </span>Адрес места жительства</label>
                    <textarea name="residence_address" class="form-control" rows="2" placeholder="Регион, город, улица, дом, квартира" required>{{ old('residence_address') }}</textarea>
                    @error('residence_address')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="bio-personal-group">
                <h3 class="bio-personal-group__title">Организация</h3>

                <div class="row">
                    <div class="col-md-8">
                        <label class="form-label org-name-label" for="org_id">
                            <span><span class="text-danger">* </span>Полное наименование организации</span>
                            <button
                                type="button"
                                class="org-name-help"
                                data-toggle="modal"
                                data-target="#orgMissingModal"
                                aria-label="Информация об отсутствии организации в списке"
                            >
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                            </button>
                        </label>
                        <select name="org_id" id="org_id" class="form-control select2" required>
                            <option value="">Выберите</option>
                            @foreach($organizations as $organization)
                                <option
                                    value="{{ $organization->id }}"
                                    data-inn="{{ $organization->inn }}"
                                    {{ (string) old('org_id') === (string) $organization->id ? 'selected' : '' }}
                                >{{ $organization->organization_title }}</option>
                            @endforeach
                        </select>
                        @error('org_id')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                        @error('organization_title')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">* </span>ИНН организации</label>
                        <input
                            type="text"
                            id="organization_inn_display"
                            class="form-control mb-3"
                            value=""
                            readonly
                            tabindex="-1"
                            aria-readonly="true"
                            placeholder="Выберите организацию"
                        >
                        <input type="hidden" name="organization_inn" id="organization_inn" value="{{ old('organization_inn') }}">
                        @error('organization_inn')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><span class="text-danger">* </span>Должность</label>
                    <input name="position" type="text" class="form-control" value="{{ old('position') }}" required>
                    @error('position')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="bio-personal-group">
                <h3 class="bio-personal-group__title">Образование</h3>

                <div class="mb-3">
                    <label class="form-label"><span class="text-danger">* </span>Образование и реквизиты документа об образовании (название, серия, номер и дата выдачи)</label>
                    <textarea
                        name="education"
                        class="form-control"
                        rows="3"
                        placeholder="Высшее / среднее профессиональное; наименование и реквизиты документа об образовании (название, серия, номер и дата выдачи)"
                        required
                    >{{ old('education') }}</textarea>
                    @error('education')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="bio-personal-group">
                <h3 class="bio-personal-group__title">Контакты</h3>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label org-name-label" for="email">
                            <span><span class="text-danger">* </span>Адрес электронной почты</span>
                            <button
                                type="button"
                                class="org-name-help"
                                data-toggle="modal"
                                data-target="#emailCabinetModal"
                                aria-label="Информация об отправке пароля на электронную почту"
                            >
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                            </button>
                        </label>
                        <input name="email" id="email" type="email" class="form-control mb-3" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span class="text-danger">* </span>Номер мобильного телефона</label>
                        <div class="mb-3">
                            <input name="phone" id="phone" type="tel" class="form-control" value="{{ old('phone') }}" required>
                            <input type="hidden" name="phone_prefix" id="phone_prefix" value="{{ old('phone_prefix', '7') }}">
                        </div>
                        @error('phone')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input name="politic" class="form-check-input" type="checkbox" id="politicCheckbox" value="1" {{ old('politic') ? 'checked' : '' }} required>
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

<div class="modal fade" id="orgMissingModal" tabindex="-1" role="dialog" aria-labelledby="orgMissingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orgMissingModalLabel">Организация отсутствует в списке</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Если в списке нет Вашей организации, значит организация еще не подала официальную заявку.
                    Для подачи заявки от организации (как юридическое лицо) необходимо перейти по ссылке:
                </p>
                <a class="bioeconomy-apply-btn" href="{{ route('org.bioeconomy.create') }}">
                    <i class="fas fa-pencil-alt" aria-hidden="true"></i>Подать заявку от организации
                </a>
                <p>
                    Обращаем внимание, что необходимо назначить ответственное лицо, написать письмо по образцу, подписать и прикрепить скан-копию.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="emailCabinetModal" tabindex="-1" role="dialog" aria-labelledby="emailCabinetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailCabinetModalLabel">Электронная почта</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    Обращаем Ваше внимание, что после завершения подачи заявки на указанный Вами адрес электронной почты будет направлено автоматическое уведомление, содержащее пароль для доступа в личный кабинет. Если письмо не поступит в течение нескольких минут, пожалуйста, проверьте папку «Спам».
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/i18n/ru.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/data.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script>
        (function () {
            if (window.jQuery && jQuery.fn.select2) {
                jQuery('#org_id').select2({
                    language: 'ru',
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Начните вводить название организации',
                    allowClear: true
                });
            }

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

            if (window.jQuery && jQuery.fn.inputmask) {
                jQuery('#snils').inputmask({
                    mask: '999-999-999 99',
                    placeholder: '111-222-333 00',
                    clearIncomplete: true
                });
            }

            var orgSelect = document.querySelector('#org_id');
            var innDisplay = document.querySelector('#organization_inn_display');
            var innHidden = document.querySelector('#organization_inn');

            function syncOrganizationInn() {
                if (!orgSelect || !innDisplay || !innHidden) {
                    return;
                }

                var selected = orgSelect.options[orgSelect.selectedIndex];
                var inn = selected && selected.value ? (selected.getAttribute('data-inn') || '') : '';
                innDisplay.value = inn;
                innHidden.value = inn;
            }

            if (orgSelect) {
                if (window.jQuery) {
                    jQuery(orgSelect).on('change', syncOrganizationInn);
                } else {
                    orgSelect.addEventListener('change', syncOrganizationInn);
                }
                syncOrganizationInn();
            }
        })();
    </script>
@endpush
