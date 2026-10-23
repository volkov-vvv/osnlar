@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $author->full_name ?: $author->name }}</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td>ID</td>
                                        <td>{{$author->id}}</td>
                                    </tr>
                                    <tr>
                                        <td>Фото</td>
                                        <td>
                                            @if($author->photo)
                                                <img src="{{ url('storage/' . $author->photo) }}" alt="Фото автора" width="160" height="160" style="object-fit: cover;" class="rounded">
                                            @endif
                                        </td>
                                    </tr>
                                <tr>
                                    <td>Фамилия</td>
                                    <td>{{ $author->lastname }}</td>
                                </tr>
                                <tr>
                                    <td>Имя</td>
                                    <td>{{ $author->firstname }}</td>
                                </tr>
                                <tr>
                                    <td>Отчество</td>
                                    <td>{{ $author->middlename }}</td>
                                </tr>
                                <tr>
                                    <td>Псевдоним</td>
                                    <td>{{$author->name}}</td>
                                </tr>
                                <tr>
                                    <td>E-mail</td>
                                    <td>{{ $author->email }}</td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>{{ $author->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Серия паспорта</td>
                                    <td>{{ $author->passport_series ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Номер паспорта</td>
                                    <td>{{ $author->passport_number ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Кем выдан</td>
                                    <td>{{ $author->passport_issued_by ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата выдачи</td>
                                    <td>{{ $author->passport_issued_at ? $author->passport_issued_at->format('d.m.Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Код подразделения</td>
                                    <td>{{ $author->passport_department_code ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>ИНН</td>
                                    <td>{{ $author->inn ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Адрес регистрации</td>
                                    <td style="white-space: pre-wrap;">{{ $author->registration_address ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Счёт №</td>
                                    <td>{{ $author->bank_account ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Банк</td>
                                    <td>{{ $author->bank_name ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>БИК</td>
                                    <td>{{ $author->bank_bik ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Корреспондентский счёт</td>
                                    <td>{{ $author->bank_correspondent_account ?: '—' }}</td>
                                </tr>

                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{$author->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{$author->updated_at}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a class="btn btn-outline-primary mr-2" href="{{route('admin.author.edit', $author->id)}}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{route('admin.author.index')}}">Назад</a>
                </div>
            </div>

                </div>
                <div class="col-6">
                <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body p-0">  
                            <table class="table table-hover">
                                <tbody>
                                    
                                <tr>
                                    
                                    <td>{!! $author->description !!}</td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                </div>
                </div>
            </div>

        

        </div><!-- /.container-fluid -->
    </section>

@endsection
