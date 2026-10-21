@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заявка по БИО №{{ $bioLid->id }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td width="40%">ID</td>
                                    <td>{{ $bioLid->id }}</td>
                                </tr>
                                <tr>
                                    <td>Курс</td>
                                    <td>{{ $bioLid->course->title ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Фамилия</td>
                                    <td>{{ $bioLid->lastname }}</td>
                                </tr>
                                <tr>
                                    <td>Имя</td>
                                    <td>{{ $bioLid->firstname }}</td>
                                </tr>
                                <tr>
                                    <td>Отчество</td>
                                    <td>{{ $bioLid->middlename ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата рождения</td>
                                    <td>{{ $bioLid->birth_date ? $bioLid->birth_date->format('d.m.Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Пол</td>
                                    <td>{{ $bioLid->genderLabel() ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td>СНИЛС</td>
                                    <td>{{ $bioLid->snils ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Гражданство</td>
                                    <td>{{ $bioLid->citizenship ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Адрес места жительства</td>
                                    <td>{{ $bioLid->residence_address ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Образование</td>
                                    <td>{{ $bioLid->education ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Полное наименование организации</td>
                                    <td>{{ $bioLid->organization_title ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>ИНН организации</td>
                                    <td>{{ $bioLid->organization_inn ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Должность</td>
                                    <td>{{ $bioLid->position ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $bioLid->email }}</td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>{{ $bioLid->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{ $bioLid->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{ $bioLid->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col">
                            <a class="btn btn-outline-secondary" href="{{ route('admin.biolid.index') }}">Назад</a>
                            <form method="post" action="{{ route('admin.biolid.destroy', $bioLid->id) }}" class="d-inline" onsubmit="return confirm('Удалить заявку?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
