@extends('admin.contract.pdf_layout')

@section('title', 'Акт №'.$act->number.' — '.($author->full_name ?: $author->name))

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
        $amount = $act->amount !== null ? $act->amountFormatted() : '________';
        $amountWords = $act->amount !== null ? $act->amountInWords() : '________';
    @endphp

    <h1 class="assignment-title">АКТ №{{ $act->number }}</h1>
    <p class="appendix-subtitle assignment-subtitle">
        об оказании услуг к Заданию № {{ $assignment->number }} от {{ $assignment->issuedAtLabel() }}<br>
        по Договору № {{ $contract->number ?: '________' }} от {{ $contract->signedAtLabel() }}
    </p>

    <table class="meta" width="100%">
        <tr>
            <td>г. Москва</td>
            <td style="text-align: right;">{{ $act->issuedAtLabel() }}</td>
        </tr>
    </table>

    <p>
        Общество с ограниченной ответственностью «Центр повышения квалификации и профессиональной подготовки «Основание»,
        именуемое в дальнейшем «Заказчик», в лице Генерального директора <strong>Скуратова Александра Ивановича</strong>, действующего
        на основании Устава, с одной стороны и гражданин(ка) РФ <strong>{{ $authorName }}</strong>, зарегистрирован(а)
        плательщиком налога на профессиональный доход {{ $contract->napRegisteredAtLabel() }}, именуемая(ый) в дальнейшем
        «Исполнитель», с другой стороны, совместно именуемые «Стороны», составили настоящий Акт о нижеследующем:
        Исполнитель выполнил, а Заказчик принял нижеследующие услуги.
    </p>

    <p>
        Обучение Слушателей по {{ $programDative }}
        <strong>«{{ $courseTitle }}»</strong> ({{ $courseHours }}), зачисленных на поток {{ $assignment->studyGroupLabel() }}
        в формате {{ $studyForm }} обучения с использованием дистанционных образовательных технологий,
        включающее следующие виды работ:
    </p>

    <p style="text-align: left;">{!! nl2br(e($assignment->work_types)) !!}</p>

    <p>
        Стоимость оказанных услуг по Заданию № {{ $assignment->number }} составляет
        <strong>{{ $amount }}</strong> ({{ $amountWords }}) рублей 00 коп.
        Услуги оказаны Исполнителем в объеме и сроки, установленные Заданием №{{ $assignment->number }}
        к Договору {{ $contract->number ?: '________' }} от {{ $contract->signedAtLabel() }} надлежащего качества.
        Заказчик претензий к оказанным услугам не имеет. Исполнитель обязуется исполнить свои обязательства,
        предусмотренные п. 3.3. Договора № {{ $contract->number ?: '________' }} от {{ $contract->signedAtLabel() }}.
    </p>

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
