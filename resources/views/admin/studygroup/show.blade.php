@extends('admin.layouts.main')
@section('content')
    <style>
        .studygroup-users-count {
            display: inline-flex;
            align-items: center;
            margin: 0 0 0 0.45rem;
            padding: 0.28rem 0.75rem;
            border: 1px solid #063a7a;
            border-radius: 999px;
            background: linear-gradient(135deg, #094ca1 0%, #0b5cc0 55%, #0a4fa8 100%);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0.01em;
            white-space: nowrap;
            vertical-align: middle;
            box-shadow: 0 4px 12px rgba(9, 76, 161, 0.28);
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $studygroup->title }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td width="40%">ID</td>
                                    <td>{{ $studygroup->id }}</td>
                                </tr>
                                <tr>
                                    <td>Полное название</td>
                                    <td>{{ $studygroup->title }}</td>
                                </tr>
                                <tr>
                                    <td>Краткое название (для АРПМ)</td>
                                    <td>{{ $studygroup->short_title ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Курс</td>
                                    <td>
                                        @if($studygroup->course)
                                            <a href="{{ route('admin.course.show', $studygroup->course->id) }}">
                                                {{ $studygroup->course->short_title ?: $studygroup->course->title }}
                                            </a>
                                            @if($studygroup->course->short_title)
                                                <span class="text-muted">— {{ $studygroup->course->title }}</span>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Описание</td>
                                    <td>{{ $studygroup->description ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата начала обучения</td>
                                    <td>{{ $studygroup->starts_at ? $studygroup->starts_at->format('d.m.Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата завершения обучения</td>
                                    <td>{{ $studygroup->ends_at ? $studygroup->ends_at->format('d.m.Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Срок обучения</td>
                                    <td>{{ $studygroup->durationLabel() ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Кол-во рабочих дней</td>
                                    <td>{{ $studygroup->workdaysLabel() ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{ $studygroup->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{ $studygroup->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header px-3 py-3">
                            <h3 class="card-title mb-0">
                                Пользователи
                                <span class="studygroup-users-count">{{ $studygroup->users->count() }}</span>
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ФИО</th>
                                    <th>Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($studygroup->users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.show', $user->id) }}">
                                                {{ trim(($user->lastname ?? '') . ' ' . ($user->name ?? '') . ' ' . ($user->middlename ?? '')) }}
                                            </a>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-muted">Пользователи не привязаны</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-3">
                    <a class="btn btn-outline-primary mr-2" href="{{ route('admin.studygroup.edit', $studygroup->id) }}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.studygroup.index') }}">Назад</a>
                </div>
                <div class="col-3">
                    <div class="d-flex justify-content-end">
                        <form method="post" action="{{ route('admin.studygroup.destroy', $studygroup->id) }}" onsubmit="return confirm('Удалить учебную группу?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
