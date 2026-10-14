@extends('layouts.main2')

@push('styles')
    <style>
        .course-page-title {
            overflow-wrap: anywhere;
            word-break: break-word;
            hyphens: auto;
            line-height: 1.25;
        }
        .authors-list {
            padding-bottom: 10rem;
        }
        @media (max-width: 767.98px) {
            .course-page-title {
                font-size: 1.6rem;
            }
        }
    </style>
@endpush

@section('content')

    <main class="blog">
        <div class="container">
            <div class="row pt-5 pb-4">
                <div class="col text-center" data-aos="fade-up">
                    <h1 class="text-center course-page-title">Авторы и эксперты</h1>
                </div>
            </div>

            <section class="authors-list">
                <div class="row pb-4">
                    @foreach($authors as $author)
                        <div class="col-6 col-md-4 col-lg-3 mb-4" data-aos="fade-up">
                            <a href="{{ route('author.show', $author) }}" class="d-block text-center text-decoration-none">
                                @if($author->photo)
                                    <img src="{{ url('storage/' . $author->photo) }}" alt="{{ $author->display_name }}" class="rounded-circle" width="140" height="140" style="object-fit: cover;">
                                @else
                                    <span class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 140px; height: 140px;">
                                        <i class="bi bi-person" style="font-size: 3rem; color: #094ca1;"></i>
                                    </span>
                                @endif
                                <h2 class="h5 mt-3 mb-1" style="color: #094ca1">{{ $author->display_name }}</h2>
                                @if($author->full_name !== '' && $author->name && $author->full_name !== $author->name)
                                    <p class="mb-0" style="color: grey">{{ $author->name }}</p>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </main>

@endsection
