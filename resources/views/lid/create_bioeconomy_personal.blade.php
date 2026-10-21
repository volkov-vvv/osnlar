@extends('layouts.main2')

@push('styles')
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
        .bio-personal-form .form-control {
            border-color: #dee2e6;
        }
        .bio-personal-form .form-control:focus {
            border-color: #ced4da;
            box-shadow: 0 0 0 0.2rem rgba(206, 212, 218, 0.35);
        }
        .bio-personal-form select.form-control {
            padding-top: 0;
            padding-bottom: 0;
            padding-right: 2.25rem;
            line-height: calc(1.5em + 0.75rem);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23495057' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 8px 10px;
        }
        .bio-personal-form .iti {
            width: 100%;
            display: block;
        }
        .bio-personal-form .iti .iti__tel-input,
        .bio-personal-form .iti input[type="tel"] {
            width: 100%;
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

            <div class="row">
                <div class="col-md-8">
                    <label class="form-label"><span class="text-danger">* </span>Полное наименование организации</label>
                    <select name="org_id" id="org_id" class="form-control mb-3" required>
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
                <label class="form-label"><span class="text-danger">* </span>Гражданство</label>
                <input type="text" class="form-control" value="РФ" readonly tabindex="-1" aria-readonly="true">
                <input type="hidden" name="citizenship" value="РФ">
            </div>

            <div class="mb-3">
                <label class="form-label"><span class="text-danger">* </span>Адрес места жительства</label>
                <textarea name="residence_address" class="form-control" rows="2" placeholder="Регион, город, улица, дом, квартира" required>{{ old('residence_address') }}</textarea>
                @error('residence_address')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label"><span class="text-danger">* </span>Образование</label>
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

            <div class="mb-3">
                <label class="form-label"><span class="text-danger">* </span>Должность</label>
                <input name="position" type="text" class="form-control" value="{{ old('position') }}" required>
                @error('position')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label"><span class="text-danger">* </span>Адрес электронной почты</label>
                    <input name="email" type="email" class="form-control mb-3" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label"><span class="text-danger">* </span>Номер телефона</label>
                    <div class="mb-3">
                        <input name="phone" id="phone" type="tel" class="form-control" value="{{ old('phone') }}" required>
                        <input type="hidden" name="phone_prefix" id="phone_prefix" value="{{ old('phone_prefix', '7') }}">
                    </div>
                    @error('phone')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-check mb-4">
                <input name="politic" class="form-check-input" type="checkbox" id="politicCheckbox" value="1" {{ old('politic') ? 'checked' : '' }} required>
                <label for="politicCheckbox" class="form-check-label">
                    <span class="text-danger">* </span>Я соглашаюсь с
                    <a href="{{ asset('files/politic.pdf') }}" target="_blank">политикой обработки персональных данных</a>
                </label>
                @error('politic')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <button type="submit" class="btn btn-primary">Отправить</button>
                <a class="btn btn-outline-secondary" href="{{ route('course.show', $course->id) }}">Назад</a>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
    <script src="{{ asset('js/intlTelInput/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/data.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script>
        (function () {
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
                orgSelect.addEventListener('change', syncOrganizationInn);
                syncOrganizationInn();
            }
        })();
    </script>
@endpush
