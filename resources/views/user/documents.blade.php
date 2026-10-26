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
            @else
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
                                    <span class="docs-section-title">1. Проверка данных заявки по БИО</span>
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
                                        и отправьте их на проверку.
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
                                            <tr>
                                                <th>Адрес места жительства</th>
                                                <td>{{ $bioLid->residence_address ?: '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Образование</th>
                                                <td>{{ $bioLid->education ?: '—' }}</td>
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
                                        <form method="post" action="{{ route('user.documents.submit-data') }}" onsubmit="return confirm('Отправить данные заявки на проверку?');">
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

            @endunless
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
    </script>
@endpush
