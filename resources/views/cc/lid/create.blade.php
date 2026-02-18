@extends('cc.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Создание заявки</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div  class="col-4">
                <form action="{{route('cc.lid.store')}}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Фамилия</label>
                        <input name="lastname" type="text" class="form-control" value="{{old('lastname')}}">
                        @error('lastname')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Имя</label>
                        <input name="firstname" type="text" class="form-control" value="{{old('firstname')}}">
                        @error('firstname')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Отчество</label>
                        <input name="middlename" type="text" class="form-control" value="{{old('middlename')}}">
                        @error('middlename')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Дата рождения</label>
                        <input name="data" type="data" class="form-control" data-inputmask-alias="datetime"
                               data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric"
                               value="{{old('data')}}">
                        @error('data')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Телефон</label>
                        <input id="phone" name="phone" type="tel" class="form-control" value="{{old('phone')}}">
                        <input type="hidden" name="phone_prefix" id="phone_prefix" value="7">
                        @error('data')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Email</label>
                        <input name="email" type="email" class="form-control" value="{{old('email')}}">
                        @error('email')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Текущий уровень образования</label>

                        <select name="lid_level_edu_id" class="form-control select2">
                            @foreach($levelsedu as $leveledu)
                                <option value="{{$leveledu->id}}"
                                    {{ $leveledu->id == old('lid_level_edu_id') ? ' selected' : '' }}
                                >{{$leveledu->title}}</option>
                            @endforeach
                        </select>

                        @error('lid_level_edu_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Регион</label>
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
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Курс</label>
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
                    <div class="mb-3">
                        <label><span class="text-danger">* </span>Категория</label>
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
                    <div class="mb-3">
                        <label>Персональный агент</label>
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

                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input name="send_mail" class="custom-control-input" type="checkbox" id="customCheckbox1" value="1">
                            <label for="customCheckbox1" class="custom-control-label">Отправить письмо</label>
                        </div>
                        @error('send_mail')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group alert alert-secondary">
                        <label>Дата создания</label>
                        <input name="created_at" type="datetime-local" class="form-control" data-inputmask-alias="datetime"
                               data-inputmask-inputformat="yyyy-mm-dd hh:mm:ss" data-mask="yyyy-mm-dd hh:mm:ss" inputmode="numeric"
                               value="{{Carbon\Carbon::now()->toDateTimeString()}}">
                        @error('created_at')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group alert alert-secondary">
                        <label>Ответственный</label>
                        <select name="responsible_id" class="form-control select2">
                            <option></option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}"
                                    {{ $user->id == old('responsible_id') ? ' selected' : '' }}
                                >{{$user->name}}</option>
                            @endforeach
                        </select>
                        @error('responsible_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group alert alert-secondary">
                        <label>Статус</label>
                        <select name="status_id" class="form-control select2">
                            @foreach($statuses as $status)
                                <option value="{{$status->id}}"
                                @if(old('status_id'))
                                    {{ $status->id == old('status_id') ? ' selected' : '' }}
                                    @else
                                    {{ $status->id == 7 ? ' selected' : '' }}
                                    @endif
                                >{{$status->title}}</option>
                            @endforeach
                        </select>
                        @error('status_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="politic" value="1">


                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a class="btn btn-outline-secondary" href="{{route('cc.lid.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>

@endsection


@section('javascript')

    <!-- Phone input -->
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}">
    <script src="{{ asset('js/intlTelInput/intlTelInput.min.js')}} "></script>
    <script src="{{ asset('js/intlTelInput/data.min.js')}} "></script>

    <script>
        $(document).ready(function() {
            $('[data-mask]').inputmask();

            var input = document.querySelector("#phone");
            const iti = window.intlTelInput(input, {
                strictMode: true,
                showSelectedDialCode: true,
                nationalMode: false,
                initialCountry:"ru",
                onlyCountries: ["ru", "by"],
                i18n: {
                    // Country names
                    ru: "Россия",
                    by: "Беларусь",
                },
                hiddenInput: () => ({ phone: "phone"}),
                utilsScript: "{{ asset('js/intlTelInput/utils.js')}}?1712939239769"
            });

            input.addEventListener('countrychange', () => {
                $('#phone_prefix').val(iti.getSelectedCountryData().dialCode);
            });
        });
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2({
                language: "ru"
            })
        })


    </script>
@endsection
