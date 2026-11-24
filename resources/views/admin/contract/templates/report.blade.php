@extends('admin.contract.pdf_layout')

@section('title', 'Отчёт №'.$report->displayNumber().' — '.($author->full_name ?: $author->name))

@section('content')
    @php
        $authorName = $author->full_name ?: ($author->name ?? '—');
        $authorPseudonym = $author->name ?: $authorName;
        $courseTitle = $course->title ?? '—';
        $courseHours = $course?->studyHoursFullLabel() ?? '—';
        $studyForm = $course?->studyFormGenitiveLabel() ?? '____________';
        $programDative = match ($course->type ?? null) {
            'professional_retraining' => 'дополнительной профессиональной программе профессиональной переподготовки',
            'advanced_training' => 'дополнительной профессиональной программе повышения квалификации',
            'professional_education' => 'программе профессионального обучения',
            default => 'программе',
        };
        $lessonUrl = $report->lesson_url ?: '____';
        $lessonHours = $report->lesson_hours !== null ? $report->lessonHoursFormatted() : '____';
        $students = $report->successfulStudentsList();
        $isPerStudent = $report->isPerStudent();
    @endphp

    <h1 class="assignment-title">ОТЧЕТ №{{ $report->displayNumber() }}</h1>
    <p class="appendix-subtitle assignment-subtitle">
        по Заданию № {{ $assignment->number }} от {{ $assignment->issuedAtLabel() }}<br>
        к Договору № {{ $contract->number ?: '________' }} от {{ $contract->signedAtLabel() }}
    </p>

    <table class="meta" width="100%">
        <tr>
            <td>г. Москва</td>
            <td style="text-align: right;">{{ $report->issuedAtLabel() }}</td>
        </tr>
    </table>

    <p>
        Гражданин(ка) РФ <strong>{{ $authorName }}</strong>, зарегистрирован(а) плательщиком налога на профессиональный доход
        {{ $contract->napRegisteredAtLabel() }}, именуемый(ая) в дальнейшем «Исполнитель»,
        в срок с {{ $report->startsAtShortLabel() }} по {{ $report->endsAtShortLabel() }}
        оказал следующие услуги.
    </p>

    <p>
        Обучение Слушателей по {{ $programDative }}
        <strong>«{{ $courseTitle }}»</strong> ({{ $courseHours }}), зачисленных на поток {{ $assignment->studyGroupLabel() }}
        в формате {{ $studyForm }} обучения с использованием дистанционных образовательных технологий,
        включающее следующие виды работ:
    </p>

    <p style="text-align: left;">{!! nl2br(e($assignment->work_types)) !!}</p>

    @if($isPerStudent)
        <p>
            Успешно завершили обучение следующие Слушатели:
        </p>
        <table class="appendix-inner" width="100%">
            <tr>
                <td style="width: 10%;">№</td>
                <td>Фамилия И.О.</td>
            </tr>
            @forelse($students as $index => $studentName)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $studentName }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">—</td>
                </tr>
            @endforelse
        </table>
    @else
        <table class="appendix-inner" width="100%">
            <tr>
                <td>Ссылка на проведенное занятие</td>
                <td style="width: 40%;">Количество проведенных акад. часов</td>
            </tr>
            <tr>
                <td>{{ $lessonUrl }}</td>
                <td>{{ $lessonHours }}</td>
            </tr>
        </table>
    @endif

    <table class="signatures appendix-signatures appendix-signatures-final assignment-signatures">
        <tr>
            <td><strong>Заказчик</strong></td>
            <td><strong>Исполнитель</strong></td>
        </tr>
        <tr>
            <td>
                ООО Центр повышения квалификации<br>
                и профессиональной подготовки «Основание»<br>
                Генеральный директор<br>
                <br>
                _______________ /Скуратов А.И./
            </td>
            <td>
                {{ $authorName }}<br>
                <br>
                <br>
                _______________ / {{ $authorPseudonym }} /
            </td>
        </tr>
    </table>
@endsection
