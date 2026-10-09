@extends($panel . '.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Обновление заявки: "{{ $org->organization_title }}"</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-xl-6">
                <form action="{{ route($panel . '.org.update', $org->id) }}" method="post">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label>Краткое название организации</label>
                        <input name="organization_title" type="text" class="form-control" value="{{ $org->organization_title }}">
                        @error('organization_title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Полное название организации</label>
                        <input name="organization_full_title" type="text" class="form-control" value="{{ $org->organization_full_title }}">
                        @error('organization_full_title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>ИНН</label>
                        <input name="inn" type="text" class="form-control" value="{{ $org->inn }}" maxlength="12">
                        @error('inn')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Адрес</label>
                        <input name="address" type="text" class="form-control" value="{{ $org->address }}">
                        @error('address')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Телефон (из заявки)</label>
                            <input name="phone" type="text" class="form-control" value="{{ $org->phone }}">
                            @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Дополнительный телефон</label>
                            <input name="additional_phone" type="text" class="form-control" value="{{ $org->additional_phone }}">
                            @error('additional_phone')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Email (из заявки)</label>
                            <input name="email" type="email" class="form-control" value="{{ $org->email }}">
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Дополнительный email</label>
                            <input name="additional_email" type="email" class="form-control" value="{{ $org->additional_email }}">
                            @error('additional_email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Фамилия</label>
                        <input name="lastname" type="text" class="form-control" value="{{ $org->lastname }}">
                        @error('lastname')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Имя</label>
                        <input name="firstname" type="text" class="form-control" value="{{ $org->firstname }}">
                        @error('firstname')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Отчество</label>
                        <input name="middlename" type="text" class="form-control" value="{{ $org->middlename }}">
                        @error('middlename')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Комментарий заявителя</label>
                        <textarea class="form-control" rows="3" disabled>{{ $org->comment }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Выбранные курсы</label>
                        @if($org->courses->isNotEmpty())
                            <ul class="mb-0">
                                @foreach($org->courses as $course)
                                    <li>{{ $course->title }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">—</p>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label>Регион</label>
                        <select name="region_id" class="form-control select2">
                            <option value=""></option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ $region->id == $org->region_id ? 'selected' : '' }}>
                                    {{ $region->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('region_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group alert alert-secondary">
                        <label>Ответственный</label>
                        <select name="responsible_id" class="form-control select2">
                            <option value=""></option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $org->responsible_id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsible_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-group alert alert-secondary">
                        <label>Статус</label>
                        <select name="status_id" class="form-control select2">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $status->id == $org->status_id ? 'selected' : '' }}>
                                    {{ $status->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="comment" id="comment">

                    <button type="submit" id="form-submit" class="btn btn-primary">Обновить</button>
                    <a class="btn btn-outline-secondary" href="{{ route($panel . '.org.index') }}">Назад</a>
                </form>
            </div>
        </div>
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
