@extends('layouts.main2')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}">
    <style>
        .org-bioeconomy-form .form-control,
        .org-bioeconomy-form .input-group-text {
            border-color: #dee2e6;
        }
        .org-bioeconomy-form .form-control:focus {
            border-color: #ced4da;
            box-shadow: 0 0 0 0.2rem rgba(206, 212, 218, 0.35);
        }
        .org-bioeconomy-form .course-checkbox + .form-check-label {
            font-weight: 400;
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

        <form class="org-bioeconomy-form" action="{{ route('org.bioeconomy.store') }}" method="post">
            @csrf

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
                <div class="col-md-8">
                    <label class="form-label"><span class="text-danger">* </span>Краткое название организации</label>
                    <input name="organization_title" type="text" class="form-control mb-3" value="{{ old('organization_title') }}" required>
                    @error('organization_title')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">ИНН организации</label>
                    <input name="inn" type="text" class="form-control mb-3" value="{{ old('inn') }}" maxlength="12" inputmode="numeric" placeholder="10 или 12 цифр">
                    @error('inn')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label"><span class="text-danger">* </span>Телефон</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input name="phone" id="phone" type="tel" class="form-control" value="{{ old('phone') }}" required>
                        <input type="hidden" name="phone_prefix" id="phone_prefix" value="{{ old('phone_prefix', '7') }}">
                    </div>
                    @error('phone')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label"><span class="text-danger">* </span>Email</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-2">
                <div class="col">
                    <label class="form-label mb-2"><span class="text-danger">* </span>Выберите курсы</label>
                    @if($courses->isEmpty())
                        <p class="text-muted">Сейчас нет доступных курсов по Биоэкономике.</p>
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
                                <label class="form-check-label" for="course_{{ $course->id }}">{{ $course->title }}</label>
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
            </div>

            <div class="row mt-3">
                <div class="col">
                    <label class="form-label">Дополнительный комментарий</label>
                    <textarea name="comment" class="form-control" rows="4" placeholder="Укажите пожелания или уточнения">{{ old('comment') }}</textarea>
                    @error('comment')
                    <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-3 mb-4">
                <div class="col">
                    <div class="form-check">
                        <input name="politic" class="form-check-input" type="checkbox" id="politicCheckbox" value="1" {{ old('politic') ? 'checked' : '' }} required>
                        <label for="politicCheckbox" class="form-check-label">
                            <span class="text-danger">* </span>Я соглашаюсь с
                            <a href="{{ asset('files/politic.pdf') }}" target="_blank">политикой обработки персональных данных</a>
                        </label>
                    </div>
                    @error('politic')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-2 mb-4">
                <button type="submit" class="btn btn-primary" @if($courses->isEmpty()) disabled @endif>Отправить</button>
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
