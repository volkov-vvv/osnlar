@php
    $breadcrumbItems = \App\View\AdminBreadcrumbs::items($breadcrumbs ?? null);
@endphp
@if($breadcrumbItems !== [])
    <nav class="admin-breadcrumbs" aria-label="Навигационная цепочка">
        <ol class="breadcrumb mb-0">
            @foreach($breadcrumbItems as $item)
                @if(!$loop->last && !empty($item['url']))
                    <li class="breadcrumb-item">
                        <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                    </li>
                @elseif($loop->last)
                    <li class="breadcrumb-item active">{{ $item['title'] }}</li>
                @else
                    <li class="breadcrumb-item">{{ $item['title'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
