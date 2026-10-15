@extends('layouts.main2')

@push('styles')
<style>
    .archive-year-filter {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem 1rem;
        margin-bottom: 1.25rem;
    }

    .archive-year-filter__label {
        margin: 0;
        font-weight: 700;
        color: #1a1a1a;
        white-space: nowrap;
    }

    .archive-year-filter__list {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.35rem;
        border: 1px solid #d0d5db;
        border-radius: 0.75rem;
        background: #f7f8fa;
    }

    .archive-year-filter__btn {
        appearance: none;
        border: 0;
        background: transparent;
        color: #4a5563;
        font-weight: 700;
        font-size: 0.95rem;
        line-height: 1;
        padding: 0.65rem 1.1rem;
        border-radius: 0.55rem;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    }

    .archive-year-filter__btn:hover {
        color: #094ca1;
        background: #fff;
    }

    .archive-year-filter__btn.is-active {
        color: #fff;
        background: #007bff;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.25);
    }

    .archive-year-filter__btn:focus {
        outline: none;
    }

    .archive-year-filter__btn:focus-visible {
        outline: 2px solid #007bff;
        outline-offset: 2px;
    }

    @media (max-width: 575.98px) {
        .archive-year-filter {
            justify-content: center;
        }

        .archive-year-filter__list {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')

    <main class="blog">
        <div class="container">
            <div class="row p-5"  data-aos="fade-up">
                <div class="col">
                    <h1 class="text-center">Архив курсов</h1>
                    <p class="text-center" style="color: grey; font-size: 20px"></p>
                </div>
            </div>
            <section class="featured-posts-section">
                <div class="archive-year-filter" data-aos="fade-up" role="group" aria-label="Фильтр по году">
                    <span class="archive-year-filter__label">Год</span>
                    <div class="archive-year-filter__list" id="archive-year">
                        <button type="button" class="archive-year-filter__btn is-active" data-year="all">Все</button>
                        @foreach($years as $year)
                            <button
                                type="button"
                                class="archive-year-filter__btn"
                                data-year="{{ $year }}"
                            >{{ $year }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col fetured-post blog-post" data-aos="fade-up">
                        <div>
                            <div>
                                <table id="example1" class="table table-bordered hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>№</th>
                                        <th>Название курса</th>
                                        <th>Год</th>
                                        <th>Статус</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($courses as $course)
                                        <tr data-year="{{ $course->years }}">
                                            <td>{{$course->id}}</td>
                                            <td>{{$course->title}}</td>
                                            <td>{{$course->years}}</td>
                                            <td>{{$course->is_published == 0 ? 'Архив' : ''}}</td>
                                        </tr>
                                    @endforeach
                                    <tr id="archive-empty-row" class="d-none">
                                        <td colspan="4" class="text-center text-muted">Курсов за выбранный год нет</td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card-->
                    </div>
                </div>
            </section>

        </div>

    </main>



{{--    <main class="blog">--}}
{{--        <div class="container">--}}
{{--            <h1 class="edica-page-title" data-aos="fade-up">Курсы</h1>--}}
{{--            <section class="featured-posts-section">--}}
{{--                <div class="row">--}}
{{--                    @foreach($courses as $course)--}}
{{--                    <div class="col-md-4 fetured-post blog-post" data-aos="fade-right">--}}
{{--                        <div class="blog-post-thumbnail-wrapper">--}}
{{--                            <a href="{{route('course.show', $course->id)}}">--}}
{{--                                <img src="{{'storage/' . $course->prev_img}}" alt="blog post">--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <p class="blog-post-category"></p>--}}
{{--                        <a href="{{route('course.show', $course->id)}}" class="blog-post-permalink">--}}
{{--                            <h6 class="blog-post-title">{{$course->title}}</h6>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </section>--}}

{{--        </div>--}}

{{--    </main>--}}


@push('scripts')
<script>
    (function () {
        var filter = document.getElementById('archive-year');
        var buttons = filter.querySelectorAll('.archive-year-filter__btn');
        var rows = document.querySelectorAll('#example1 tbody tr[data-year]');
        var emptyRow = document.getElementById('archive-empty-row');

        function filterByYear(year) {
            var visibleCount = 0;

            rows.forEach(function (row) {
                var match = year === 'all' || String(row.getAttribute('data-year')) === String(year);
                row.classList.toggle('d-none', !match);
                if (match) {
                    visibleCount++;
                }
            });

            emptyRow.classList.toggle('d-none', visibleCount > 0);
        }

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                buttons.forEach(function (item) {
                    item.classList.toggle('is-active', item === button);
                });
                filterByYear(button.getAttribute('data-year'));
            });
        });

        var active = filter.querySelector('.archive-year-filter__btn.is-active');
        filterByYear(active ? active.getAttribute('data-year') : 'all');
    })();
</script>
@endpush

@endsection
