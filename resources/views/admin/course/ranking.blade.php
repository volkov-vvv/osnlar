@extends('admin.layouts.main')
@section('content')
    <style>
        #course-ranking-list .course-ranking-item {
            cursor: grab;
            background: #fff;
        }
        #course-ranking-list .course-ranking-item.ui-sortable-helper {
            cursor: grabbing;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.15);
        }
        #course-ranking-list .course-ranking-handle {
            color: #6c757d;
            margin-right: 0.75rem;
        }
        #course-ranking-list .course-ranking-position {
            min-width: 2rem;
            display: inline-block;
            color: #6c757d;
            font-variant-numeric: tabular-nums;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Порядок в каталоге</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header py-3 px-3">
                    <form method="get" action="{{ route('admin.course.ranking') }}" class="form-inline flex-wrap align-items-center">
                        <label class="mr-2 mb-0" for="year">Год</label>
                        <select id="year" name="year" class="form-control form-control-sm mr-3 mb-0" onchange="this.form.submit()">
                            <option value="all" @selected($selectedYear === null)>Все годы</option>
                            @include('partials.year-options', [
                                'yearOptions' => $years,
                                'selectedYear' => $selectedYear,
                            ])
                        </select>

                        <label class="mr-2 mb-0" for="catalog">Каталог</label>
                        <select id="catalog" name="catalog" class="form-control form-control-sm mb-0" onchange="this.form.submit()">
                            @foreach($catalogs as $key => $label)
                                <option value="{{ $key }}" @selected($catalog === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="card-body">
                    <p class="text-muted mb-3">
                        Перетащите курсы в нужном порядке и нажмите «Сохранить». Выше в списке — выше в каталоге на сайте.
                        @if($selectedYear === null)
                            Режим «Все годы» совпадает с публичным каталогом: в одном списке курсы разных лет.
                        @endif
                    </p>

                    @if($courses->isEmpty())
                        <div class="alert alert-info mb-0">
                            Нет курсов для выбранного фильтра.
                        </div>
                    @else
                        <form method="post" action="{{ route('admin.course.ranking.update') }}" id="course-ranking-form">
                            @csrf
                            <input type="hidden" name="year" value="{{ $selectedYear ?? 'all' }}">
                            <input type="hidden" name="catalog" value="{{ $catalog }}">

                            <ul id="course-ranking-list" class="list-group mb-3">
                                @foreach($courses as $index => $course)
                                    <li class="list-group-item course-ranking-item d-flex align-items-center"
                                        data-id="{{ $course->id }}">
                                        <input type="hidden" name="ids[]" value="{{ $course->id }}">
                                        <span class="course-ranking-handle"><i class="fas fa-bars"></i></span>
                                        <span class="course-ranking-position mr-2">{{ $index + 1 }}.</span>
                                        <div class="flex-grow-1">
                                            <strong>{{ $course->short_title ?: $course->title }}</strong>
                                            @if($course->short_title && $course->short_title !== $course->title)
                                                <div class="small text-muted">{{ $course->title }}</div>
                                            @endif
                                        </div>
                                        @if($selectedYear === null && $course->years)
                                            <span class="badge badge-info ml-2">{{ $course->years }}</span>
                                        @endif
                                        <span class="badge badge-{{ $course->is_published ? 'success' : 'secondary' }} ml-2">
                                            {{ $course->is_published ? 'Опубликован' : 'Скрыт' }}
                                        </span>
                                        <span class="badge badge-light ml-2 text-muted">order: {{ $course->order }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Сохранить
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        $(function () {
            var $list = $('#course-ranking-list');
            if (!$list.length) {
                return;
            }

            function renumber() {
                $list.children('.course-ranking-item').each(function (index) {
                    $(this).find('.course-ranking-position').text((index + 1) + '.');
                });
            }

            $list.sortable({
                handle: '.course-ranking-handle',
                axis: 'y',
                update: renumber
            });
        });
    </script>
@endsection
