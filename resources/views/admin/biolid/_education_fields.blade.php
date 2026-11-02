@php
    /** @var \App\Models\BioLid|null $bioLid */
    $bioLid = $bioLid ?? null;
@endphp

<div class="mb-3">
    <label for="education_organization">Образовательная организация</label>
    <input
        name="education_organization"
        id="education_organization"
        type="text"
        class="form-control"
        value="{{ old('education_organization', $bioLid->education_organization ?? '') }}"
    >
    @error('education_organization')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="education_qualification">Полученная квалификация</label>
    <input
        name="education_qualification"
        id="education_qualification"
        type="text"
        class="form-control"
        value="{{ old('education_qualification', $bioLid->education_qualification ?? '') }}"
    >
    @error('education_qualification')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="diploma_series_number">Серия и номер диплома</label>
        <input
            name="diploma_series_number"
            id="diploma_series_number"
            type="text"
            class="form-control"
            value="{{ old('diploma_series_number', $bioLid->diploma_series_number ?? '') }}"
        >
        @error('diploma_series_number')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="diploma_issued_at">Дата выдачи диплома</label>
        <input
            name="diploma_issued_at"
            id="diploma_issued_at"
            type="date"
            class="form-control"
            value="{{ old('diploma_issued_at', optional($bioLid->diploma_issued_at ?? null)->format('Y-m-d')) }}"
        >
        @error('diploma_issued_at')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
