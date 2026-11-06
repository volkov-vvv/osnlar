@extends('layouts.main2')

@push('styles')
    <style>
        .course-page-title {
            overflow-wrap: anywhere;
            word-break: break-word;
            hyphens: auto;
            line-height: 1.25;
        }
        .course-page-lead {
            color: grey;
            font-size: 20px;
        }
        .course-page-lead .text-underline-green {
            font-style: normal;
            font-weight: inherit;
            color: inherit;
            text-decoration: underline #83d16a;
            text-decoration-thickness: 4px;
            text-underline-offset: 2px;
        }
        .course-page-partners {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .course-page-partners .course-kadry-logo {
            display: block;
            height: 130px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
        }
        .featured-posts-section .course-study-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 10px;
        }
        .featured-posts-section .course-study-form {
            margin: 0;
            color: #094ca1;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .featured-posts-section .course-study-form i {
            margin-right: 0.4rem;
            color: #094ca1;
        }
        .featured-posts-section .course-study-hours {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin: 0 0 0 auto;
            padding: 0.28rem 0.7rem;
            border: 1px solid rgba(9, 76, 161, 0.18);
            border-radius: 999px;
            background: linear-gradient(135deg, #eaf2fb 0%, #f7fbff 100%);
            color: #094ca1;
            font-size: 0.875rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0.01em;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(9, 76, 161, 0.08);
        }
        .featured-posts-section .course-study-hours i {
            font-size: 0.8em;
            color: #094ca1;
            opacity: 0.9;
        }

        .course-filters {
            margin: 0 0 2rem;
            padding: 1.15rem 1.2rem 1.25rem;
            border: 1px solid #e3e6ea;
            border-radius: 1rem;
            background: linear-gradient(160deg, #ffffff 0%, #f7fbff 100%);
            box-shadow: 0 8px 24px rgba(9, 76, 161, 0.06);
        }
        .course-filters__search {
            position: relative;
            margin-bottom: 1rem;
        }
        .course-filters__search i {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: #094ca1;
            opacity: 0.75;
            pointer-events: none;
        }
        .course-filters__search-input {
            width: 100%;
            height: 2.75rem;
            padding: 0.55rem 1rem 0.55rem 2.55rem;
            border: 1px solid #d8dee6;
            border-radius: 999px;
            background: #fff;
            color: #343a40;
            font-size: 1rem;
            line-height: 1.3;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .course-filters__search-input:focus {
            outline: 0;
            border-color: #094ca1;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.15);
        }
        .course-filters__groups {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }
        .course-filters__group {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem 0.55rem;
        }
        .course-filters__label {
            min-width: 7.5rem;
            margin: 0;
            color: #495057;
            font-size: 0.9rem;
            font-weight: 700;
        }
        .course-filters__chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            flex: 1 1 auto;
        }
        .course-filter-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2rem;
            padding: 0.3rem 0.85rem;
            border: 1px solid #d5d8dc;
            border-radius: 999px;
            background: #fff;
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 700;
            line-height: 1.2;
            cursor: pointer;
            transition: border-color 0.15s ease, background 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
        }
        .course-filter-chip:hover,
        .course-filter-chip:focus {
            color: #094ca1;
            border-color: rgba(9, 76, 161, 0.35);
            background: #f3f8fd;
            outline: 0;
        }
        .course-filter-chip.is-active {
            color: #fff;
            border-color: #094ca1;
            background: linear-gradient(135deg, #094ca1 0%, #0b5cc0 100%);
            box-shadow: 0 6px 14px rgba(9, 76, 161, 0.18);
        }
        .course-filters__meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-top: 1rem;
            padding-top: 0.85rem;
            border-top: 1px solid rgba(9, 76, 161, 0.08);
        }
        .course-filters__count {
            margin: 0;
            color: #094ca1;
            font-size: 0.95rem;
            font-weight: 700;
        }
        .course-filters__reset {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.85rem;
            border: 1px solid #d5d8dc;
            border-radius: 999px;
            background: #fff;
            color: #495057;
            font-size: 0.875rem;
            font-weight: 700;
            line-height: 1.2;
            cursor: pointer;
            transition: border-color 0.15s ease, color 0.15s ease, background 0.15s ease;
        }
        .course-filters__reset:hover,
        .course-filters__reset:focus {
            color: #094ca1;
            border-color: rgba(9, 76, 161, 0.35);
            background: #f3f8fd;
            outline: 0;
        }
        .course-filters__empty {
            display: none;
            margin: 1.5rem 0 4rem;
            padding: 1.25rem 1rem;
            border: 1px dashed rgba(9, 76, 161, 0.25);
            border-radius: 0.85rem;
            background: #f8fbff;
            color: #495057;
            text-align: center;
            font-weight: 600;
        }
        .course-filters__empty.is-visible {
            display: block;
        }
        .course-card.is-hidden {
            display: none !important;
        }

        @media (max-width: 767.98px) {
            .course-page-title {
                font-size: 1.6rem;
            }
            .course-page-lead {
                font-size: 16px;
            }
            .course-page-partners .course-kadry-logo {
                height: 85px;
            }
            .course-filters {
                padding: 1rem;
            }
            .course-filters__label {
                min-width: 100%;
            }
        }
    </style>
@endpush

@section('content')

    <main class="blog">
        <div class="container">

            <div class="row px-3 px-md-5 py-4 py-md-5" data-aos="fade-up">
                <div class="col">
                    <div class="course-page-partners" aria-label="Национальный проект «Кадры»">
                        <img class="course-kadry-logo" src="{{ asset('assets/images/кадры-thumb.jpg') }}" alt="Национальный проект «Кадры»">
                    </div>
                    <h1 class="text-center course-page-title">Каталог курсов 2027 года</h1>
                    <p class="text-center course-page-lead">
                        В рамках реализации федерального проекта «Активные меры содействия занятости» проходит предварительная запись на <em class="text-underline-green">бесплатное</em> обучение в 2027 году.
                    </p>
                </div>
            </div>

            <div class="course-filters" data-course-filters data-aos="fade-up">
                <div class="course-filters__search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input
                        type="search"
                        class="course-filters__search-input"
                        data-course-search
                        placeholder="Поиск по названию курса"
                        aria-label="Поиск по названию курса"
                        autocomplete="off"
                    >
                </div>

                <div class="course-filters__groups">
                    @if($filterHours->isNotEmpty())
                        <div class="course-filters__group" data-filter-group="hours">
                            <p class="course-filters__label">Часы</p>
                            <div class="course-filters__chips">
                                <button type="button" class="course-filter-chip is-active" data-filter="hours" data-value="">Все</button>
                                @foreach($filterHours as $hours)
                                    <button type="button" class="course-filter-chip" data-filter="hours" data-value="{{ $hours }}">{{ $hours }} ч.</button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($filterForms->isNotEmpty())
                        <div class="course-filters__group" data-filter-group="form">
                            <p class="course-filters__label">Форма</p>
                            <div class="course-filters__chips">
                                <button type="button" class="course-filter-chip is-active" data-filter="form" data-value="">Все</button>
                                @foreach($filterForms as $formValue => $formLabel)
                                    <button type="button" class="course-filter-chip" data-filter="form" data-value="{{ $formValue }}">{{ $formLabel }}</button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($filterTypes->isNotEmpty())
                        <div class="course-filters__group" data-filter-group="type">
                            <p class="course-filters__label">Тип</p>
                            <div class="course-filters__chips">
                                <button type="button" class="course-filter-chip is-active" data-filter="type" data-value="">Все</button>
                                @foreach($filterTypes as $typeValue => $typeLabel)
                                    <button type="button" class="course-filter-chip" data-filter="type" data-value="{{ $typeValue }}">{{ $typeLabel }}</button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="course-filters__meta">
                    <p class="course-filters__count">Найдено: <span data-course-count>{{ $courses->count() }}</span></p>
                    <button type="button" class="course-filters__reset" data-course-reset>
                        <i class="fas fa-undo-alt" aria-hidden="true"></i>
                        Сбросить
                    </button>
                </div>
            </div>

            <section class="featured-posts-section" data-aos="fade-up">
                <div class="row" data-course-grid>
                    @foreach($courses as $course)
                        <div
                            class="col-md-4 fetured-post blog-post course-card"
                            data-aos="fade-right"
                            data-title="{{ mb_strtolower($course->title) }}"
                            data-hours="{{ $course->study_hours !== null && $course->study_hours !== '' ? (int) $course->study_hours : '' }}"
                            data-form="{{ $course->study_form ?: '' }}"
                            data-type="{{ $course->type ?: '' }}"
                        >
                            <div class="blog-post-thumbnail-wrapper">
                                <a href="{{ route('course.show', $course->id) }}">
                                    <img src="{{ 'storage/' . $course->prev_img }}" alt="{{ $course->title }}">
                                </a>
                            </div>
                            @if($course->studyFormLabel() || $course->studyHoursLabel())
                                <div class="course-study-meta">
                                    @if($course->studyFormLabel())
                                        <p class="course-study-form"><i class="fas fa-graduation-cap" aria-hidden="true"></i>{{ $course->studyFormLabel() }}</p>
                                    @endif
                                    @if($course->studyHoursLabel())
                                        <p class="course-study-hours"><i class="fas fa-clock" aria-hidden="true"></i>{{ $course->studyHoursLabel() }}</p>
                                    @endif
                                </div>
                            @endif
                            <a href="{{ route('course.show', $course->id) }}" class="blog-post-permalink">
                                <h6 class="blog-post-title">{{ $course->title }}</h6>
                            </a>
                        </div>
                    @endforeach
                </div>
                <p class="course-filters__empty" data-course-empty>По выбранным фильтрам курсы не найдены</p>
            </section>

        </div>
    </main>

@endsection

@push('scripts')
    <script>
        (function () {
            var root = document.querySelector('[data-course-filters]');
            var grid = document.querySelector('[data-course-grid]');
            if (!root || !grid) {
                return;
            }

            var searchInput = root.querySelector('[data-course-search]');
            var countNode = root.querySelector('[data-course-count]');
            var resetButton = root.querySelector('[data-course-reset]');
            var emptyNode = document.querySelector('[data-course-empty]');
            var cards = Array.prototype.slice.call(grid.querySelectorAll('.course-card'));
            var state = {
                q: '',
                hours: '',
                form: '',
                type: ''
            };

            function normalize(value) {
                return String(value || '').trim().toLowerCase();
            }

            function readUrlState() {
                var params = new URLSearchParams(window.location.search);
                state.q = params.get('q') || '';
                state.hours = params.get('hours') || '';
                state.form = params.get('form') || '';
                state.type = params.get('type') || '';
            }

            function writeUrlState() {
                var params = new URLSearchParams();
                if (state.q) params.set('q', state.q);
                if (state.hours) params.set('hours', state.hours);
                if (state.form) params.set('form', state.form);
                if (state.type) params.set('type', state.type);

                var query = params.toString();
                var nextUrl = window.location.pathname + (query ? '?' + query : '') + window.location.hash;
                window.history.replaceState({}, '', nextUrl);
            }

            function setActiveChip(filterName, value) {
                root.querySelectorAll('[data-filter="' + filterName + '"]').forEach(function (chip) {
                    var isActive = (chip.getAttribute('data-value') || '') === value;
                    chip.classList.toggle('is-active', isActive);
                });
            }

            function syncControlsFromState() {
                if (searchInput) {
                    searchInput.value = state.q;
                }
                setActiveChip('hours', state.hours);
                setActiveChip('form', state.form);
                setActiveChip('type', state.type);
            }

            function cardMatches(card) {
                var title = card.getAttribute('data-title') || '';
                var hours = card.getAttribute('data-hours') || '';
                var form = card.getAttribute('data-form') || '';
                var type = card.getAttribute('data-type') || '';
                var query = normalize(state.q);

                if (query && title.indexOf(query) === -1) {
                    return false;
                }
                if (state.hours && hours !== String(state.hours)) {
                    return false;
                }
                if (state.form && form !== state.form) {
                    return false;
                }
                if (state.type && type !== state.type) {
                    return false;
                }

                return true;
            }

            function applyFilters() {
                var visibleCount = 0;

                cards.forEach(function (card) {
                    var visible = cardMatches(card);
                    card.classList.toggle('is-hidden', !visible);
                    if (visible) {
                        visibleCount += 1;
                    }
                });

                if (countNode) {
                    countNode.textContent = String(visibleCount);
                }
                if (emptyNode) {
                    emptyNode.classList.toggle('is-visible', visibleCount === 0);
                }

                writeUrlState();
            }

            root.addEventListener('click', function (event) {
                var chip = event.target.closest('[data-filter]');
                if (!chip || !root.contains(chip)) {
                    return;
                }

                var filterName = chip.getAttribute('data-filter');
                var value = chip.getAttribute('data-value') || '';
                if (!filterName || !(filterName in state)) {
                    return;
                }

                state[filterName] = value;
                setActiveChip(filterName, value);
                applyFilters();
            });

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    state.q = searchInput.value || '';
                    applyFilters();
                });
            }

            if (resetButton) {
                resetButton.addEventListener('click', function () {
                    state.q = '';
                    state.hours = '';
                    state.form = '';
                    state.type = '';
                    syncControlsFromState();
                    applyFilters();
                });
            }

            readUrlState();
            syncControlsFromState();
            applyFilters();
        })();
    </script>
@endpush
