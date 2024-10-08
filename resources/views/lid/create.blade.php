@extends('layouts.main-lid')
@section('content')

    <div class="container mb-5">
        <div class="row mb-3">
            <div class="col">
                <h1 class="text-center">Заявка на обучение</h1>
                <h4 class="text-center">сделайте первый шаг к востребованной профессии!</h4>
            </div>
        </div>

        <form action="{{route('lid.store')}}" method="post">
            @csrf

            <div class="row mt mb-2">
                <div class="col-lg-6">
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

                <div class="col-lg-6">
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
                        <select name="course_id" id="course_id" class="form-control select2">
                            <option></option>
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
                        @foreach($categoriesMain as $categoryMain)
                            <p>
                                <input name="category_main" type="radio" id="category_main_{{$categoryMain->id}}" value="{{$categoryMain->id}}">
                                <label for="category_main_{{$categoryMain->id}}" class="category-label">&nbsp;{{$categoryMain->title}}</label>
                            </p>
                        @endforeach

                        <div id="category_all" class="ml-3">
                            <p> Все категории:</p>
                            <select name="category_all" class="form-control select2">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}"
                                        {{ $category->id == old('category_id') ? ' selected' : '' }}
                                    >{{$category->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="category_id" value="{{old('category_id')}}">
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

            @if(isset($_GET['utm_source']))
            <div class="mb-3">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="hidden" name="utm_source" value="{{$_GET['utm_source']}}">
                        <input type="hidden" name="utm_medium" value="{{$_GET['utm_medium']}}">
                        <input type="hidden" name="utm_campaign" value="{{$_GET['utm_campaign']}}">
                    </div>
                </div>
            </div>
            @endif

            <button type="submit" class="btn btn-primary">Отправить</button>
            <a class="btn btn-outline-secondary" href="{{route('main.index')}}">Назад</a>
        </form>


    </div>

@endsection

@section('javascript')
    <script>
        $('input[name="category_main"]').on('change', function (e){
            var cat = $(this).val();
            $('input[name="category_id"]').val(cat);
        })

        $('select[name="category_all"]').on('change', function (e){
            $('input[name="category_main"]').prop('checked', false);
            var cat = $(this).val();
            $('input[name="category_id"]').val(cat);
        })

        $(document).ready(function() {
            $('#course_id').select2({
                placeholder: "Выберите курс"
            });
        });



    </script>
@endsection
