@extends('layouts.main2')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}">
    <style>
        .org-bioeconomy-form .org-bioeconomy-group {
            margin-bottom: 1.15rem;
            padding: 1.15rem 1.2rem 0.55rem;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
        }
        .org-bioeconomy-form .org-bioeconomy-group__title {
            margin: 0 0 1rem;
            color: #094ca1;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }
        .org-bioeconomy-form .form-label {
            margin-bottom: 0.45rem;
            color: #3d4a5c;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .org-bioeconomy-form .form-control,
        .org-bioeconomy-form .input-group-text {
            border: 1px solid #d5dde8;
            background-color: #f8fafc;
            color: #1f2937;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }
        .org-bioeconomy-form .form-control {
            min-height: calc(1.5em + 1rem + 2px);
            padding: 0.55rem 0.95rem;
            border-radius: 0.75rem;
            font-size: 0.975rem;
            line-height: 1.5;
        }
        .org-bioeconomy-form textarea.form-control {
            min-height: auto;
            padding-top: 0.7rem;
            padding-bottom: 0.7rem;
            resize: vertical;
        }
        .org-bioeconomy-form .form-control::placeholder {
            color: #94a3b8;
        }
        .org-bioeconomy-form .form-control:hover {
            border-color: #b7c5d8;
            background-color: #fff;
        }
        .org-bioeconomy-form .form-control:focus {
            border-color: #094ca1;
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.14);
            outline: 0;
        }
        .org-bioeconomy-form .input-group-text {
            border-radius: 0.75rem 0 0 0.75rem;
            color: #64748b;
        }
        .org-bioeconomy-form .input-group > .form-control {
            border-radius: 0 0.75rem 0.75rem 0;
        }
        .org-bioeconomy-form .input-group:focus-within .input-group-text,
        .org-bioeconomy-form .input-group:focus-within .form-control {
            border-color: #094ca1;
        }
        .org-bioeconomy-form .course-checkbox + .form-check-label {
            font-weight: 400;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        .org-bioeconomy-form .course-study-hours {
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
        .org-bioeconomy-form .course-study-hours i {
            font-size: 0.8em;
            color: #094ca1;
            opacity: 0.9;
        }
        .org-bioeconomy-form .iti {
            width: 100%;
            display: block;
        }
        .org-bioeconomy-form .input-group .iti {
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
        }
        .org-bioeconomy-form .iti .iti__tel-input,
        .org-bioeconomy-form .iti input[type="tel"] {
            width: 100%;
        }
        .org-bioeconomy-form .iti__selected-flag {
            border-radius: 0.75rem 0 0 0.75rem;
        }
        .org-bioeconomy-form .courses-label {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .org-bioeconomy-form .courses-help {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            cursor: help;
            line-height: 1;
        }
        .org-bioeconomy-form .courses-help i {
            font-size: 0.95em;
            line-height: 1;
        }
        .org-bioeconomy-form .courses-help:hover,
        .org-bioeconomy-form .courses-help:focus {
            color: #094ca1;
        }
        .org-bioeconomy-form .courses-help__tooltip {
            position: absolute;
            left: 50%;
            bottom: calc(100% + 0.45rem);
            transform: translateX(-50%);
            width: max-content;
            max-width: min(22rem, 70vw);
            padding: 0.55rem 0.75rem;
            border: 1px solid #d5dde8;
            border-radius: 0.75rem;
            background: #fff;
            color: #343a40;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.35;
            text-align: left;
            white-space: normal;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.1);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.15s ease;
            z-index: 5;
        }
        .org-bioeconomy-form .courses-help:hover .courses-help__tooltip,
        .org-bioeconomy-form .courses-help:focus .courses-help__tooltip {
            opacity: 1;
            visibility: visible;
        }
        .org-bioeconomy-form .file-input {
            display: flex;
            align-items: stretch;
            height: calc(1.5em + 1rem + 2px);
            padding: 0;
            overflow: hidden;
            border: 1px solid #d5dde8;
            border-radius: 0.75rem;
            background: #f8fafc;
            cursor: pointer;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }
        .org-bioeconomy-form .file-input:hover {
            border-color: #b7c5d8;
            background-color: #fff;
        }
        .org-bioeconomy-form .file-input:focus-within {
            border-color: #094ca1;
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.14);
        }
        .org-bioeconomy-form .file-input__button {
            display: inline-flex;
            align-items: center;
            flex: 0 0 auto;
            padding: 0 1rem;
            border: 0;
            border-right: 1px solid #d5dde8;
            background-color: #eef2f7;
            color: #334155;
            font: inherit;
            font-weight: 600;
            line-height: 1.6;
            white-space: nowrap;
        }
        .org-bioeconomy-form .file-input:hover .file-input__button {
            background-color: #e2e8f0;
        }
        .org-bioeconomy-form .file-input__name {
            display: flex;
            align-items: center;
            flex: 1 1 auto;
            min-width: 0;
            padding: 0 0.95rem;
            color: #94a3b8;
            font-size: inherit;
            line-height: 1.6;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .org-bioeconomy-form .file-input__name.is-selected {
            color: #1f2937;
        }
        .org-bioeconomy-form .file-input__native {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .org-bioeconomy-form .blank-template-link {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
        }
        .org-bioeconomy-form .blank-template-link:hover,
        .org-bioeconomy-form .blank-template-link:focus {
            color: #094ca1;
            text-decoration: underline;
        }
        .org-bioeconomy-form .blank-template-link i {
            font-size: 1.5rem;
            line-height: 1;
        }
        .org-bioeconomy-form .form-check {
            padding: 0.7rem 0.9rem 0.7rem 2.1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            background: #f8fafc;
        }
        .org-bioeconomy-form .form-check + .form-check {
            margin-top: 0.45rem;
        }
        .org-bioeconomy-form .form-check-label a {
            color: #094ca1;
            font-weight: 600;
        }
        .org-bioeconomy-form .btn-submit {
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
        .org-bioeconomy-form .btn-submit:hover,
        .org-bioeconomy-form .btn-submit:focus {
            background: linear-gradient(135deg, #073a7a 0%, #094ca1 100%);
            border-color: #063a7a;
            color: #fff;
            box-shadow: 0 10px 22px rgba(9, 76, 161, 0.26);
            transform: translateY(-1px);
        }
        .org-bioeconomy-form .btn-submit:disabled {
            background: linear-gradient(135deg, #094ca1 0%, #0b5cc0 100%);
            border-color: #063a7a;
            opacity: 0.65;
            transform: none;
            box-shadow: none;
        }
        .org-bioeconomy-form .btn-outline-secondary {
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
                <h2 class="text-center">Заявка от организации</h2>
                <p class="text-center text-muted mb-0">Национальный проект «Технологическое обеспечение биоэкономики»</p>
            </div>
        </div>

        <form class="org-bioeconomy-form" action="{{ route('org.bioeconomy.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="org-bioeconomy-group">
                <h3 class="org-bioeconomy-group__title">Организация</h3>
                <div class="row">
                    <div class="col-md-8">
                        <label class="form-label"><span class="text-danger">* </span>Краткое название организации</label>
                        <input name="organization_title" type="text" class="form-control mb-3" value="{{ old('organization_title') }}" required>
                        @error('organization_title')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">* </span>ИНН организации</label>
                        <input name="inn" type="text" class="form-control mb-3" value="{{ old('inn') }}" maxlength="12" inputmode="numeric" placeholder="10 или 12 цифр" required>
                        @error('inn')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="org-bioeconomy-group">
                <h3 class="org-bioeconomy-group__title">Ответственное лицо</h3>

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
                    <div class="col-md-6">
                        <label class="form-label"><span class="text-danger">* </span>Телефон</label>
                        <div class="mb-3">
                            <input name="phone" id="phone" type="tel" class="form-control" value="{{ old('phone') }}" required>
                            <input type="hidden" name="phone_prefix" id="phone_prefix" value="{{ old('phone_prefix', '7') }}">
                        </div>
                        @error('phone')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><span class="text-danger">* </span>Email</label>
                        <input name="email" type="email" class="form-control mb-3" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="text-danger mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 mb-2">
                    <label class="form-label" for="appointment_letter">
                        <span class="text-danger">* </span>Загрузите письмо о назначении ответственного лица за организацию обучения работников, на имя руководителя АНО «Агентство развития профессионального мастерства» федерального оператора проекта
                    </label>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <label class="file-input mb-0" for="appointment_letter">
                            <span class="file-input__button">Выберите файл</span>
                            <span class="file-input__name" id="appointment_letter_name">Файл не выбран</span>
                            <input
                                name="appointment_letter"
                                id="appointment_letter"
                                type="file"
                                class="file-input__native"
                                accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                required
                            >
                        </label>
                    </div>
                    <div class="col-md-4">
                        <a class="blank-template-link" href="{{ asset('files/БЛАНК.docx') }}" download>
                            <i class="fas fa-file-word" aria-hidden="true"></i>
                            <span>Бланк для заполнения</span>
                        </a>
                    </div>
                </div>
                <div class="text-muted small mt-1 mb-3">Допустимые форматы: PDF, JPG, PNG. Размер файла — до 5 МБ.</div>
                @error('appointment_letter')
                <div class="text-danger mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="org-bioeconomy-group">
                <h3 class="org-bioeconomy-group__title courses-label">
                    <span><span class="text-danger">* </span>Курсы</span>
                    <span class="courses-help" tabindex="0" aria-label="Подсказка по выбору курсов">
                        <i class="fas fa-question-circle" aria-hidden="true"></i>
                        <span class="courses-help__tooltip" role="tooltip">Если Вас интересует другая тематика, напишите нам в поле Дополнительный комментарий.</span>
                    </span>
                </h3>
                @if($courses->isEmpty())
                    <p class="text-muted mb-3">Сейчас нет доступных курсов по Биоэкономике.</p>
                @else
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="select_all_courses">
                        <label class="form-check-label" for="select_all_courses"><strong>Выбрать все</strong></label>
                    </div>
                    @foreach($courses as $course)
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input course-checkbox"
                                type="checkbox"
                                name="course_ids[]"
                                id="course_{{ $course->id }}"
                                value="{{ $course->id }}"
                                {{ in_array($course->id, old('course_ids', [])) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="course_{{ $course->id }}">
                                {{ $course->title }}
                                @if($course->studyHoursLabel())
                                    <span class="course-study-hours"><i class="fas fa-clock" aria-hidden="true"></i>{{ $course->studyHoursLabel() }}</span>
                                @endif
                            </label>
                        </div>
                    @endforeach
                @endif
                @error('course_ids')
                <div class="text-danger mb-2">{{ $message }}</div>
                @enderror
                @error('course_ids.*')
                <div class="text-danger mb-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="org-bioeconomy-group">
                <h3 class="org-bioeconomy-group__title">Дополнительно</h3>
                <div class="mb-3">
                    <label class="form-label">Дополнительный комментарий</label>
                    <textarea name="comment" class="form-control" rows="4" placeholder="Укажите пожелания или уточнения">{{ old('comment') }}</textarea>
                    @error('comment')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
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
                <button type="submit" class="btn btn-submit" @if($courses->isEmpty()) disabled @endif>Отправить</button>
                <a class="btn btn-outline-secondary" href="{{ route('bioeconomy.index') }}">Назад</a>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
    <script src="{{ asset('js/intlTelInput/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/data.min.js') }}"></script>
    <script>
        (function () {
            var selectAll = document.getElementById('select_all_courses');
            var checkboxes = document.querySelectorAll('.course-checkbox');
            if (selectAll && checkboxes.length) {
                function syncSelectAll() {
                    var total = checkboxes.length;
                    var checked = document.querySelectorAll('.course-checkbox:checked').length;
                    selectAll.checked = total > 0 && checked === total;
                    selectAll.indeterminate = checked > 0 && checked < total;
                }

                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(function (checkbox) {
                        checkbox.checked = selectAll.checked;
                    });
                    selectAll.indeterminate = false;
                });

                checkboxes.forEach(function (checkbox) {
                    checkbox.addEventListener('change', syncSelectAll);
                });

                syncSelectAll();
            }

            var appointmentLetter = document.getElementById('appointment_letter');
            var appointmentLetterName = document.getElementById('appointment_letter_name');
            if (appointmentLetter && appointmentLetterName) {
                appointmentLetter.addEventListener('change', function () {
                    var file = this.files && this.files[0];
                    if (file) {
                        appointmentLetterName.textContent = file.name;
                        appointmentLetterName.classList.add('is-selected');
                    } else {
                        appointmentLetterName.textContent = 'Файл не выбран';
                        appointmentLetterName.classList.remove('is-selected');
                    }
                });
            }

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
        })();
    </script>
@endpush
