@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Редактирование агента: "{{$agent->title}}"</h1>
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
                <form action="{{route('admin.agent.update', $agent->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <div class="form-group">
                            <label>ФИО агента</label>
                            <input name="title" type="text" class="form-control" aria-describedby="Название" value="{{$agent->title}}">
                            @error('title')
                            <div class="text-danger">Это поле необходимо для заполнения "{{$message}}"</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="active" value="0">
                                <input name="active" type="checkbox" class="custom-control-input" id="customSwitch2" value="1"
                                    {{ $agent->active == 1 ? ' checked' : '' }}
                                >
                                <label class="custom-control-label" for="customSwitch2">Активность</label>
                            </div>
                            @error('active')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{route('admin.agent.index')}}">Назад</a>
                </form>
            </div>


            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>









@endsection
