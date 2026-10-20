@php
    /** @var \App\Models\Author|null $author */
    $author = $author ?? null;
@endphp

<hr>
<h5 class="mb-3">Паспортные данные</h5>

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="passport_series">Серия паспорта</label>
        <input name="passport_series" id="passport_series" type="text" class="form-control" maxlength="10"
               value="{{ old('passport_series', $author->passport_series ?? '') }}">
        @error('passport_series')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label for="passport_number">Номер паспорта</label>
        <input name="passport_number" id="passport_number" type="text" class="form-control" maxlength="20"
               value="{{ old('passport_number', $author->passport_number ?? '') }}">
        @error('passport_number')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label for="passport_issued_at">Дата выдачи</label>
        <input name="passport_issued_at" id="passport_issued_at" type="date" class="form-control"
               value="{{ old('passport_issued_at', optional($author->passport_issued_at ?? null)->format('Y-m-d')) }}">
        @error('passport_issued_at')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label for="passport_department_code">Код подразделения</label>
        <input name="passport_department_code" id="passport_department_code" type="text" class="form-control" maxlength="20"
               value="{{ old('passport_department_code', $author->passport_department_code ?? '') }}">
        @error('passport_department_code')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="passport_issued_by">Кем выдан</label>
    <input name="passport_issued_by" id="passport_issued_by" type="text" class="form-control"
           value="{{ old('passport_issued_by', $author->passport_issued_by ?? '') }}">
    @error('passport_issued_by')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="inn">ИНН</label>
    <input name="inn" id="inn" type="text" class="form-control" maxlength="12" inputmode="numeric" style="max-width: 16rem;"
           value="{{ old('inn', $author->inn ?? '') }}">
    @error('inn')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="registration_address">Адрес места жительства (регистрация)</label>
    <textarea name="registration_address" id="registration_address" class="form-control" rows="3">{{ old('registration_address', $author->registration_address ?? '') }}</textarea>
    @error('registration_address')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<hr>
<h5 class="mb-3">Банковские реквизиты</h5>

<div class="mb-3">
    <label for="bank_account">Счёт №</label>
    <input name="bank_account" id="bank_account" type="text" class="form-control" maxlength="34"
           value="{{ old('bank_account', $author->bank_account ?? '') }}">
    @error('bank_account')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="bank_name">Банк</label>
    <input name="bank_name" id="bank_name" type="text" class="form-control"
           value="{{ old('bank_name', $author->bank_name ?? '') }}">
    @error('bank_name')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="bank_bik">БИК</label>
        <input name="bank_bik" id="bank_bik" type="text" class="form-control" maxlength="20"
               value="{{ old('bank_bik', $author->bank_bik ?? '') }}">
        @error('bank_bik')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="bank_correspondent_account">Корреспондентский счёт</label>
        <input name="bank_correspondent_account" id="bank_correspondent_account" type="text" class="form-control" maxlength="34"
               value="{{ old('bank_correspondent_account', $author->bank_correspondent_account ?? '') }}">
        @error('bank_correspondent_account')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
