@extends('admin.contract.pdf_layout')

@section('title', 'Задание №'.$assignment->number.' — '.($author->full_name ?: $author->name))

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
        $rate = $assignment->hourlyRateFormatted();
        $rateWords = $assignment->hourlyRateInWords();
        $perStudentRate = $assignment->perStudentAmountFormatted();
        $perStudentRateWords = $assignment->perStudentAmountInWords();
    @endphp

    <h1 class="assignment-title">ЗАДАНИЕ №{{ $assignment->number }}</h1>
    <p class="appendix-subtitle assignment-subtitle">
        к Договору № {{ $contract->number ?: '________' }}
        от {{ $contract->signedAtLabel() }}
    </p>

    <table class="meta" width="100%">
        <tr>
            <td>г. Москва</td>
            <td style="text-align: right;">{{ $assignment->issuedAtLabel() }}</td>
        </tr>
    </table>

    <p>
        Общество с ограниченной ответственностью «Центр повышения квалификации и профессиональной подготовки «Основание»,
        именуемое в дальнейшем «Заказчик», в лице Генерального директора <strong>Скуратова Александра Ивановича</strong>, действующего
        на основании Устава, с одной стороны и гражданин(ка) РФ <strong>{{ $authorName }}</strong>, зарегистрирован(а)
        плательщиком налога на профессиональный доход {{ $contract->napRegisteredAtLabel() }}, именуемая(ый) в дальнейшем
        «Исполнитель», с другой стороны, совместно именуемые «Стороны», составили настоящее Задание к Договору
        <strong>№ {{ $contract->number ?: '________' }}</strong> от {{ $contract->signedAtLabel() }} о нижеследующем.<br>
        Исполнитель по заданию Заказчика обязуется оказать следующие услуги: обучение Слушателей по {{ $programDative }}
        <strong>«{{ $courseTitle }}»</strong> ({{ $courseHours }}), зачисленных на поток {{ $assignment->studyGroupLabel() }}
        в формате {{ $studyForm }} обучения с использованием дистанционных образовательных технологий.
        Включающее следующие <strong>виды работ:</strong>
    </p>

    <p style="text-align: left;">{!! nl2br(e($assignment->work_types)) !!}</p>

    @if($assignment->isPaymentHourly())
        <p>
            Стоимость услуг Исполнителя по настоящему Заданию определяется исходя из ставки  <strong>{{ $rate }}</strong> ({{ $rateWords }})
            рублей 00 коп. за 1 (один) академический час оказания услуг. Сумма рассчитывается по формуле:
            количество отработанных часов x {{ $rate }} ({{ $rateWords }}) рублей 00 коп.<br>
            Итоговая сумма по настоящему Заданию рассчитывается на основании представленных Исполнителем отчетов
            и указывается в Акте об оказании услуг.
        </p>
    @endif

    @if($assignment->isPaymentFixed())
        <p>
            Стоимость услуг Исполнителя по настоящему Заданию составляет <strong>{{ $rate }}</strong> ({{ $rateWords }})
            рублей 00 коп.
        </p>
    @endif

    @if($assignment->isPaymentPerStudent())
        <p>
            Заказчик обязуется выплатить {{ $assignment->hasBasePayment() ? 'дополнительное вознаграждение' : 'вознаграждение' }}
            в размере <strong>{{ $perStudentRate }}</strong> ({{ $perStudentRateWords }})
            рубля 00 коп. за каждого Слушателя, успешно завершившего обучение в группе.
        </p>
    @endif

    <p>
         <strong>Срок оказания услуг:</strong> с {{ $assignment->startsAtShortLabel() }} по {{ $assignment->endsAtShortLabel() }}.
    </p>

    <table class="signatures appendix-signatures appendix-signatures-final assignment-signatures">
        <tr>
            <td>
                <strong>Заказчик</strong><br>
                ООО Центр повышения квалификации<br>
                и профессиональной подготовки «Основание»<br>
                Генеральный директор<br>
                <br>
                _______________ /Скуратов А.И./
            </td>
            <td>
                <strong>Исполнитель</strong><br>
                {{ $authorName }}<br>
                <br>
                <br>
                <br>
                _______________ / {{ $authorPseudonym }} /
            </td>
        </tr>
    </table>
@endsection
