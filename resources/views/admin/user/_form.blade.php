@php
    $isEdit = $isEdit ?? false;
    $user = $user ?? null;

    $lastname = old('lastname', $isEdit ? $user->lastname : '');
    $name = old('name', $isEdit ? $user->name : '');
    $middlename = old('middlename', $isEdit ? $user->middlename : '');
    $email = old('email', $isEdit ? $user->email : '');
    $phone = old('phone', $isEdit ? $user->phone : '');
    $phonePrefix = old('phone_prefix', $isEdit ? ($user->phone_prefix ?: '7') : '7');
    $roleValue = old('role', $isEdit ? $user->role : null);
    $companyId = old('company_id', $isEdit ? optional($user->company)->id : null);

    $selectedOrgIds = collect($selectedOrgIds ?? [])
        ->map(fn ($id) => (int) $id)
        ->all();

    $selectedStudyGroupIds = collect($selectedStudyGroupIds ?? [])
        ->map(fn ($id) => (int) $id)
        ->all();

    $selectedAgentIds = old(
        'agent_ids',
        $isEdit ? $user->agents->pluck('id')->all() : []
    );

    $selectedUtm = old('utm', $isEdit ? ($user->utm ?? []) : []);
    $showAgentForm = (string) $roleValue === '2';
    $showCompanyForm = (string) $roleValue === '3';
@endphp

@if($isEdit)
    <input type="hidden" name="user_id" value="{{ $user->id }}">
@endif

<div class="row">
    @if($isEdit)
        <div class="col-lg-4 col-md-4 mb-3">
            <div class="card card-outline card-secondary h-100 mb-0">
                <div class="card-header p-3">
                    <h3 class="card-title mb-0">Фото</h3>
                </div>
                <div class="card-body p-3">
                    @include('admin.user._avatar', ['avatar' => $user->avatar])
                </div>
            </div>
        </div>
    @endif
    <div class="{{ $isEdit ? 'col-lg-4 col-md-4' : 'col-md-6' }} mb-3">
        <div class="card card-outline card-primary h-100 mb-0">
            <div class="card-header p-3">
                <h3 class="card-title mb-0">Личные данные</h3>
            </div>
            <div class="card-body p-3">
                <div class="mb-3">
                    <label for="user-lastname">Фамилия</label>
                    <input id="user-lastname" name="lastname" type="text" class="form-control" value="{{ $lastname }}">
                    @error('lastname')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="user-name">Имя</label>
                    <input id="user-name" name="name" type="text" class="form-control" value="{{ $name }}">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0">
                    <label for="user-middlename">Отчество</label>
                    <input id="user-middlename" name="middlename" type="text" class="form-control" value="{{ $middlename }}">
                    @error('middlename')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="{{ $isEdit ? 'col-lg-4 col-md-4' : 'col-md-6' }} mb-3">
        <div class="card card-outline card-info h-100 mb-0 user-contacts-card">
            <div class="card-header p-3">
                <h3 class="card-title mb-0">Контакты</h3>
            </div>
            <div class="card-body p-3">
                <div class="mb-3">
                    <label for="user-email">Email</label>
                    <input id="user-email" name="email" type="email" class="form-control w-100" value="{{ $email }}">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0">
                    <label for="phone">Телефон</label>
                    <div class="user-phone-field">
                        <input name="phone" id="phone" type="tel" class="form-control w-100" value="{{ $phone }}">
                        <input type="hidden" name="phone_prefix" id="phone_prefix" value="{{ $phonePrefix }}">
                    </div>
                    @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .user-contacts-card .user-phone-field .iti {
        display: block;
        width: 100%;
    }

    .user-contacts-card .user-phone-field .iti input.form-control {
        width: 100%;
    }
</style>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card card-outline card-warning h-100 mb-0">
            <div class="card-header p-3">
                <h3 class="card-title mb-0">Доступ</h3>
            </div>
            <div class="card-body p-3">
                <div class="mb-3">
                    <label for="user-password">Пароль</label>
                    <div class="input-group">
                        <input
                            id="user-password"
                            name="password"
                            type="password"
                            class="form-control"
                            autocomplete="new-password"
                            @if(!$isEdit) required @endif
                        >
                        <div class="input-group-append">
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="user-password-toggle"
                                title="Показать или скрыть пароль"
                                aria-label="Показать или скрыть пароль"
                            >
                                <i class="far fa-eye" id="user-password-toggle-icon"></i>
                            </button>
                            @if(!$isEdit)
                                <button
                                    type="button"
                                    class="btn btn-outline-primary"
                                    id="user-password-generate"
                                    title="Сгенерировать пароль"
                                    aria-label="Сгенерировать пароль"
                                >
                                    <i class="fas fa-key"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    @if($isEdit)
                        <div class="form-text text-muted">Оставьте пустым, чтобы не менять пароль.</div>
                    @endif
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0 form-group">
                    <label for="role">Роль пользователя</label>
                    <select name="role" id="role" class="form-control">
                        @foreach($roles as $id => $role)
                            <option value="{{ $id }}" {{ (string) $id === (string) $roleValue ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card card-outline card-secondary h-100 mb-0" id="role-data-card">
            <div class="card-header p-3">
                <h3 class="card-title mb-0">Данные роли</h3>
            </div>
            <div class="card-body p-3">
                <div id="agent-form" @if(!$showAgentForm) style="display: none" @endif>
                    @if($isEdit)
                        <div class="row">
                            <div class="col-md-6 mb-3 form-group">
                                <label>Привязка к агенту</label>
                                <select name="agent_ids[]" class="select2" multiple="multiple" data-placeholder="Выберите" style="width: 100%;">
                                    @foreach($agents as $agent)
                                        <option
                                            value="{{ $agent->id }}"
                                            {{ in_array($agent->id, $selectedAgentIds) ? 'selected' : '' }}
                                        >{{ $agent->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 form-group">
                                <label>utm_source</label>
                                <select name="utm[]" class="select2" multiple="multiple" data-allow-clear="true" data-placeholder="Выберите" style="width: 100%;">
                                    @foreach($utm_sources as $utm_source)
                                        @if(!empty($utm_source))
                                            <option
                                                value="{{ $utm_source }}"
                                                {{ is_array($selectedUtm) && in_array($utm_source, $selectedUtm) ? 'selected' : '' }}
                                            >{{ $utm_source }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @include('admin.user._agent_orgs_picker', [
                        'organizations' => $organizations,
                        'selectedOrgIds' => $selectedOrgIds,
                    ])
                </div>

                <div id="company-form" @if(!$showCompanyForm) style="display: none" @endif>
                    <div class="mb-0 form-group">
                        <label>Компания</label>
                        <select name="company_id" class="select2" data-placeholder="Выберите" style="width: 100%;">
                            <option value="" {{ empty($companyId) ? 'selected' : '' }}>---</option>
                            @foreach($companies as $company)
                                <option
                                    value="{{ $company->id }}"
                                    {{ (string) $companyId === (string) $company->id ? 'selected' : '' }}
                                >{{ $company->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <p id="role-data-empty" class="text-muted mb-0" @if($showAgentForm || $showCompanyForm) style="display: none" @endif>
                    Для выбранной роли дополнительные поля не требуются.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card card-outline card-primary h-100 mb-0">
            <div class="card-header p-3">
                <h3 class="card-title mb-0">Учебные группы</h3>
            </div>
            <div class="card-body p-3">
                @include('admin.user._study_groups_picker', [
                    'selectedStudyGroupIds' => $selectedStudyGroupIds,
                ])
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Обновить' : 'Создать' }}</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.user.index') }}">Назад</a>
</div>
