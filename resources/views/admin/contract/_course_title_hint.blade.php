@php
    $course = $course ?? null;
@endphp
@if($course?->short_title)
    <span class="d-inline-flex align-items-center">
        {{ $course->short_title }}
        <span
            class="course-short-title-hint ml-1"
            data-full-title="{{ $course->title }}"
            aria-label="{{ $course->title }}"
        >
            <i class="fas fa-question-circle text-muted" aria-hidden="true"></i>
        </span>
    </span>
@else
    {{ $course->title ?? '—' }}
@endif
