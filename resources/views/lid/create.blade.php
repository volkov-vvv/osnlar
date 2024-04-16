@extends('layouts.main-lid')
@section('content')

    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <h2 class="text-center">Заявка на обучение</h2>
                <h4 class="text-center">сделайте первый шаг к востребованной профессии!</h4>
            </div>
        </div>

        <form action="{{route('lid.store')}}" method="post">
            @csrf

            <div class="row mt mb-2">
                <div class="col-6">
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
                    <div class="col">
                        <label><span class="text-danger">* </span>Текущий уровень вашего образования</label>
                        <div class="form-group">
                            <select name="lid_level_edu_id" class="form-control select2">
                                @foreach($levelsedu as $leveledu)
                                    <option value="{{$leveledu->id}}"
                                        {{ $leveledu->id == old('lid_level_edu_id') ? ' selected' : '' }}
                                    >{{$leveledu->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('lid_level_edu_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div class="col">
                        <label><span class="text-danger">* </span>Дата рождения:</label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input name="data" type="data" class="form-control" data-inputmask-alias="datetime"
                                       data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric">
                            </div>
                            <!-- /.input group -->
                        </div>
                        @error('data')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
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
                        <label><span class="text-danger">* </span>Выберите Ваш регион</label>
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
                    <label><span class="text-danger">* </span>Выберите курс, на который хотите записаться</label>
                    <div class="form-group">
                        <select name="course_id" class="form-control select2">
                            @foreach($courses as $course)
                                <option value="{{$course->id}}"
                                    {{ $course->id == old('course_id') ? ' selected' : '' }}
                                >{{$course->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('course_id')
                    <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-1">

            </div>

            <div class="row mt-1">
                <div class="col">
                    <label><span class="text-danger">* </span>Выберите подходящую для Вас категорию</label>
                    <div class="form-group">
                        <select name="category_id" class="form-control select2">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}"
                                    {{ $category->id == old('category_id') ? ' selected' : '' }}
                                >{{$category->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id')
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
            <div class="row mt-1">
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
            <div class="row mt-1 mb-4">
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input name="in_project" class="custom-control-input" type="checkbox" id="customCheckbox2" value="1">
                        <label for="customCheckbox2" class="custom-control-label">&nbsp;&nbsp;Я никогда не обучался(-лась) в рамках проекта "Содействие занятости"</label>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">Отправить</button>
            <a class="btn btn-outline-secondary" href="{{route('main.index')}}">Назад</a>
        </form>


    </div>

@endsection
