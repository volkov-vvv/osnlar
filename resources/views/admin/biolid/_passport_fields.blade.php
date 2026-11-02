@php
    /** @var \App\Models\BioLid|null $bioLid */
    $bioLid = $bioLid ?? null;
@endphp

<hr>
<h5 class="mb-3">Паспортные данные</h5>

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="passport_series">Серия паспорта</label>
        @php
            $passportSeries = old('passport_series', $bioLid->passport_series ?? '');
            $passportSeriesDigits = preg_replace('/\D+/', '', (string) $passportSeries);
            if (strlen($passportSeriesDigits) === 4) {
                $passportSeries = substr($passportSeriesDigits, 0, 2).' '.substr($passportSeriesDigits, 2, 2);
            }
        @endphp
        <input
            name="passport_series"
            id="passport_series"
            type="text"
            class="form-control"
            inputmode="numeric"
            maxlength="5"
            placeholder="** **"
            data-mask
            data-inputmask="'mask': '99 99', 'placeholder': '** **', 'clearIncomplete': true"
            value="{{ $passportSeries }}"
        >
        @error('passport_series')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label for="passport_number">Номер паспорта</label>
        <input
            name="passport_number"
            id="passport_number"
            type="text"
            class="form-control"
            inputmode="numeric"
            maxlength="6"
            placeholder="******"
            data-mask
            data-inputmask="'mask': '999999', 'placeholder': '******', 'clearIncomplete': true"
            value="{{ old('passport_number', $bioLid->passport_number ?? '') }}"
        >
        @error('passport_number')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label for="passport_issued_at">Дата выдачи</label>
        <input
            name="passport_issued_at"
            id="passport_issued_at"
            type="date"
            class="form-control"
            value="{{ old('passport_issued_at', optional($bioLid->passport_issued_at ?? null)->format('Y-m-d')) }}"
        >
        @error('passport_issued_at')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label for="passport_department_code">Код подразделения</label>
        @php
            $passportDepartmentCode = old('passport_department_code', $bioLid->passport_department_code ?? '');
            $passportDepartmentDigits = preg_replace('/\D+/', '', (string) $passportDepartmentCode);
            if (strlen($passportDepartmentDigits) === 6) {
                $passportDepartmentCode = substr($passportDepartmentDigits, 0, 3).'–'.substr($passportDepartmentDigits, 3, 3);
            }
        @endphp
        <input
            name="passport_department_code"
            id="passport_department_code"
            type="text"
            class="form-control"
            inputmode="numeric"
            maxlength="7"
            placeholder="***–***"
            data-mask
            data-inputmask="'mask': '999–999', 'placeholder': '***–***', 'clearIncomplete': true"
            value="{{ $passportDepartmentCode }}"
        >
        @error('passport_department_code')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="passport_issued_by">Кем выдан</label>
    <input
        name="passport_issued_by"
        id="passport_issued_by"
        type="text"
        class="form-control"
        value="{{ old('passport_issued_by', $bioLid->passport_issued_by ?? '') }}"
    >
    @error('passport_issued_by')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
