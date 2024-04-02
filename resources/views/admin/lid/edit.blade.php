@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Обновление заявки: "{{$lid->firstname . ' ' . $lid->lastname}}"</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div  class="col">
                <form action="{{route('admin.lid.update', $lid->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label>Фамилия</label>
                        <input name="lastname" type="text" class="form-control" aria-describedby="Название" value="{{$lid->lastname}}">
                        @error('lastname')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Имя</label>
                        <input name="firstname" type="text" class="form-control" aria-describedby="Название" value="{{$lid->firstname}}">
                        @error('firstname')
                        <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control" value="{{$lid->email}}" disabled>
                        @error('email')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label>Ответственный</label>
                        <select name="responsible_id" class="form-control">
                            @foreach($users as $user)
                                <option value="{{$user->id}}"
                                    {{ $user->id == $lid->responsible_id ? ' selected' : '' }}
                                >{{$user->name}}</option>
                            @endforeach
                        </select>
                        @error('responsible_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label>Статус</label>
                        <select name="status_id" class="form-control">
                            @foreach($statuses as $status)
                                <option value="{{$status->id}}"
                                    {{ $status->id == $lid->status_id ? ' selected' : '' }}
                                >{{$status->title}}</option>
                            @endforeach
                        </select>
                        @error('status_id')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.lid.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection
