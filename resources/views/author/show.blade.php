@extends('layouts.main2')

@push('styles')
    <style>
        .author-page {
            padding-bottom: 10rem;
        }
    </style>
@endpush

@section('content')

    <main class="blog-post author-page">
        <div class="container">
            <div class="row pt-5 pb-4 align-items-center justify-content-center">
                @if($author->photo)
                    <div class="col-auto text-center pb-3 pb-lg-0" data-aos="fade-up">
                        <img src="{{ url('storage/' . $author->photo) }}" alt="{{ $author->display_name }}" class="rounded-circle" width="180" height="180" style="object-fit: cover;">
                    </div>
                @endif
                <div class="col-lg-8 text-center text-lg-start" data-aos="fade-up" data-aos-delay="100">
                    <h1 class="mb-2" style="color: #094ca1">{{ $author->display_name }}</h1>
                    @if($author->full_name !== '' && $author->name && $author->full_name !== $author->name)
                        <p class="mb-0" style="color: grey">{{ $author->name }}</p>
                    @endif
                </div>
            </div>

            @if($author->description)
                <section data-aos="fade-up" data-aos-delay="200">
                    <div class="row">
                        <div class="col-lg-9 mx-auto">
                            {!! $author->description !!}
                        </div>
                    </div>
                </section>
            @endif

            @if($courses->isNotEmpty())
                <section class="blog pt-5 pb-5" data-aos="fade-up">
                    <h2 class="text-center pb-4" style="color: #094ca1">Курсы</h2>
                    <div class="row">
                        @foreach($courses as $course)
                            <div class="col-md-4 fetured-post blog-post">
                                <div class="blog-post-thumbnail-wrapper">
                                    <a href="{{ route('course.show', $course->id) }}">
                                        <img src="{{ url('storage/' . $course->prev_img) }}" alt="{{ $course->title }}">
                                    </a>
                                </div>
                                <a href="{{ route('course.show', $course->id) }}" class="blog-post-permalink">
                                    <h6 class="blog-post-title">{{ $course->title }}</h6>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>

@endsection
