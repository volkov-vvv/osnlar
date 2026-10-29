@extends($panel . '.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Создание заявки по БИО</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-xl-6">
                <form action="{{ route($panel . '.biolid.store') }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Курс</label>
                        <select name="course_id" class="form-control select2" required>
                            <option value="">Выберите</option>
                            @foreach($courses as $course)
                                <option
                                    value="{{ $course->id }}"
                                    {{ (string) old('course_id') === (string) $course->id ? 'selected' : '' }}
                                >{{ $course->title }}</option>
                            @endforeach
                        </select>
                        @error('course_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Фамилия</label>
                        <input name="lastname" type="text" class="form-control" value="{{ old('lastname') }}" required>
                        @error('lastname')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Имя</label>
                        <input name="firstname" type="text" class="form-control" value="{{ old('firstname') }}" required>
                        @error('firstname')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Отчество</label>
                        <input name="middlename" type="text" class="form-control" value="{{ old('middlename') }}">
                        @error('middlename')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Дата рождения</label>
                        <input
                            name="birth_date"
                            type="date"
                            class="form-control"
                            value="{{ old('birth_date') }}"
                        >
                        @error('birth_date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Пол</label>
                        <select name="gender" class="form-control">
                            <option value="">Выберите</option>
                            @foreach($genders as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    {{ old('gender') === $value ? 'selected' : '' }}
                                >{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('gender')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>СНИЛС</label>
                        <input
                            name="snils"
                            type="text"
                            class="form-control"
                            inputmode="numeric"
                            placeholder="111-222-333 00"
                            value="{{ old('snils') }}"
                        >
                        @error('snils')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Гражданство</label>
                        <input name="citizenship" type="text" class="form-control" value="{{ old('citizenship', 'РФ') }}">
                        @error('citizenship')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Адрес места жительства</label>
                        <textarea name="residence_address" class="form-control" rows="2">{{ old('residence_address') }}</textarea>
                        @error('residence_address')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Образование</label>
                        <textarea name="education" class="form-control" rows="3">{{ old('education') }}</textarea>
                        @error('education')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Полное наименование организации</label>
                        <select name="org_id" id="org_id" class="form-control select2">
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
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('organization_title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>ИНН организации</label>
                        <input
                            type="text"
                            id="organization_inn_display"
                            class="form-control"
                            value=""
                            readonly
                            tabindex="-1"
                            aria-readonly="true"
                            placeholder="Выберите организацию"
                            style="background-color: #e9ecef;"
                        >
                        <input type="hidden" name="organization_inn" id="organization_inn" value="{{ old('organization_inn') }}">
                        @error('organization_inn')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Должность</label>
                        <input name="position" type="text" class="form-control" value="{{ old('position') }}">
                        @error('position')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Телефон</label>
                        <input name="phone" type="text" class="form-control" value="{{ old('phone') }}" required>
                        @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group alert alert-secondary">
                        <label>Ответственный</label>
                        <select name="responsible_id" class="form-control select2">
                            <option value=""></option>
                            @foreach($users as $user)
                                <option
                                    value="{{ $user->id }}"
                                    {{ (string) old('responsible_id') === (string) $user->id ? 'selected' : '' }}
                                >{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('responsible_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group alert alert-secondary">
                        <label><span class="text-danger">* </span>Статус</label>
                        <select name="status_id" class="form-control select2" required>
                            @foreach($statuses as $status)
                                <option
                                    value="{{ $status->id }}"
                                    {{ (string) old('status_id', 1) === (string) $status->id ? 'selected' : '' }}
                                >{{ $status->title }}</option>
                            @endforeach
                        </select>
                        @error('status_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary">Создать</button>
                        <a class="btn btn-outline-secondary" href="{{ route($panel . '.biolid.index') }}">Назад</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        (function () {
            var orgSelect = document.getElementById('org_id');
            var innDisplay = document.getElementById('organization_inn_display');
            var innHidden = document.getElementById('organization_inn');

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
@endsection
