@php
    $selectedStatus = old('status', $selectedStatus ?? \App\Models\ContractWorkflowStatus::DRAFT);
    $statusOptions = $statusOptions ?? \App\Models\ContractWorkflowStatus::LABELS;
@endphp

<div class="mb-3">
    <label for="status">Статус</label>
    <select name="status" id="status" class="form-control" style="max-width: 16rem;" required>
        @foreach($statusOptions as $value => $label)
            <option value="{{ $value }}" {{ (string) $selectedStatus === (string) $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>
