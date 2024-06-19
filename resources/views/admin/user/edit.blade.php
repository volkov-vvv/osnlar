@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Редактирование пользователя: "{{$user->name}}"</h1>
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
                <form action="{{route('admin.user.update', $user->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                    </div>
                    <div class="mb-3">
                        <label>Имя пользователя</label>
                        <input name="name" type="text" class="form-control" aria-describedby="Название" value="{{$user->name}}">
                        @error('name')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control" value="{{$user->email}}">
                        @error('email')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group">
                        <label>Роль пользователя</label>
                        <select name="role" id="role" class="form-control">
                            @foreach($roles as $id => $role)
                                <option value="{{$id}}"
                                    {{ $id == $user->role ? ' selected' : '' }}
                                >{{$role}}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div id="agent-form"
                        @if($user->role != 2)
                            style="display: none"
                        @endif
                    >
                        <div class="mb-3 form-group">
                            <label>Привязка к агенту</label>
                            <select name="agent_ids[]" class="select2" multiple="multiple" data-placeholder="Выберите" style="width: 100%;">
                                @foreach($agents as $agent)
                                    <option value="{{$agent->id}}"
                                        {{ is_array($user->agents->pluck('id')->toArray()) && in_array($agent->id, $user->agents->pluck('id')->toArray()) ? ' selected' : ''  }}
                                    >{{$agent->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label>utm_source</label>
                            <select name="utm[]" class="select2" multiple="multiple" data-placeholder="Выберите" style="width: 100%;">
                                @foreach($utm_sources as $utm_source)
                                    <option value="{{$utm_source}}"
                                        {{ is_array($user->utm) && in_array($utm_source, $user->utm) ? ' selected' : ''  }}
                                    >{{$utm_source}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <button type="submit" class="btn btn-primary">Обновить</button>
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
            if($('#role option:selected').val() == 2){
                $('#agent-form').show();
            }else{
                $('#agent-form').hide();
            }
        })
    </script>
@endsection
