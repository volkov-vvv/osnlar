@extends('layouts.main2')

@push('styles')
    <style>
        .docs-card {
            border: 1px solid #e3e6ea;
            border-radius: 1rem;
            background: linear-gradient(135deg, #f7fbf8 0%, #ffffff 60%);
            overflow: hidden;
        }
        .docs-card .card-body {
            padding: 1.5rem;
        }
        .docs-card .card-body.border-top {
            border-top-color: #e3e6ea !important;
        }
        .docs-section-toggle {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            margin: 0;
            padding: 1.1rem 1.25rem;
            border: 0;
            background: transparent;
            text-align: left;
            cursor: pointer;
        }
        .docs-section-toggle:hover,
        .docs-section-toggle:focus {
            outline: none;
            background: rgba(9, 76, 161, 0.04);
        }
        .docs-section-toggle-main {
            display: flex;
            align-items: center;
            flex: 1 1 auto;
            min-width: 0;
            gap: 0.75rem;
        }
        .docs-section-title {
            margin: 0;
            color: #094ca1;
            font-size: 1.15rem;
            font-weight: 700;
        }
        .docs-section-toggle-main .docs-status {
            margin-left: auto;
        }
        .docs-section-chevron {
            flex: 0 0 auto;
            color: #094ca1;
            transition: transform 0.2s ease;
        }
        .docs-section-toggle[aria-expanded="true"] .docs-section-chevron {
            transform: rotate(180deg);
        }
        .docs-table th {
            width: 40%;
            color: #3d444b;
            font-weight: 600;
            background: #f3fbf5;
        }
        .docs-file-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .docs-file-link img {
            width: 22px;
            height: 22px;
        }
        .docs-hint {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .docs-status {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 0.35rem;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .docs-status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .docs-status-approved {
            background: #d4edda;
            color: #155724;
        }
        .docs-status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        .docs-status-empty {
            background: #e9ecef;
            color: #6c757d;
        }
        .docs-card .custom-file-label::after,
        .docs-card .custom-file-input:lang(en) ~ .custom-file-label::after {
            content: "\f07c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            width: 3rem;
            padding-left: 0;
            padding-right: 0;
            text-align: center;
        }
        .docs-card .btn-submit-check {
            background-color: #094ca1;
            border-color: #094ca1;
            color: #fff;
        }
        .docs-card .btn-submit-check:hover,
        .docs-card .btn-submit-check:focus {
            background-color: #073a7a;
            border-color: #073a7a;
            color: #fff;
        }
        .docs-table .form-control {
            border-color: #ced4da;
            box-shadow: none;
        }
        .docs-table .form-control:focus {
            border-color: #adb5bd;
            box-shadow: 0 0 0 0.2rem rgba(173, 181, 189, 0.35);
        }
        .docs-table .form-control:invalid,
        .docs-table .form-control:user-invalid {
            border-color: #ced4da;
            box-shadow: none;
        }
        .docs-table .form-control.is-invalid,
        .docs-table .form-control.is-invalid:focus,
        .docs-table .form-control.is-invalid:invalid,
        .docs-table .form-control.is-invalid:user-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.2);
        }
        .docs-card-locked {
            background: linear-gradient(135deg, #f1f3f5 0%, #e9ecef 70%);
            border-color: #dee2e6;
        }
        .docs-card-locked .docs-section-toggle {
            cursor: default;
        }
        .docs-card-locked .docs-section-toggle:hover,
        .docs-card-locked .docs-section-toggle:focus {
            background: transparent;
        }
        .docs-card-locked .docs-section-title {
            color: #6c757d;
        }
        .docs-card-locked .docs-section-chevron {
            color: #adb5bd;
        }
        .docs-locked-hint {
            display: inline-block;
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
            margin-left: auto;
            white-space: normal;
            text-align: right;
            line-height: 1.3;
        }
    </style>
@endpush

@section('content')
    <div class="row pt-5 pb-4">
        <div class="col text-center">
            <h1>Документы</h1>
        </div>
    </div>

    <main class="blog">
        <div class="container pb-5">
            @if(session('success') || session('error') || ! $bioLid)
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @unless($bioLid)
                            <div class="alert alert-info">
                                К вашей учётной записи пока не привязана заявка по БИО. После привязки здесь появятся данные и загрузка документов.
                            </div>
                        @endunless
                    </div>
                </div>
            @endif

            @if($bioLid)
                @php
                    $dataStatusClass = match($bioLid->data_review_status) {
                        'approved' => 'docs-status-approved',
                        'rejected' => 'docs-status-rejected',
                        'pending' => 'docs-status-pending',
                        default => 'docs-status-empty',
                    };
                @endphp

                <div class="row justify-content-center mb-3">
                    <div class="col-lg-10">
                        <div class="card docs-card">
                            <button
                                class="docs-section-toggle"
                                type="button"
                                data-toggle="collapse"
                                data-target="#docs-collapse-data"
                                aria-expanded="false"
                                aria-controls="docs-collapse-data"
                            >
                                <span class="docs-section-toggle-main">
                                    <span class="docs-section-title">1. Личные данные</span>
                                    <span class="docs-status {{ $dataStatusClass }}">{{ $bioLid->dataReviewStatusLabel() }}</span>
                                </span>
                                <i class="fas fa-chevron-down docs-section-chevron" aria-hidden="true"></i>
                            </button>
                            <div id="docs-collapse-data" class="collapse">
                                <div class="card-body border-top">
                                    <p class="text-muted mb-3">
                                        Проверьте сведения из заявки
                                        @if($bioLid->course)
                                            на курс «{{ $bioLid->course->title }}»
                                        @endif
                                        и отправьте их на проверку. Также заполните адрес, паспортные данные и образование, если они не указаны.
                                    </p>

                                    <div class="table-responsive mb-3">
                                        <table class="table table-bordered docs-table mb-0">
                                            <tbody>
                                            <tr>
                                                <th>Номер заявки</th>
                                                <td>{{ $bioLid->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>Фамилия</th>
                                                <td>{{ $bioLid->lastname }}</td>
                                            </tr>
                                            <tr>
                                                <th>Имя</th>
                                                <td>{{ $bioLid->firstname }}</td>
                                            </tr>
                                            <tr>
                                                <th>Отчество</th>
                                                <td>{{ $bioLid->middlename ?: '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Дата рождения</th>
                                                <td>{{ $bioLid->birth_date ? $bioLid->birth_date->format('d.m.Y') : '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Пол</th>
                                                <td>{{ $bioLid->genderLabel() ?? '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th>СНИЛС</th>
                                                <td>{{ $bioLid->snils ?: '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Гражданство</th>
                                                <td>{{ $bioLid->citizenship ?: '—' }}</td>
                                            </tr>
                                            @php
                                                $canEditData = ! $bioLid->isDataApproved() && ! $bioLid->isDataPendingReview();
                                                $passportSeries = old('passport_series', $bioLid->passport_series);
                                                $passportSeriesDigits = preg_replace('/\D+/', '', (string) $passportSeries);
                                                if (strlen($passportSeriesDigits) === 4) {
                                                    $passportSeries = substr($passportSeriesDigits, 0, 2).' '.substr($passportSeriesDigits, 2, 2);
                                                }
                                                $passportDepartmentCode = old('passport_department_code', $bioLid->passport_department_code);
                                                $passportDepartmentDigits = preg_replace('/\D+/', '', (string) $passportDepartmentCode);
                                                if (strlen($passportDepartmentDigits) === 6) {
                                                    $passportDepartmentCode = substr($passportDepartmentDigits, 0, 3).'–'.substr($passportDepartmentDigits, 3, 3);
                                                }
                                            @endphp
                                            <tr>
                                                <th>Адрес места жительства</th>
                                                <td>
                                                    @if($canEditData)
                                                        <textarea
                                                            form="docs-submit-data-form"
                                                            name="residence_address"
                                                            class="form-control @error('residence_address') is-invalid @enderror"
                                                            rows="2"
                                                            required
                                                        >{{ old('residence_address', $bioLid->residence_address) }}</textarea>
                                                        @error('residence_address')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $bioLid->residence_address ?: '—' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Серия / номер паспорта</th>
                                                <td>
                                                    @if($canEditData)
                                                        <div class="form-row">
                                                            <div class="col-sm-5 mb-2 mb-sm-0">
                                                                <input
                                                                    form="docs-submit-data-form"
                                                                    name="passport_series"
                                                                    type="text"
                                                                    class="form-control @error('passport_series') is-invalid @enderror"
                                                                    inputmode="numeric"
                                                                    maxlength="5"
                                                                    placeholder="00 00"
                                                                    value="{{ $passportSeries }}"
                                                                    required
                                                                >
                                                                @error('passport_series')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-sm-7">
                                                                <input
                                                                    form="docs-submit-data-form"
                                                                    name="passport_number"
                                                                    type="text"
                                                                    class="form-control @error('passport_number') is-invalid @enderror"
                                                                    inputmode="numeric"
                                                                    maxlength="6"
                                                                    placeholder="000000"
                                                                    value="{{ old('passport_number', $bioLid->passport_number) }}"
                                                                    required
                                                                >
                                                                @error('passport_number')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @elseif($bioLid->passport_series || $bioLid->passport_number)
                                                        {{ trim(($bioLid->passport_series ?: '').' '.$bioLid->passport_number) }}
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Кем выдан</th>
                                                <td>
                                                    @if($canEditData)
                                                        <input
                                                            form="docs-submit-data-form"
                                                            name="passport_issued_by"
                                                            type="text"
                                                            class="form-control @error('passport_issued_by') is-invalid @enderror"
                                                            value="{{ old('passport_issued_by', $bioLid->passport_issued_by) }}"
                                                            required
                                                        >
                                                        @error('passport_issued_by')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $bioLid->passport_issued_by ?: '—' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Дата выдачи</th>
                                                <td>
                                                    @if($canEditData)
                                                        <input
                                                            form="docs-submit-data-form"
                                                            name="passport_issued_at"
                                                            type="date"
                                                            class="form-control @error('passport_issued_at') is-invalid @enderror"
                                                            value="{{ old('passport_issued_at', optional($bioLid->passport_issued_at)->format('Y-m-d')) }}"
                                                            required
                                                        >
                                                        @error('passport_issued_at')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $bioLid->passport_issued_at ? $bioLid->passport_issued_at->format('d.m.Y') : '—' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Код подразделения</th>
                                                <td>
                                                    @if($canEditData)
                                                        <input
                                                            form="docs-submit-data-form"
                                                            name="passport_department_code"
                                                            type="text"
                                                            class="form-control @error('passport_department_code') is-invalid @enderror"
                                                            inputmode="numeric"
                                                            maxlength="7"
                                                            placeholder="000–000"
                                                            value="{{ $passportDepartmentCode }}"
                                                            required
                                                        >
                                                        @error('passport_department_code')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $bioLid->passport_department_code ?: '—' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Образование</th>
                                                <td>
                                                    @if($canEditData)
                                                        <textarea
                                                            form="docs-submit-data-form"
                                                            name="education"
                                                            class="form-control @error('education') is-invalid @enderror"
                                                            rows="3"
                                                            required
                                                        >{{ old('education', $bioLid->education) }}</textarea>
                                                        @error('education')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $bioLid->education ?: '—' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Образовательная организация</th>
                                                <td>
                                                    @if($canEditData)
                                                        <input
                                                            form="docs-submit-data-form"
                                                            name="education_organization"
                                                            type="text"
                                                            class="form-control @error('education_organization') is-invalid @enderror"
                                                            value="{{ old('education_organization', $bioLid->education_organization) }}"
                                                            required
                                                        >
                                                        @error('education_organization')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $bioLid->education_organization ?: '—' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Полученная квалификация</th>
                                                <td>
                                                    @if($canEditData)
                                                        <input
                                                            form="docs-submit-data-form"
                                                            name="education_qualification"
                                                            type="text"
                                                            class="form-control @error('education_qualification') is-invalid @enderror"
                                                            value="{{ old('education_qualification', $bioLid->education_qualification) }}"
                                                            required
                                                        >
                                                        @error('education_qualification')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $bioLid->education_qualification ?: '—' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Серия и номер диплома</th>
                                                <td>
                                                    @if($canEditData)
                                                        <input
                                                            form="docs-submit-data-form"
                                                            name="diploma_series_number"
                                                            type="text"
                                                            class="form-control @error('diploma_series_number') is-invalid @enderror"
                                                            value="{{ old('diploma_series_number', $bioLid->diploma_series_number) }}"
                                                            required
                                                        >
                                                        @error('diploma_series_number')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $bioLid->diploma_series_number ?: '—' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Дата выдачи диплома</th>
                                                <td>
                                                    @if($canEditData)
                                                        <input
                                                            form="docs-submit-data-form"
                                                            name="diploma_issued_at"
                                                            type="date"
                                                            class="form-control @error('diploma_issued_at') is-invalid @enderror"
                                                            value="{{ old('diploma_issued_at', optional($bioLid->diploma_issued_at)->format('Y-m-d')) }}"
                                                            required
                                                        >
                                                        @error('diploma_issued_at')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $bioLid->diploma_issued_at ? $bioLid->diploma_issued_at->format('d.m.Y') : '—' }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Организация</th>
                                                <td>{{ $bioLid->organization_title ?: '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th>ИНН организации</th>
                                                <td>{{ $bioLid->organization_inn ?: '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Должность</th>
                                                <td>{{ $bioLid->position ?: '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $bioLid->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Телефон</th>
                                                <td>{{ $bioLid->phoneFormatted() ?: $bioLid->phoneDisplay() }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($bioLid->isDataRejected())
                                        <div class="alert alert-danger">
                                            @if($bioLid->data_reviewed_at)
                                                <div><strong>Дата отклонения:</strong> {{ $bioLid->data_reviewed_at->format('d.m.Y H:i') }}</div>
                                            @endif
                                            @if($bioLid->data_review_comment)
                                                <div class="{{ $bioLid->data_reviewed_at ? 'mt-1' : '' }}">
                                                    <strong>Причина отклонения:</strong> {{ $bioLid->data_review_comment }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if($bioLid->isDataApproved())
                                        <div class="alert alert-success mb-0">
                                            Данные проверены
                                            @if($bioLid->data_reviewed_at)
                                                {{ $bioLid->data_reviewed_at->format('d.m.Y H:i') }}
                                            @endif
                                        </div>
                                    @elseif($bioLid->isDataPendingReview())
                                        <div class="alert alert-warning mb-0">
                                            Данные на проверке
                                            @if($bioLid->data_submitted_at)
                                                с {{ $bioLid->data_submitted_at->format('d.m.Y H:i') }}
                                            @endif
                                        </div>
                                    @else
                                        <p class="docs-hint mb-3">Заполните адрес, паспортные данные, сведения об образовании и дипломе, проверьте заявку и отправьте на проверку.</p>
                                        <form
                                            id="docs-submit-data-form"
                                            method="post"
                                            action="{{ route('user.documents.submit-data') }}"
                                            onsubmit="return confirm('Отправить данные заявки на проверку?');"
                                        >
                                            @csrf
                                            <button type="submit" class="btn btn-submit-check">Отправить на проверку</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @php $docStep = 1; @endphp
                @foreach($needDocs as $key => $needDoc)
                    @php
                        $docStep++;
                        $doc = $docs[$key] ?? null;
                        $docStatus = $doc['review_status'] ?? null;
                        $docStatusClass = match($docStatus) {
                            'approved' => 'docs-status-approved',
                            'rejected' => 'docs-status-rejected',
                            'pending' => 'docs-status-pending',
                            default => 'docs-status-empty',
                        };
                        $docStatusLabel = $doc['status_label'] ?? 'Не отправлено';
                        $canSubmitDoc = ! $doc || ! empty($doc['is_rejected']) || (empty($doc['is_pending']) && empty($doc['is_approved']));
                        $collapseId = 'docs-collapse-'.$key;
                    @endphp
                    <div class="row justify-content-center mb-3" id="doc-{{ $key }}">
                        <div class="col-lg-10">
                            <div class="card docs-card">
                                <button
                                    class="docs-section-toggle"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#{{ $collapseId }}"
                                    aria-expanded="false"
                                    aria-controls="{{ $collapseId }}"
                                >
                                    <span class="docs-section-toggle-main">
                                        <span class="docs-section-title">{{ $docStep }}. {{ $needDoc['title'] }}</span>
                                        <span class="docs-status {{ $docStatusClass }}">{{ $docStatusLabel }}</span>
                                    </span>
                                    <i class="fas fa-chevron-down docs-section-chevron" aria-hidden="true"></i>
                                </button>
                                <div id="{{ $collapseId }}" class="collapse">
                                    <div class="card-body border-top">
                                        <p class="docs-hint mb-3">Допустимые форматы: PDF, JPG, PNG. Размер файла — до 5 МБ.</p>

                                        @if(!empty($doc['file']))
                                            <div class="mb-3">
                                                <a class="docs-file-link" href="{{ url('storage/'.$doc['file']) }}" target="_blank" rel="noopener">
                                                    @if(file_exists(public_path('assets/images/'.$doc['ext'].'.svg')))
                                                        <img src="{{ asset('assets/images/'.$doc['ext'].'.svg') }}" alt="">
                                                    @else
                                                        <i class="fas fa-file"></i>
                                                    @endif
                                                    {{ $doc['title'] }}
                                                </a>
                                            </div>
                                        @endif

                                        @if(!empty($doc['is_rejected']))
                                            <div class="alert alert-danger">
                                                @if(!empty($doc['reviewed_at']))
                                                    <div><strong>Дата отклонения:</strong> {{ $doc['reviewed_at']->format('d.m.Y H:i') }}</div>
                                                @endif
                                                @if(!empty($doc['review_comment']))
                                                    <div class="{{ !empty($doc['reviewed_at']) ? 'mt-1' : '' }}">
                                                        <strong>Причина отклонения:</strong> {{ $doc['review_comment'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if(!empty($doc['is_approved']))
                                            <div class="alert alert-success mb-0">
                                                Документ проверен
                                                @if(!empty($doc['reviewed_at']))
                                                    {{ $doc['reviewed_at']->format('d.m.Y H:i') }}
                                                @endif
                                            </div>
                                        @elseif(!empty($doc['is_pending']))
                                            <div class="alert alert-warning mb-0">
                                                Документ на проверке
                                                @if(!empty($doc['submitted_at']))
                                                    с {{ $doc['submitted_at']->format('d.m.Y H:i') }}
                                                @endif
                                            </div>
                                        @elseif($canSubmitDoc)
                                            <form method="post" action="{{ route('user.documents.submit-document') }}" enctype="multipart/form-data" onsubmit="return confirm('Отправить документ на проверку?');">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $key }}">

                                                <div class="mb-3 form-group">
                                                    <div class="custom-file">
                                                        <input
                                                            type="file"
                                                            class="custom-file-input @error('doc') is-invalid @enderror"
                                                            id="doc_{{ $key }}"
                                                            name="doc"
                                                            accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                                            required
                                                        >
                                                        <label class="custom-file-label" for="doc_{{ $key }}">
                                                            {{ !empty($doc['is_rejected']) ? 'Загрузите новый файл' : 'Выберите файл' }}
                                                        </label>
                                                    </div>
                                                    @error('doc')
                                                        @if(old('type') === $key)
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @endif
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-submit-check">Отправить на проверку</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @php
                    $docStep++;
                    $initialStepsApproved = $bioLid->areInitialDocumentStepsApproved();
                    $applicationStatuses = collect($applicationDocs)->map(function ($needDoc, $key) use ($docs) {
                        $status = $docs[$key]['review_status'] ?? null;
                        if ($status === null && ! empty($needDoc['optional'])) {
                            return 'skip';
                        }

                        return $status;
                    })->reject(fn ($status) => $status === 'skip');
                    if ($applicationStatuses->contains('rejected')) {
                        $applicationStatusClass = 'docs-status-rejected';
                        $applicationStatusLabel = 'Есть отклонения';
                    } elseif ($applicationStatuses->isNotEmpty() && $applicationStatuses->every(fn ($status) => $status === 'approved')) {
                        $applicationStatusClass = 'docs-status-approved';
                        $applicationStatusLabel = 'Проверено';
                    } elseif ($applicationStatuses->contains('pending') || $applicationStatuses->contains('approved')) {
                        $applicationStatusClass = 'docs-status-pending';
                        $applicationStatusLabel = 'На проверке';
                    } else {
                        $applicationStatusClass = 'docs-status-empty';
                        $applicationStatusLabel = 'Не отправлено';
                    }
                @endphp
                <div class="row justify-content-center mb-3" id="doc-application">
                    <div class="col-lg-10">
                        <div class="card docs-card {{ $initialStepsApproved ? '' : 'docs-card-locked' }}">
                            <button
                                class="docs-section-toggle"
                                type="button"
                                @if($initialStepsApproved)
                                    data-toggle="collapse"
                                    data-target="#docs-collapse-application"
                                    aria-expanded="false"
                                    aria-controls="docs-collapse-application"
                                @else
                                    aria-disabled="true"
                                    disabled
                                @endif
                            >
                                <span class="docs-section-toggle-main">
                                    <span class="docs-section-title">{{ $docStep }}. Заявление</span>
                                    @if($initialStepsApproved)
                                        <span class="docs-status {{ $applicationStatusClass }}">{{ $applicationStatusLabel }}</span>
                                    @else
                                        <span class="docs-locked-hint">Откроется после проверки шагов 1–5</span>
                                    @endif
                                </span>
                                @if($initialStepsApproved)
                                    <i class="fas fa-chevron-down docs-section-chevron" aria-hidden="true"></i>
                                @endif
                            </button>
                            @if($initialStepsApproved)
                            <div id="docs-collapse-application" class="collapse">
                                <div class="card-body border-top">
                                    <div class="alert alert-info mb-4">
                                        Скачайте заявление — в нём уже заполнены ФИО, адрес, паспортные данные, сведения о себе, курс, часы и сроки обучения.
                                        Распечатайте документ, при необходимости дополните оставшиеся поля от руки, подпишите и загрузите скан или фото.
                                        Если диплом выдан на другую фамилию, дополнительно загрузите копию документа о смене фамилии.
                                    </div>
                                    <p class="mb-4">
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('user.documents.application-template') }}">
                                            <i class="fas fa-download mr-1"></i> Скачать заявление
                                        </a>
                                    </p>

                                    @php
                                        $applicationCanSubmitAny = false;
                                        foreach ($applicationDocs as $key => $needDoc) {
                                            $doc = $docs[$key] ?? null;
                                            if (! $doc || ! empty($doc['is_rejected']) || (empty($doc['is_pending']) && empty($doc['is_approved']))) {
                                                $applicationCanSubmitAny = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if($applicationCanSubmitAny)
                                        <form method="post" action="{{ route('user.documents.submit-group') }}" enctype="multipart/form-data" onsubmit="return confirm('Отправить документы шага на проверку?');">
                                            @csrf
                                            <input type="hidden" name="group" value="application">
                                    @endif

                                    @foreach($applicationDocs as $key => $needDoc)
                                        @php
                                            $doc = $docs[$key] ?? null;
                                            $canSubmitDoc = ! $doc || ! empty($doc['is_rejected']) || (empty($doc['is_pending']) && empty($doc['is_approved']));
                                        @endphp
                                        <div class="mb-4 pb-3 {{ ! $loop->last ? 'border-bottom' : '' }}" id="doc-{{ $key }}">
                                            <h5 class="mb-2">
                                                {{ $needDoc['title'] }}
                                                @if(!empty($needDoc['optional']))
                                                    <span class="text-muted font-weight-normal">(при необходимости)</span>
                                                @endif
                                            </h5>
                                            @if(!empty($needDoc['hint']))
                                                <p class="docs-hint mb-2">{{ $needDoc['hint'] }}</p>
                                            @endif
                                            <p class="docs-hint mb-3">Допустимые форматы: PDF, JPG, PNG. Размер файла — до 5 МБ.</p>

                                            @if(!empty($doc['file']))
                                                <div class="mb-3">
                                                    <a class="docs-file-link" href="{{ url('storage/'.$doc['file']) }}" target="_blank" rel="noopener">
                                                        @if(file_exists(public_path('assets/images/'.$doc['ext'].'.svg')))
                                                            <img src="{{ asset('assets/images/'.$doc['ext'].'.svg') }}" alt="">
                                                        @else
                                                            <i class="fas fa-file"></i>
                                                        @endif
                                                        {{ $doc['title'] }}
                                                    </a>
                                                    <span class="docs-status {{ match($doc['review_status'] ?? null) {
                                                        'approved' => 'docs-status-approved',
                                                        'rejected' => 'docs-status-rejected',
                                                        'pending' => 'docs-status-pending',
                                                        default => 'docs-status-empty',
                                                    } }} ml-2">{{ $doc['status_label'] ?? 'Не отправлено' }}</span>
                                                </div>
                                            @endif

                                            @if(!empty($doc['is_rejected']))
                                                <div class="alert alert-danger">
                                                    @if(!empty($doc['reviewed_at']))
                                                        <div><strong>Дата отклонения:</strong> {{ $doc['reviewed_at']->format('d.m.Y H:i') }}</div>
                                                    @endif
                                                    @if(!empty($doc['review_comment']))
                                                        <div class="{{ !empty($doc['reviewed_at']) ? 'mt-1' : '' }}">
                                                            <strong>Причина отклонения:</strong> {{ $doc['review_comment'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(!empty($doc['is_approved']))
                                                <div class="alert alert-success mb-0">
                                                    Документ проверен
                                                    @if(!empty($doc['reviewed_at']))
                                                        {{ $doc['reviewed_at']->format('d.m.Y H:i') }}
                                                    @endif
                                                </div>
                                            @elseif(!empty($doc['is_pending']))
                                                <div class="alert alert-warning mb-0">
                                                    Документ на проверке
                                                    @if(!empty($doc['submitted_at']))
                                                        с {{ $doc['submitted_at']->format('d.m.Y H:i') }}
                                                    @endif
                                                </div>
                                            @elseif($canSubmitDoc)
                                                <div class="mb-3 form-group">
                                                    <div class="custom-file">
                                                        <input
                                                            type="file"
                                                            class="custom-file-input @error('docs.'.$key) is-invalid @enderror"
                                                            id="doc_{{ $key }}"
                                                            name="docs[{{ $key }}]"
                                                            accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                                            @if(empty($needDoc['optional'])) required @endif
                                                        >
                                                        <label class="custom-file-label" for="doc_{{ $key }}">
                                                            {{ !empty($doc['is_rejected']) ? 'Загрузите новый файл' : 'Выберите файл' }}
                                                        </label>
                                                    </div>
                                                    @error('docs.'.$key)
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    @if($applicationCanSubmitAny)
                                            <button type="submit" class="btn btn-submit-check">Отправить на проверку</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @php
                    $docStep++;
                    $consentStatuses = collect($consentDocs)->map(function ($needDoc, $key) use ($docs) {
                        return $docs[$key]['review_status'] ?? null;
                    });
                    if ($consentStatuses->contains('rejected')) {
                        $consentStatus = 'rejected';
                        $consentStatusLabel = 'Есть отклонения';
                        $consentStatusClass = 'docs-status-rejected';
                    } elseif ($consentStatuses->every(fn ($status) => $status === 'approved')) {
                        $consentStatus = 'approved';
                        $consentStatusLabel = 'Проверено';
                        $consentStatusClass = 'docs-status-approved';
                    } elseif ($consentStatuses->contains('pending') || $consentStatuses->contains('approved')) {
                        $consentStatus = 'pending';
                        $consentStatusLabel = 'На проверке';
                        $consentStatusClass = 'docs-status-pending';
                    } else {
                        $consentStatus = null;
                        $consentStatusLabel = 'Не отправлено';
                        $consentStatusClass = 'docs-status-empty';
                    }
                @endphp
                <div class="row justify-content-center mb-3" id="doc-consents">
                    <div class="col-lg-10">
                        <div class="card docs-card {{ $initialStepsApproved ? '' : 'docs-card-locked' }}">
                            <button
                                class="docs-section-toggle"
                                type="button"
                                @if($initialStepsApproved)
                                    data-toggle="collapse"
                                    data-target="#docs-collapse-consents"
                                    aria-expanded="false"
                                    aria-controls="docs-collapse-consents"
                                @else
                                    aria-disabled="true"
                                    disabled
                                @endif
                            >
                                <span class="docs-section-toggle-main">
                                    <span class="docs-section-title">{{ $docStep }}. Согласия на обработку персональных данных</span>
                                    @if($initialStepsApproved)
                                        <span class="docs-status {{ $consentStatusClass }}">{{ $consentStatusLabel }}</span>
                                    @else
                                        <span class="docs-locked-hint">Откроется после проверки шагов 1–5</span>
                                    @endif
                                </span>
                                @if($initialStepsApproved)
                                    <i class="fas fa-chevron-down docs-section-chevron" aria-hidden="true"></i>
                                @endif
                            </button>
                            @if($initialStepsApproved)
                            <div id="docs-collapse-consents" class="collapse">
                                <div class="card-body border-top">
                                    <div class="alert alert-info mb-4">
                                        Скачайте оба согласия — в них уже подставлены ФИО, паспортные данные и текущая дата.
                                        Распечатайте, подпишите от руки и загрузите сканы или фото каждого документа.
                                    </div>

                                    @php
                                        $consentCanSubmitAny = false;
                                        foreach ($consentDocs as $key => $needDoc) {
                                            $doc = $docs[$key] ?? null;
                                            if (! $doc || ! empty($doc['is_rejected']) || (empty($doc['is_pending']) && empty($doc['is_approved']))) {
                                                $consentCanSubmitAny = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if($consentCanSubmitAny)
                                        <form method="post" action="{{ route('user.documents.submit-group') }}" enctype="multipart/form-data" onsubmit="return confirm('Отправить оба согласия на проверку?');">
                                            @csrf
                                            <input type="hidden" name="group" value="consents">
                                    @endif

                                    @foreach($consentDocs as $key => $needDoc)
                                        @php
                                            $doc = $docs[$key] ?? null;
                                            $canSubmitDoc = ! $doc || ! empty($doc['is_rejected']) || (empty($doc['is_pending']) && empty($doc['is_approved']));
                                        @endphp
                                        <div class="mb-4 pb-3 {{ ! $loop->last ? 'border-bottom' : '' }}" id="doc-{{ $key }}">
                                            <h5 class="mb-2">{{ $needDoc['title'] }}</h5>
                                            <p class="mb-3">
                                                <a class="btn btn-outline-primary btn-sm" href="{{ route('user.documents.consent-template', $needDoc['kind']) }}">
                                                    <i class="fas fa-download mr-1"></i> Скачать {{ $needDoc['title'] }}
                                                </a>
                                            </p>
                                            <p class="docs-hint mb-3">Допустимые форматы: PDF, JPG, PNG. Размер файла — до 5 МБ.</p>

                                            @if(!empty($doc['file']))
                                                <div class="mb-3">
                                                    <a class="docs-file-link" href="{{ url('storage/'.$doc['file']) }}" target="_blank" rel="noopener">
                                                        @if(file_exists(public_path('assets/images/'.$doc['ext'].'.svg')))
                                                            <img src="{{ asset('assets/images/'.$doc['ext'].'.svg') }}" alt="">
                                                        @else
                                                            <i class="fas fa-file"></i>
                                                        @endif
                                                        {{ $doc['title'] }}
                                                    </a>
                                                    <span class="docs-status {{ match($doc['review_status'] ?? null) {
                                                        'approved' => 'docs-status-approved',
                                                        'rejected' => 'docs-status-rejected',
                                                        'pending' => 'docs-status-pending',
                                                        default => 'docs-status-empty',
                                                    } }} ml-2">{{ $doc['status_label'] ?? 'Не отправлено' }}</span>
                                                </div>
                                            @endif

                                            @if(!empty($doc['is_rejected']))
                                                <div class="alert alert-danger">
                                                    @if(!empty($doc['reviewed_at']))
                                                        <div><strong>Дата отклонения:</strong> {{ $doc['reviewed_at']->format('d.m.Y H:i') }}</div>
                                                    @endif
                                                    @if(!empty($doc['review_comment']))
                                                        <div class="{{ !empty($doc['reviewed_at']) ? 'mt-1' : '' }}">
                                                            <strong>Причина отклонения:</strong> {{ $doc['review_comment'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(!empty($doc['is_approved']))
                                                <div class="alert alert-success mb-0">
                                                    Документ проверен
                                                    @if(!empty($doc['reviewed_at']))
                                                        {{ $doc['reviewed_at']->format('d.m.Y H:i') }}
                                                    @endif
                                                </div>
                                            @elseif(!empty($doc['is_pending']))
                                                <div class="alert alert-warning mb-0">
                                                    Документ на проверке
                                                    @if(!empty($doc['submitted_at']))
                                                        с {{ $doc['submitted_at']->format('d.m.Y H:i') }}
                                                    @endif
                                                </div>
                                            @elseif($canSubmitDoc)
                                                <div class="mb-3 form-group">
                                                    <div class="custom-file">
                                                        <input
                                                            type="file"
                                                            class="custom-file-input @error('docs.'.$key) is-invalid @enderror"
                                                            id="doc_{{ $key }}"
                                                            name="docs[{{ $key }}]"
                                                            accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                                            required
                                                        >
                                                        <label class="custom-file-label" for="doc_{{ $key }}">
                                                            {{ !empty($doc['is_rejected']) ? 'Загрузите новый файл' : 'Выберите файл' }}
                                                        </label>
                                                    </div>
                                                    @error('docs.'.$key)
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    @if($consentCanSubmitAny)
                                            <button type="submit" class="btn btn-submit-check">Отправить на проверку</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            input.addEventListener('change', function () {
                var fileName = (this.value || '').split('\\').pop();
                var label = this.nextElementSibling;
                if (label) {
                    label.textContent = fileName || 'Выберите файл';
                }
            });
        });

        document.querySelectorAll('[data-dismiss="alert"]').forEach(function (button) {
            button.addEventListener('click', function () {
                var alert = this.closest('.alert');
                if (alert) {
                    alert.remove();
                }
            });
        });

        @if(session('open_section') || $errors->has('docs.consent_arpm') || $errors->has('docs.consent_osnovanie') || $errors->has('docs.application') || $errors->has('docs.name_change_doc'))
            (function () {
                var section = @json(session('open_section'));
                if (!section) {
                    if (@json($errors->has('docs.consent_arpm') || $errors->has('docs.consent_osnovanie'))) {
                        section = 'consents';
                    } else {
                        section = 'application';
                    }
                }
                var collapse = document.getElementById('docs-collapse-' + section);
                if (collapse && window.jQuery) {
                    window.jQuery(collapse).collapse('show');
                    var anchor = document.getElementById('doc-' + (section === 'consents' ? 'consents' : 'application'));
                    if (anchor) {
                        anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            })();
        @endif
    </script>
@endpush
