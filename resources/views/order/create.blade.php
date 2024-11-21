@extends('layouts.main-lid')
@section('content')

    <div class="container mb-5">
        <div class="row mb-3">
            <div class="col">
                <h1 class="text-center">Заказ на обучение</h1>
                <h4 class="text-center">Курс: {{$course->title}}</h4>
            </div>
        </div>

        <form action="{{route('order.store')}}" method="post">
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
                </div>

                <div class="col-lg-6">
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
                    <div class="custom-control custom-checkbox">
                        <input name="politic" class="custom-control-input" type="checkbox" id="customCheckbox1" value="1">
                        <label for="customCheckbox1" class="custom-control-label"><span class="text-danger">* </span>Я соглашаюсь с <a href="{{asset('files/politic.pdf')}}" target="_blank">политикой обработки персональных данных</a> </label>
                    </div>
                    @error('politic')
                    <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
            </div>
            <input type="hidden" name="course_id" value="{{$course->id}}">
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

