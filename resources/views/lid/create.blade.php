@extends('layouts.main-lid')
@section('content')

    <div class="container">
        <div class="row mt-1">
            <div class="col">
                <h3>Заявка на участие в проекте</h3>
            </div>
        </div>

        <form action="{{route('lid.store')}}" method="post">
            @csrf

            <div class="row mt-1">
                <div class="col">
                    <label>Фамилия:</label>
                    <div class="input-group mb-3">
                        <input name="lastname" type="text" class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                        </div>
                    </div>
                    @error('lastname')
                    <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-1">
                <div class="col">
                    <label>Имя:</label>
                    <div class="input-group mb-3">
                        <input name="firstname" type="text" class="form-control" value="{{old('firstname')}}">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                        </div>
                    </div>
                    @error('firstname')
                    <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-1">
                <div class="col">
                    <label>Отчество:</label>
                    <div class="input-group mb-3">
                        <input name="middlename" type="text" class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                        </div>
                        @error('middlename')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col">
                    <label>Дата рождения:</label>
                    <div class="form-group">
                         <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input name="data" type="data" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric">
                        </div>
                        <!-- /.input group -->
                    </div>
                    @error('data')
                    <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-1">
                <div class="col">
                    <div class="form-group">
                        <label>Телефон:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input name="phone" type="tel" class="form-control"
                                   data-inputmask="&quot;mask&quot;: &quot;+7 (999) 999-9999&quot;" data-mask="data-mask"
                                   inputmode="text">
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>

            <div class="row mt-1">
                <div class="col">
                    <label>Email:</label>
                    <div class="input-group mb-3">

                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input name="email" type="email" class="form-control" placeholder="">
                    </div>
                    @error('email')
                    <div class="text-danger">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-1">
                <div class="col">
                    <label>Текущий уровень вашего образования (будьте готовы предоставить диплом)</label>
                    <div class="form-group">
                        <select name="lid_level_edu_id" class="form-control">
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

            <div class="row mt-1">
                <div class="col">
                    <label>Выберите курс, на который хотите записаться</label>
                    <div class="form-group">
                        <select name="course_id" class="form-control">
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

            <button type="submit" class="btn btn-primary">Создать</button>
            <a class="btn btn-outline-secondary" href="{{route('main.index')}}">Назад</a>
        </form>


    </div>

@endsection
