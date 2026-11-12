@extends('admin.layouts.main')
@section('content')
    @php
        $roleLabel = $roles[$user->role] ?? '—';
        $fullName = trim(implode(' ', array_filter([
            $user->lastname,
            $user->name,
            $user->middlename,
        ]))) ?: ($user->name ?: '—');
        $phoneDisplay = $user->phone
            ? ('+' . ($user->phone_prefix ?: '7') . ' ' . $user->phone)
            : '—';
        $isAgent = (int) $user->role === \App\Models\User::ROLE_AGENT;
        $isContact = (int) $user->role === \App\Models\User::ROLE_CONTACT;
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $fullName }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="card card-outline card-secondary h-100 mb-0">
                        <div class="card-header p-3">
                            <h3 class="card-title mb-0">Фото</h3>
                        </div>
                        <div class="card-body p-3 text-center">
                            @if($user->avatar)
                                <img
                                    src="{{ url('storage/' . $user->avatar) }}"
                                    alt="Фото"
                                    class="img-circle elevation-1"
                                    width="160"
                                    height="160"
                                    style="object-fit: cover;"
                                >
                            @else
                                <span
                                    class="d-inline-block rounded-circle"
                                    style="width: 160px; height: 160px; background-color: #d6d8db;"
                                ></span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="card card-outline card-primary h-100 mb-0">
                        <div class="card-header p-3">
                            <h3 class="card-title mb-0">Личные данные</h3>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <div class="text-muted small">Фамилия</div>
                                <div>{{ $user->lastname ?: '—' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small">Имя</div>
                                <div>{{ $user->name ?: '—' }}</div>
                            </div>
                            <div class="mb-0">
                                <div class="text-muted small">Отчество</div>
                                <div>{{ $user->middlename ?: '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="card card-outline card-info h-100 mb-0">
                        <div class="card-header p-3">
                            <h3 class="card-title mb-0">Контакты</h3>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <div class="text-muted small">Email</div>
                                <div>{{ $user->email ?: '—' }}</div>
                            </div>
                            <div class="mb-0">
                                <div class="text-muted small">Телефон</div>
                                <div>{{ $phoneDisplay }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card card-outline card-warning h-100 mb-0">
                        <div class="card-header p-3">
                            <h3 class="card-title mb-0">Доступ</h3>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <div class="text-muted small">ID</div>
                                <div>{{ $user->id }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small">Роль</div>
                                <div>{{ $roleLabel }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small">Дата создания</div>
                                <div>{{ $user->created_at }}</div>
                            </div>
                            <div class="mb-0">
                                <div class="text-muted small">Дата обновления</div>
                                <div>{{ $user->updated_at }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card card-outline card-secondary h-100 mb-0">
                        <div class="card-header p-3">
                            <h3 class="card-title mb-0">Данные роли</h3>
                        </div>
                        <div class="card-body p-3">
                            @if($isAgent)
                                <div class="mb-3">
                                    <div class="text-muted small">Привязка к агенту</div>
                                    <div>
                                        @forelse($user->agents as $agent)
                                            <span class="badge badge-secondary mr-1 mb-1">{{ $agent->title }}</span>
                                        @empty
                                            —
                                        @endforelse
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-muted small">utm_source</div>
                                    <div>
                                        @php $utmList = is_array($user->utm) ? array_filter($user->utm) : []; @endphp
                                        @forelse($utmList as $utm)
                                            <span class="badge badge-secondary mr-1 mb-1">{{ $utm }}</span>
                                        @empty
                                            —
                                        @endforelse
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div class="text-muted small mb-1">Организации (БИО)</div>
                                    <div>
                                        @forelse($user->orgs as $organization)
                                            @php
                                                $orgInn = trim((string) $organization->inn);
                                                $orgTitle = trim((string) $organization->organization_title);
                                                $orgLabel = $orgInn !== '' ? ($orgInn.' — '.$orgTitle) : ($orgTitle ?: '—');
                                            @endphp
                                            <div class="mb-1">{{ $orgLabel }}</div>
                                        @empty
                                            —
                                        @endforelse
                                    </div>
                                </div>
                            @elseif($isContact)
                                <div class="mb-0">
                                    <div class="text-muted small">Компания</div>
                                    <div>{{ optional($user->company)->title ?: '—' }}</div>
                                </div>
                            @else
                                <p class="text-muted mb-0">Для выбранной роли дополнительные поля не требуются.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card card-outline card-primary h-100 mb-0">
                        <div class="card-header p-3">
                            <h3 class="card-title mb-0">Учебные группы</h3>
                        </div>
                        <div class="card-body p-3">
                            @forelse($user->studyGroups as $studyGroup)
                                <a
                                    href="{{ route('admin.studygroup.show', $studyGroup->id) }}"
                                    class="badge badge-secondary mr-1 mb-1"
                                >{{ $studyGroup->short_title ?: $studyGroup->title }}</a>
                            @empty
                                <span class="text-muted">Учебные группы не выбраны.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                <div>
                    <a class="btn btn-primary mr-2" href="{{ route('admin.user.edit', $user->id) }}">Редактировать</a>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.user.index') }}">Назад</a>
                </div>
                <form method="post" action="{{ route('admin.user.destroy', $user->id) }}" onsubmit="return confirm('Удалить пользователя?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Удалить</button>
                </form>
            </div>
        </div>
    </section>
@endsection
