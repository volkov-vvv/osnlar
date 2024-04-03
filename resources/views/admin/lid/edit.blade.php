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
                <form action="{{route('lid.update', $lid->id)}}" method="post">
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

                    <input type="hidden" name="comment" id="comment">

                    <button type="submit" id="lid-edit-submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('lid.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>


    <div class="modal fade" id="comment-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Введите комментарий</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <textarea name="comment-text" class="form-control" style="min-width: 100%"></textarea>
                    </div>
                    <div>
                        <button type="button" id="comment-submit" class="btn btn-primary">Отправить</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Закрыть</button>
                    </div>

                </div>
            </div>
        </div>
    </div>





@endsection
