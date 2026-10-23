@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1>Обновление курса "{{$course->title}}"</h1>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mb-2">
            <form action="{{route('admin.course.update', $course->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-6">
                    <div class="row mb-3">
                        <div class="col-3">
                            <div class="form-group mb-0">
                                <label>Компания</label>
                                <select name="company_id" class="select2" data-placeholder="Выберите" style="width: 100%;">
                                    @foreach($companies as $company)
                                        <option
                                            @if(!empty($course->company_id) && $company->id == $course->company_id)
                                            selected
                                            @endif
                                            value="{{$company->id}}">{{$company->title}}</option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mb-0">
                                <label for="type">Тип</label>
                                <select name="type" id="type" class="form-control select2" style="width: 100%;" data-placeholder="Не выбран">
                                    <option value="">Не выбран</option>
                                    @foreach(\App\Models\Course::TYPES as $value => $label)
                                        <option value="{{ $value }}" {{ old('type', $course->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mb-0">
                                <label>Серия</label>
                                <select name="series" class="form-control select2" style="width: 100%;" data-placeholder="Не выбрана">
                                    <option value="">Не выбрана</option>
                                    @foreach($series as $serie)
                                        <option value="{{$serie->id}}"
                                            {{ (string) $serie->id === (string) old('series', $course->series) ? 'selected' : '' }}
                                        >{{$serie->title}}</option>
                                    @endforeach
                                </select>
                                @error('series')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mb-0">
                                <label>Год</label>
                                <select name="years" id="course_years" class="form-control select2" style="width: 100%;" data-placeholder="Не выбран">
                                    <option value="">Не выбран</option>
                                    @foreach($years as $year)
                                        <option value="{{$year}}"
                                            {{ (string) $year === (string) old('years', $course->years) ? 'selected' : '' }}
                                        >{{$year}}</option>
                                    @endforeach
                                </select>
                                @error('years')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="form-group mb-0">
                                <label>Название курса</label>
                                <input name="title" id="course_title" type="text" class="form-control" aria-describedby="Название"
                                       value="{{$course->title}}">
                                @error('title')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group mb-0">
                                <label>Краткое название</label>
                                <input
                                    type="text"
                                    id="short_title_preview"
                                    class="form-control"
                                    value="{{ $course->short_title }}"
                                    readonly
                                    tabindex="-1"
                                >
                                <small class="form-text text-muted">Формируется автоматически</small>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-stretch">
                        <div class="col-6 d-flex">
                            <div class="form-group mb-0 w-100 d-flex flex-column">
                                <label for="prev_img">Добавить заставку курса</label>
                                <div class="mb-2" style="min-height: 120px;">
                                    <img src="{{ url('storage/' . $course->prev_img) }}" alt="Заставка курса" class="rounded" style="max-width: 160px; max-height: 120px; object-fit: cover;">
                                </div>
                                <div class="mt-auto">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="prev_img" name="prev_img">
                                            <label class="custom-file-label" for="prev_img">Выберите файл</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Загрузить</span>
                                        </div>
                                    </div>
                                    @error('prev_img')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-flex">
                            <div class="form-group mb-0 w-100 d-flex flex-column">
                                <label for="course_image">Добавить основное изображение курса</label>
                                <div class="mb-2" style="min-height: 120px;">
                                    <img src="{{ url('storage/' . $course->image) }}" alt="Основное изображение курса" class="rounded" style="max-width: 160px; max-height: 120px; object-fit: cover;">
                                </div>
                                <div class="mt-auto">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="course_image" name="image">
                                            <label class="custom-file-label" for="course_image">Выберите файл</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Загрузить</span>
                                        </div>
                                    </div>
                                    @error('image')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    <div class="mb-3 form-group">--}}
{{--                        <label>Выберите категорию</label>--}}
{{--                        <select name="category_id" class="form-control">--}}
{{--                            @foreach($categories as $category)--}}
{{--                                <option value="{{$category->id}}"--}}
{{--                                    {{ $category->id == $course->category_id ? ' selected' : '' }}--}}
{{--                                >{{$category->title}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                        @error('category_id')--}}
{{--                        <div class="text-danger">{{$message}}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
                    <div class="mb-3 form-group">
                        <label>Выберите автора/ов</label>
                        <select name="author_ids[]" class="select2" multiple="multiple" data-placeholder="Выберите" style="width: 100%;">
                            @foreach($authors as $author)
                                <option
                                    {{ is_array($course->authors->pluck('id')->toArray()) && in_array($author->id, $course->authors->pluck('id')->toArray()) ? ' selected' : ''  }}
                                    value="{{$author->id}}">{{$author->name}}</option>
                            @endforeach
                        </select>
                        @error('author_ids')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="exampleInputFile">Обновить УТП</label>
                        <div class="mb-2">
                            <a href="{{ url('storage/' . $course->utp) }}">
                                УТП
                            </a>
                        </div>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="utp">
                                <label class="custom-file-label">Выберите файл</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Загрузить</span>
                            </div>
                        </div>
                        @error('image')
                        <div class="text-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-3">
                            <div class="form-group mb-0">
                                <label for="starts_at">Cтарт обучения</label>
                                <input name="starts_at" id="starts_at" type="date" class="form-control"
                                       value="{{ old('starts_at', optional($course->starts_at)->format('Y-m-d')) }}">
                                <small class="form-text text-muted">Если дату очистить, блок на странице курса скроется.</small>
                                @error('starts_at')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mb-0">
                                <label for="study_form">Форма обучения</label>
                                <select name="study_form" id="study_form" class="form-control">
                                    <option value="">Не указана</option>
                                    @foreach(\App\Models\Course::STUDY_FORMS as $value => $label)
                                        <option value="{{ $value }}" {{ old('study_form', $course->study_form) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Если не выбрать, блок на странице курса скроется.</small>
                                @error('study_form')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mb-0">
                                <label for="study_weeks">Сроки обучения, недель</label>
                                <input name="study_weeks" id="study_weeks" type="number" min="1" max="104" class="form-control"
                                       value="{{ old('study_weeks', $course->study_weeks) }}">
                                <small class="form-text text-muted">Если поле очистить, блок на странице курса скроется.</small>
                                @error('study_weeks')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mb-0">
                                <label for="study_hours">Учебные часы</label>
                                <input name="study_hours" id="study_hours" type="number" min="1" max="5000" class="form-control"
                                       value="{{ old('study_hours', $course->study_hours) }}">
                                <small class="form-text text-muted">Если поле очистить, блок на странице курса скроется.</small>
                                @error('study_hours')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4 p-3 border rounded" style="background-color: #eef1f4;">
                        <h5 class="mb-3"><b>Управление публикацией</b></h5>

                        <div class="mb-3">
                            <div class="form-group mb-0">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="is_published" value="0">
                                    <input name="is_published" type="checkbox" class="custom-control-input" id="customSwitch2" value="1"
                                        {{ $course->is_published == 1 ? ' checked' : '' }}
                                    >
                                    <label class="custom-control-label" for="customSwitch2">Публикация</label>
                                </div>
                                @error('is_published')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 form-group">
                            <label>Статус по умолчанию</label>
                            <select name="default_status_id" id="default_status_id" class="form-control">
                                <option value=""></option>
                                @foreach($freeStatuses as $status)
                                    <option
                                        value="{{ $status->id }}"
                                        data-course-type="free"
                                        {{ (string) old('default_status_id', $course->default_status_id) === (string) $status->id ? 'selected' : '' }}
                                    >{{ $status->title }}</option>
                                @endforeach
                                @foreach($orderStatuses as $status)
                                    <option
                                        value="{{ $status->id }}"
                                        data-course-type="paid"
                                        {{ (string) old('default_status_id', $course->default_status_id) === (string) $status->id ? 'selected' : '' }}
                                    >{{ $status->title }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Для бесплатного курса — статусы заявок, для платного — статусы заказов.</small>
                            @error('default_status_id')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-group mb-0">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="open_registration" value="0">
                                    <input name="open_registration" type="checkbox" class="custom-control-input" id="customSwitch" value="1"
                                        {{ $course->open_registration == 1 ? ' checked' : '' }}
                                    >
                                    <label class="custom-control-label" for="customSwitch">Открыть запись на обучение</label>
                                </div>
                                @error('open_registration')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

{{--                        <div class="mb-3">--}}
{{--                            <div class="form-group mb-0">--}}
{{--                                <div class="custom-control custom-switch">--}}
{{--                                    <input type="hidden" name="code_future" value="0">--}}
{{--                                    <input name="code_future" type="checkbox" class="custom-control-input" id="customSwitch3" value="1"--}}
{{--                                        {{ $course->code_future == 1 ? ' checked' : '' }}--}}
{{--                                    >--}}
{{--                                    <label class="custom-control-label" for="customSwitch3">Код будущего</label>--}}
{{--                                </div>--}}
{{--                                @error('code_future')--}}
{{--                                <div class="text-danger">{{$message}}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="mb-0">
                            <div class="form-group mb-0">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="bioeconomy" value="0">
                                    <input name="bioeconomy" type="checkbox" class="custom-control-input" id="customSwitchBioeconomy" value="1"
                                        {{ $course->bioeconomy == 1 ? ' checked' : '' }}
                                    >
                                    <label class="custom-control-label" for="customSwitchBioeconomy">Биоэкономика</label>
                                </div>
                                @error('bioeconomy')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 mb-0">
                        <button type="submit" class="btn btn-primary">Обновить</button>
                        <a class="btn btn-outline-secondary" href="{{route('admin.course.index')}}">Назад</a>
                    </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label>Описание курса</label>
                            <textarea class="summernote" name="description">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-group">
                            <a class="d-flex align-items-center text-dark" href="#catalogCollapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="catalogCollapse">
                                <h5 class="mb-0 mr-2"><b>Торговый каталог</b></h5>
                                <i class="fas fa-angle-down"></i>
                            </a>
                            <div class="collapse mt-2" id="catalogCollapse">
                                <label>Стоимость обучения (руб.)</label>
                                <input name="price" type="text" class="form-control" aria-describedby="price"
                                       value="{{$course->price}}">
                                @error('price')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                                <div class="mt-3">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="fast_pay" value="0">
                                        <input name="fast_pay" type="checkbox" class="custom-control-input" id="customSwitch4" value="1"
                                            {{ $course->fast_pay == 1 ? ' checked' : '' }}
                                        >
                                        <label class="custom-control-label" for="customSwitch4">Разрешена оплата после создания заявки</label>
                                    </div>
                                    @error('fast_pay')
                                    <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <a class="d-flex align-items-center text-dark" href="#seoCollapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="seoCollapse">
                                <h5 class="mb-0 mr-2"><b>SEO</b></h5>
                                <i class="fas fa-angle-down"></i>
                            </a>
                            <div class="collapse mt-2" id="seoCollapse">
                                <label>Title</label>
                                <input name="seo_title" type="text" class="form-control" aria-describedby="Title"
                                       value="{{$course->seo_title}}">
                                @error('seo_title')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                                <label class="mt-2">Description</label>
                                <textarea class="form-control" name="seo_description">{{$course->seo_description}}</textarea>
                                @error('seo_description')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- /.container-fluid -->
    </section>









@endsection

@section('javascript')
    <script>
        (function () {
            var priceInput = document.querySelector('input[name="price"]');
            var statusSelect = document.getElementById('default_status_id');
            if (!priceInput || !statusSelect) {
                return;
            }

            function syncStatusOptions() {
                var isPaid = String(priceInput.value || '').trim() !== '';
                var expectedType = isPaid ? 'paid' : 'free';
                var selectedValue = statusSelect.value;
                var selectedStillValid = false;

                Array.prototype.forEach.call(statusSelect.options, function (option) {
                    if (!option.value) {
                        option.hidden = false;
                        return;
                    }
                    var matches = option.getAttribute('data-course-type') === expectedType;
                    option.hidden = !matches;
                    option.disabled = !matches;
                    if (matches && option.value === selectedValue) {
                        selectedStillValid = true;
                    }
                });

                if (!selectedStillValid) {
                    statusSelect.value = '';
                }
            }

            priceInput.addEventListener('input', syncStatusOptions);
            priceInput.addEventListener('change', syncStatusOptions);
            syncStatusOptions();
        })();

        (function () {
            function makeShortTitle(title, years) {
                var skipWords = {
                    'без': true, 'в': true, 'во': true, 'для': true, 'до': true, 'за': true,
                    'из': true, 'к': true, 'ко': true, 'между': true, 'на': true, 'над': true,
                    'о': true, 'об': true, 'обо': true, 'около': true, 'от': true, 'перед': true,
                    'по': true, 'под': true, 'подо': true, 'при': true, 'про': true, 'против': true,
                    'с': true, 'со': true, 'среди': true, 'у': true, 'через': true
                };
                title = String(title || '').replace(/\([^()]*\)/g, ' ').replace(/\s+/g, ' ');
                var parts = title.split('.');
                var abbrParts = [];
                var wordsUsed = 0;
                var maxWords = 6;

                parts.forEach(function (part) {
                    if (wordsUsed >= maxWords) {
                        return;
                    }

                    part = part.trim();
                    if (!part) {
                        return;
                    }

                    var letters = '';
                    part.split(/\s+/).forEach(function (word) {
                        if (wordsUsed >= maxWords) {
                            return;
                        }

                        word = word.replace(/^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$/gu, '');
                        if (!word) {
                            return;
                        }
                        var cyrillicMatch = word.match(/\p{Script=Cyrillic}/u);
                        if (!cyrillicMatch) {
                            return;
                        }
                        if (skipWords[word.toLocaleLowerCase('ru-RU')]) {
                            return;
                        }
                        letters += cyrillicMatch[0].toLocaleUpperCase('ru-RU');
                        wordsUsed++;
                    });

                    if (letters) {
                        abbrParts.push(letters);
                    }
                });

                var yearDigits = String(years || '').replace(/\D+/g, '');
                var yearSuffix = yearDigits ? yearDigits.slice(-2) : '';
                if (yearSuffix) {
                    abbrParts.push(yearSuffix);
                }

                return abbrParts.join('-');
            }

            function updateShortTitlePreview() {
                var titleInput = document.getElementById('course_title');
                var yearsSelect = document.getElementById('course_years');
                var preview = document.getElementById('short_title_preview');
                if (!titleInput || !preview) {
                    return;
                }

                preview.value = makeShortTitle(titleInput.value, yearsSelect ? yearsSelect.value : '');
            }

            $('#course_title').on('input', updateShortTitlePreview);
            $('#course_years').on('change', updateShortTitlePreview);
            updateShortTitlePreview();
        })();
    </script>
@endsection
