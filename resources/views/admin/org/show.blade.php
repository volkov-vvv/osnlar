@extends($panel . '.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ trim($org->lastname . ' ' . $org->firstname . ' ' . $org->middlename) }}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6">
                    <div class="alert @switch($org->status_id) @case(1) alert-danger @break @case(2) alert-warning @break @case(3) alert-info @break @default alert-secondary @endswitch" role="alert">
                        @foreach($statuses as $status)
                            {{ $status->id == $org->status_id ? $status->title : '' }}
                        @endforeach
                    </div>

                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>Номер заявки</td>
                                    <td>{{ $org->id }}</td>
                                </tr>
                                <tr>
                                    <td>Краткое название организации</td>
                                    <td>{{ $org->organization_title }}</td>
                                </tr>
                                <tr>
                                    <td>Полное название организации</td>
                                    <td>{{ $org->organization_full_title ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>ИНН</td>
                                    <td>{{ $org->inn ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Адрес</td>
                                    <td style="white-space: normal;">{{ $org->address ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Источник</td>
                                    <td>{{ $org->source === 'bioeconomy' ? 'Биоэкономика' : ($org->source ?: '—') }}</td>
                                </tr>
                                <tr>
                                    <td>Время первой реакции</td>
                                    <td>{{ $activites->interval }}</td>
                                </tr>
                                <tr>
                                    <td>Курсы</td>
                                    <td>
                                        @if($org->courses->isNotEmpty())
                                            <ul class="mb-0 pl-3">
                                                @foreach($org->courses as $course)
                                                    <li>{{ $course->title }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            @foreach($courses as $course)
                                                {{ $course->id == $org->course_id ? $course->title : '' }}
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Комментарий</td>
                                    <td style="white-space: normal;">{{ $org->comment ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $org->email ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Дополнительный email</td>
                                    <td>{{ $org->additional_email ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>
                                        @if($org->phone)
                                            {{ $org->phone_prefix == '7' ? '8'.$org->phone : ($org->phone_prefix.$org->phone) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Дополнительный телефон</td>
                                    <td>{{ $org->additional_phone ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td>Регион</td>
                                    <td>
                                        @foreach($regions as $region)
                                            {{ $region->id == $org->region_id ? $region->title : '' }}
                                        @endforeach
                                        @if(!$org->region_id) — @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Дата создания</td>
                                    <td>{{ $org->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Дата обновления</td>
                                    <td>{{ $org->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="alert"><b>История изменений</b></div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tr>
                                    <th>Дата</th>
                                    <th>Сотрудник</th>
                                    <th>Что изменилось</th>
                                    <th>Изменение</th>
                                    <th></th>
                                </tr>
                                @forelse ($activites as $key => $activity)
                                    <tr>
                                        <td>{{ $activity->updated_at }}</td>
                                        <td>{{ $activity->user }}</td>
                                        <td>{{ $activity->description }}</td>
                                        <td>{{ $activity->status_old }} <i class="fas fa-arrow-right"></i> {{ $activity->status_new }}</td>
                                        <td>
                                            @if(!empty($activity->comment))
                                                <div class="card-header collapsed" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="true">
                                                    <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="collapse{{ $key }}">
                                        <td colspan="5">
                                            <b>Комментарий: </b>{{ $activity->comment }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">Нет изменений</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-auto">
                    <a class="btn btn-outline-primary mr-2" href="{{ route($panel . '.org.edit', $org->id) }}">Редактировать</a>
                    <a class="btn btn-outline-secondary mr-2" href="{{ route($panel . '.org.index') }}">Назад</a>
                </div>
                <div class="col-auto">
                    <form method="post" action="{{ route($panel . '.org.destroy', $org->id) }}" onsubmit="return confirm('Удалить заявку организации?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Удалить</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
