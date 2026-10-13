@php
    $yearOptions = $yearOptions ?? \App\Models\Year::options();
    $selectedYear = $selectedYear ?? null;
    $includeEmpty = $includeEmpty ?? false;
    $selectDefault = $selectDefault ?? false;
    $defaultYear = $defaultYear ?? \App\Models\Year::defaultValue();
@endphp
@if($includeEmpty)
    <option value=""></option>
@endif
@foreach($yearOptions as $yearOption)
    <option value="{{ $yearOption }}"
        @if(!is_null($selectedYear) && (string)$selectedYear === (string)$yearOption)
            selected
        @elseif(is_null($selectedYear) && $selectDefault && (string)$yearOption === (string)$defaultYear)
            selected
        @endif
    >{{ $yearOption }}</option>
@endforeach
