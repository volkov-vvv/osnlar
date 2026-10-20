@extends('layouts.main-lid')
@section('content')

    <style>
        .iti {
            width: 100%;
            display: block;
        }

        .input-group .iti {
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
        }

        .iti .iti__tel-input,
        .iti input[type="tel"] {
            width: 100%;
        }

        .org-course-option {
            display: flex;
            align-items: flex-start;
            gap: 0.55rem;
            margin-bottom: 0.75rem;
        }

        .org-course-option .form-check-input {
            margin-top: 0.35rem;
        }

        .org-course-option .form-check-label {
            font-weight: 400;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .org-course-option .course-study-hours {
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

        .org-course-option .course-study-hours i {
            font-size: 0.8em;
            color: #094ca1;
            opacity: 0.9;
        }
    </style>

    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <h2 class="text-center">Заявка на подбор программы</h2>
            </div>
        </div>

        <form action="{{route('org.store')}}" method="post">
            @csrf

            <div class="row mt mb-2">
            <div class="col">
                <label><span class="text-danger">* </span>Название Вашей организации:</label>
                <div class="input-group mb-3">
                    <input name="organization_title" type="text" class="form-control" value="{{old('organization_title')}}">
                </div>
                @error('organization_title')
                <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                @enderror
            </div>
            </div>

            <div class="row mt mb-2">
                <div class="col-md-6">
                    <div class="col">
                        <label><span class="text-danger">* </span>Фамилия:</label>
                        <div class="input-group mb-3">
                            <input name="lastname" type="text" class="form-control" value="{{old('lastname')}}">
                        </div>
                        @error('lastname')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label><span class="text-danger">* </span>Имя:</label>
                        <div class="input-group mb-3">
                            <input name="firstname" type="text" class="form-control" value="{{old('firstname')}}">
                        </div>
                        @error('firstname')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="col">
                        <label>Отчество:</label>
                        <div class="input-group mb-3">
                            <input name="middlename" type="text" class="form-control" value="{{old('middlename')}}">
                            @error('middlename')
                            <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="col">
                        <div class="form-group">
                            <label><span class="text-danger">* </span>Телефон:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input id="phone" type="tel" class="form-control"

                                       value="{{old('phone')}}"
                                >
                                <input type="hidden" name="phone_prefix" id="phone_prefix" value="7">
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <label><span class="text-danger">* </span>Email:</label>
                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input name="email" type="email" class="form-control" placeholder=""
                                   value="{{old('email')}}">
                        </div>
                        @error('email')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <label>Выберите Ваш регион</label>
                        <div class="form-group">
                            <select name="region_id" class="form-control select2">
                                @foreach($regions as $region)
                                    <option value="{{$region->id}}"
                                        {{ $region->id == old('region_id') ? ' selected' : '' }}
                                    >{{$region->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('region_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="row mt-1">
                <div class="col">
                    <label class="d-block mb-3">Выберите курс, который Вас заинтересовал</label>
                    @forelse($courses as $course)
                        <div class="form-check org-course-option">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="course_id"
                                id="course_id_{{ $course->id }}"
                                value="{{ $course->id }}"
                                {{ (string) $course->id === (string) old('course_id', $courses->first()->id) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="course_id_{{ $course->id }}">
                                {{ $course->title }}
                                @if($course->studyHoursLabel())
                                    <span class="course-study-hours"><i class="fas fa-clock" aria-hidden="true"></i>{{ $course->studyHoursLabel() }}</span>
                                @endif
                            </label>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Сейчас нет доступных курсов.</p>
                    @endforelse
                    @error('course_id')
                    <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
            </div>


            <div class="row mt-1">
                <div class="col">
                    <label>Ваш персональный агент</label>
                    <div class="form-group">
                        <select name="agent_id" class="form-control select2">
                            @foreach($agents as $agent)
                                <option value="{{$agent->id}}"
                                    {{ $agent->id == old('agent_id') ? ' selected' : '' }}
                                >{{$agent->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('agent_id')
                    <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <div class="row mt-1 mb-4">
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input name="politic" class="custom-control-input" type="checkbox" id="customCheckbox1" value="1">
                        <label for="customCheckbox1" class="custom-control-label"><span class="text-danger">* </span>Я соглашаюсь с <a href="{{asset('files/politic.pdf')}}" target="_blank">политикой обработки персональных данных</a> </label>
                    </div>
                    @error('politic')
                    <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
            </div>


            <button type="submit" class="btn btn-primary">Отправить</button>
            <a class="btn btn-outline-secondary" href="https://corporations-osnovanie.ru/">Назад</a>
        </form>


    </div>

@endsection
