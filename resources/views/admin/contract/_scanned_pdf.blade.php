@php
    /** @var \Illuminate\Database\Eloquent\Model&\App\Models\Concerns\HasScannedPdf $model */
    $hasScan = $model->hasScannedPdf();
    $modalId = 'scannedPdfModal_'.$model->getTable().'_'.$model->getKey();
@endphp

<button
    type="button"
    class="btn {{ $hasScan ? 'btn-outline-success' : 'btn-outline-secondary' }}"
    data-toggle="modal"
    data-target="#{{ $modalId }}"
>
    <i class="fas fa-file-pdf mr-1"></i>
    Скан оригинала
    @if($hasScan)
        <i class="fas fa-check-circle ml-1"></i>
    @endif
</button>

<div
    class="modal fade"
    id="{{ $modalId }}"
    tabindex="-1"
    role="dialog"
    aria-labelledby="{{ $modalId }}_label"
    aria-hidden="true"
    @if($errors->has('scanned_pdf')) data-open-on-load="1" @endif
>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="{{ $modalId }}_label">Скан оригинала</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">PDF подписанного документа</p>

                @error('scanned_pdf')
                    <div class="alert alert-danger py-2">{{ $message }}</div>
                @enderror

                @if($hasScan)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <a href="{{ $downloadUrl }}" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-download mr-1"></i> Скачать скан
                        </a>
                        <form method="post" action="{{ $destroyUrl }}" class="d-inline" onsubmit="return confirm('Удалить скан оригинала?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-trash mr-1"></i> Удалить
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-muted mb-3">Скан ещё не загружен</div>
                @endif

                <form method="post" action="{{ $uploadUrl }}" enctype="multipart/form-data" id="{{ $modalId }}_form">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="scanned_pdf_{{ $modalId }}">
                            {{ $hasScan ? 'Заменить PDF' : 'Загрузить PDF' }}
                        </label>
                        <input
                            type="file"
                            name="scanned_pdf"
                            id="scanned_pdf_{{ $modalId }}"
                            class="form-control-file"
                            accept="application/pdf,.pdf"
                            required
                        >
                        <small class="form-text text-muted">Только PDF, до 20 МБ</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Закрыть</button>
                <button type="submit" form="{{ $modalId }}_form" class="btn btn-primary">
                    <i class="fas fa-upload mr-1"></i>
                    {{ $hasScan ? 'Заменить' : 'Загрузить' }}
                </button>
            </div>
        </div>
    </div>
</div>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.modal[data-open-on-load="1"]').forEach(function (modal) {
                if (window.jQuery) {
                    window.jQuery(modal).modal('show');
                }
            });
        });
    </script>
@endonce
