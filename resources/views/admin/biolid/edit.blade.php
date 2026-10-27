@extends($panel . '.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Обновление заявки по БИО №{{ $bioLid->id }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-xl-6">
                <form action="{{ route($panel . '.biolid.update', $bioLid->id) }}" method="post">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label>Курс</label>
                        <select name="course_id" class="form-control select2">
                            @foreach($courses as $course)
                                <option
                                    value="{{ $course->id }}"
                                    {{ (string) old('course_id', $bioLid->course_id) === (string) $course->id ? 'selected' : '' }}
                                >{{ $course->title }}</option>
                            @endforeach
                        </select>
                        @error('course_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Фамилия</label>
                        <input name="lastname" type="text" class="form-control" value="{{ old('lastname', $bioLid->lastname) }}">
                        @error('lastname')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Имя</label>
                        <input name="firstname" type="text" class="form-control" value="{{ old('firstname', $bioLid->firstname) }}">
                        @error('firstname')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Отчество</label>
                        <input name="middlename" type="text" class="form-control" value="{{ old('middlename', $bioLid->middlename) }}">
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
                            value="{{ old('birth_date', optional($bioLid->birth_date)->format('Y-m-d')) }}"
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
                                    {{ old('gender', $bioLid->gender) === $value ? 'selected' : '' }}
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
                            value="{{ old('snils', $bioLid->snils) }}"
                        >
                        @error('snils')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Гражданство</label>
                        <input name="citizenship" type="text" class="form-control" value="{{ old('citizenship', $bioLid->citizenship) }}">
                        @error('citizenship')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Адрес места жительства</label>
                        <textarea name="residence_address" class="form-control" rows="2">{{ old('residence_address', $bioLid->residence_address) }}</textarea>
                        @error('residence_address')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Образование</label>
                        <textarea name="education" class="form-control" rows="3">{{ old('education', $bioLid->education) }}</textarea>
                        @error('education')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Полное наименование организации</label>
                        <input
                            name="organization_title"
                            type="text"
                            class="form-control"
                            value="{{ old('organization_title', $bioLid->organization_title) }}"
                        >
                        @error('organization_title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>ИНН организации</label>
                        <input
                            name="organization_inn"
                            type="text"
                            class="form-control"
                            maxlength="12"
                            value="{{ old('organization_inn', $bioLid->organization_inn) }}"
                        >
                        @error('organization_inn')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Должность</label>
                        <input name="position" type="text" class="form-control" value="{{ old('position', $bioLid->position) }}">
                        @error('position')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email', $bioLid->email) }}">
                        @error('email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Телефон</label>
                        <input name="phone" type="text" class="form-control" value="{{ old('phone', $bioLid->phoneDisplay()) }}">
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
                                    {{ (string) old('responsible_id', $bioLid->responsible_id) === (string) $user->id ? 'selected' : '' }}
                                >{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('responsible_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group alert alert-secondary">
                        <label>Статус</label>
                        <select name="status_id" class="form-control select2">
                            @foreach($statuses as $status)
                                <option
                                    value="{{ $status->id }}"
                                    {{ (string) old('status_id', $bioLid->status_id) === (string) $status->id ? 'selected' : '' }}
                                >{{ $status->title }}</option>
                            @endforeach
                        </select>
                        @error('status_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="comment" id="comment">

                    <div class="mb-4">
                        <button type="submit" id="form-submit" class="btn btn-primary">Обновить</button>
                        <a class="btn btn-outline-secondary" href="{{ route($panel . '.biolid.show', $bioLid->id) }}">Назад</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div class="modal fade" id="comment-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Введите комментарий</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <textarea name="comment-text" class="form-control" style="min-width: 100%"></textarea>
                    </div>
                    <div>
                        <button type="button" id="comment-submit" class="btn btn-primary">Отправить</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
