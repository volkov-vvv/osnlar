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
                        <label>Имя пользователя</label>
                        <input name="name" type="text" class="form-control" aria-describedby="Иванов И.И.">
                        @error('name')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control">
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
                        <select name="role" class="form-control">
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
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.user.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection
