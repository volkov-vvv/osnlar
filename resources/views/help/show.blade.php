@extends('layouts.main2')

@include('help._styles')

@section('content')
    <main class="blog">
        <div class="help-wrap" data-aos="fade-up">
            <a class="help-back" href="{{ route('help.index') }}">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>К оглавлению
            </a>

            <div class="help-layout">
                <aside class="help-nav" aria-label="Оглавление">
                    <p class="help-nav-title">Разделы</p>
                    @php $grouped = collect($navPages)->groupBy('group'); @endphp
                    @foreach($grouped as $groupName => $pages)
                        <div class="help-nav-group">
                            <p class="help-nav-group-title">{{ $groupName }}</p>
                            @foreach($pages as $page)
                                <a
                                    href="{{ route('help.show', $page['slug']) }}"
                                    class="{{ $page['slug'] === $current['slug'] ? 'is-active' : '' }}"
                                >{{ $page['title'] }}</a>
                            @endforeach
                        </div>
                    @endforeach
                </aside>

                <article class="help-card help-article">
                    {!! $html !!}

                    <div class="help-pager">
                        <div>
                            @if($prev)
                                <a href="{{ route('help.show', $prev['slug']) }}">
                                    <i class="fas fa-chevron-left mr-1" aria-hidden="true"></i>{{ $prev['title'] }}
                                </a>
                            @endif
                        </div>
                        <div>
                            @if($next)
                                <a href="{{ route('help.show', $next['slug']) }}">
                                    {{ $next['title'] }}<i class="fas fa-chevron-right ml-1" aria-hidden="true"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </main>
@endsection
