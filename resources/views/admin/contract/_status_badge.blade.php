@php
    $status = $status ?? \App\Models\ContractWorkflowStatus::DRAFT;
    $label = \App\Models\ContractWorkflowStatus::label($status);
    $badgeClass = \App\Models\ContractWorkflowStatus::badgeClass($status);
    $iconClass = \App\Models\ContractWorkflowStatus::iconClass($status);
@endphp
<span class="{{ $badgeClass }}"><i class="{{ $iconClass }} mr-1"></i>{{ $label }}</span>
