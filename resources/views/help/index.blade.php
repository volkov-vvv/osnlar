@extends('layouts.main2')

@include('help._styles')

@section('content')
    <main class="blog">
        <div class="help-wrap" data-aos="fade-up">
            <div class="help-hero">
                <h1>Справка</h1>
                <p>Инструкция по работе с платформой «Основание». Разделы подобраны под вашу роль.</p>
            </div>

            <div class="help-layout">
                <aside class="help-nav" aria-label="Оглавление">
                    <p class="help-nav-title">Разделы</p>
                    @foreach($groups as $groupName => $pages)
                        <div class="help-nav-group">
                            <p class="help-nav-group-title">{{ $groupName }}</p>
                            @foreach($pages as $page)
                                <a href="{{ route('help.show', $page['slug']) }}">{{ $page['title'] }}</a>
                            @endforeach
                        </div>
                    @endforeach
                </aside>

                <div class="help-card">
                    @forelse($groups as $groupName => $pages)
                        <h2 class="help-section-title">{{ $groupName }}</h2>
                        <ul class="help-toc-list mb-4">
                            @foreach($pages as $page)
                                <li>
                                    <a href="{{ route('help.show', $page['slug']) }}">
                                        <strong>{{ $page['title'] }}</strong>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @empty
                        <p class="mb-0 text-muted">Для вашей роли пока нет доступных разделов справки.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
@endsection
