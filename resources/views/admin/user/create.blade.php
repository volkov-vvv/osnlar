@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Добавление пользователя</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <button class="btn btn-outline-primary">Выход</button>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div  class="col-4">
                <form action="{{route('admin.user.store')}}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label>Фамилия</label>
                        <input name="lastname" type="text" class="form-control" aria-describedby="Фамилия" value="{{old('lastname')}}">
                        @error('lastname')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Имя</label>
                        <input name="name" type="text" class="form-control" aria-describedby="Имя" value="{{old('name')}}">
                        @error('name')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Отчество</label>
                        <input name="middlename" type="text" class="form-control" aria-describedby="Отчество" value="{{old('middlename')}}">
                        @error('middlename')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control" value="{{old('email')}}">
                        @error('email')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Пароль</label>
                        <input name="password" type="password" class="form-control">
                        @error('password')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label>Роль пользователя</label>
                        <select name="role" id="role" class="form-control">
                            @foreach($roles as $id => $role)
                                <option value="{{$id}}"
                                    {{ $id == old('role') ? ' selected' : '' }}
                                >{{$role}}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div id="company-form" style="display: none">
                        <div class="mb-3 form-group">
                            <label>Компания</label>
                            <select name="company_id" class="select2" data-placeholder="Выберите" style="width: 100%;">
                                <option disabled selected value>---</option>
                                @foreach($companies as $company)
                                    <option value="{{$company->id}}"
                                        {{ !empty(old('company_id')) && old('company_id') == $company->id ? ' selected' : ''  }}
                                    >{{$company->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.user.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection

@section('javascript')
    <script>
        $('#role').on('change', function (e){
            if($('#role option:selected').val() == 3){
                $('#company-form').show();
            }else{
                $('#company-form').hide();
            }
        })
    </script>
@endsection
