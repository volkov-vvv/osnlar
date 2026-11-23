@php
    $authorName = $author->full_name ?: ($author->name ?? '—');
    $authorPseudonym = $author->name ?: $authorName;
    $contractNumber = $contract->number ?: '—';
    $contractDate = $contract->signedAtLabel();
    $courseTitle = $course->title ?? '—';
    $courseHours = $course?->studyHoursFullLabel() ?? '—';
    $studyForm = $course?->studyFormGenitiveLabel() ?? '____________';
    $programDative = match ($course->type ?? null) {
        'professional_retraining' => 'дополнительной профессиональной программе профессиональной переподготовки',
        'advanced_training' => 'дополнительной профессиональной программе повышения квалификации',
        'professional_education' => 'программе профессионального обучения',
        default => 'программе',
    };
@endphp

{{-- Приложение № 1: форма Отчёта --}}
<div class="appendix">
    <div class="appendix-header">
        <div>Приложение № 1</div>
        <div>к Договору оказания услуг № {{ $contractNumber }}</div>
        <div>от {{ $contractDate }}</div>
    </div>

    <p class="appendix-form-label"><strong>ФОРМА</strong></p>
    <h2 class="appendix-title">ОТЧЕТ № ____</h2>
    <p class="appendix-subtitle">
        по Заданию № ___ от « __ » _______ 202__ г.<br>
        к Договору № ___ от « __ » _______ 202__ г.
    </p>

    <table class="meta" width="100%">
        <tr>
            <td>г. Москва</td>
            <td style="text-align: right;">«__» ________________ 202_ г.</td>
        </tr>
    </table>

    <p>
        Гражданин(ка) РФ <strong>ФИО</strong>, зарегистрирован(а) плательщиком налога на профессиональный доход
        «__» ___202_ г., именуемый(ая) в дальнейшем «Исполнитель», в срок с «__» ____ 202__ г. по «__» ______ 202___ г.
        оказал следующие услуги.
    </p>

    <p>
        Обучение Слушателей по {{ $programDative }}
        <strong>«Название курса»</strong> (кол-во часов), зачисленных на поток _________
        в формате _______ обучения с использованием дистанционных образовательных технологий,
        включающее следующие виды работ:<br>
        № _________________________________________________________________ .
    </p>

    <table class="appendix-inner" width="100%">
        <tr>
            <td>Ссылка на проведенное занятие</td>
            <td style="width: 40%;">Количество проведенных акад. часов</td>
        </tr>
        <tr>
            <td style="height: 28px;">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>

    <table class="signatures appendix-signatures appendix-signatures-draft">
        <tr>
            <td><strong>Заказчик</strong></td>
            <td><strong>Исполнитель</strong></td>
        </tr>
        <tr>
            <td>
                ООО Центр повышения квалификации<br>
                и профессиональной подготовки «Основание»<br>
                Генеральный директор<br>
                ____________________/Скуратов А.И./
            </td>
            <td>
                <br>
                <br>
                <br>
                _________________/ФИО/
            </td>
        </tr>
    </table>

    <p class="appendix-agreed">СОГЛАСОВАНО:</p>

    <table class="signatures appendix-signatures appendix-signatures-final">
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
                <br>
                _______________ / {{ $authorPseudonym }} /
            </td>
        </tr>
    </table>
</div>

{{-- Приложение № 2: форма Акта --}}
<div class="appendix">
    <div class="appendix-header">
        <div>Приложение № 2</div>
        <div>к Договору оказания услуг № {{ $contractNumber }}</div>
        <div>от {{ $contractDate }}</div>
    </div>

    <p class="appendix-form-label"><strong>ФОРМА</strong></p>
    <h2 class="appendix-title">АКТ № ____</h2>
    <p class="appendix-subtitle">
        об оказании услуг к Заданию № ____ от «__» ____ 202__ г.<br>
        по Договору № ____ от «__» ____ 202__ г.
    </p>

    <table class="meta" width="100%">
        <tr>
            <td>г. Москва</td>
            <td style="text-align: right;">«__» ________________ 202_ г.</td>
        </tr>
    </table>

    <p>
        Общество с ограниченной ответственностью «Центр повышения квалификации и профессиональной подготовки «Основание»,
        именуемое в дальнейшем «Заказчик», в лице Генерального директора Скуратова Александра Ивановича, действующего
        на основании Устава, с одной стороны и гражданин(ка) РФ ФИО, зарегистрирован(а)
        плательщиком налога на профессиональный доход «__» _____ 20__ г., именуемая(ый) в дальнейшем
        «Исполнитель», с другой стороны, совместно именуемые «Стороны», составили настоящий Акт о нижеследующем: Исполнитель выполнил, а Заказчик принял нижеследующие услуги.<br>
        Обучение Слушателей по {{ $programDative }}
        <strong>«Название программы»</strong> (кол-во часов), зачисленных на поток _________
        в формате _______ обучения с использованием дистанционных образовательных технологий,
        включающее следующие виды работ: <br>
        № _________________________________________________________________ .
    </p>

    <p>
        Стоимость оказанных услуг по Заданию № __ составляет _________ рублей 00 коп.
        Услуги оказаны Исполнителем в объеме и сроки, установленные Заданием №__ к Договору _______
        от «__» _______ 202___г. надлежащего качества.
       Заказчик претензий к оказанным услугам не имеет. Исполнитель обязуется исполнить свои обязательства,
        предусмотренные п. 3.3. Договора № _______ от «____» _______ 202____ г.
    </p>

     <table class="signatures appendix-signatures appendix-signatures-draft">
        <tr>
            <td><strong>Заказчик</strong></td>
            <td><strong>Исполнитель</strong></td>
        </tr>
        <tr>
            <td>
                ООО Центр повышения квалификации<br>
                и профессиональной подготовки «Основание»<br>
                Генеральный директор<br>
                ____________________/Скуратов А.И./
            </td>
            <td>
                <br>
                <br>
                <br>
                _________________/ФИО/
            </td>
        </tr>
    </table>

    <p class="appendix-agreed">СОГЛАСОВАНО:</p>

    <table class="signatures appendix-signatures appendix-signatures-final">
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
                <br>
                _______________ / {{ $authorPseudonym }} /
            </td>
        </tr>
    </table>
</div>
