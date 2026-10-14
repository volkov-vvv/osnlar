<div class="mb-3 form-group">
    <label for="photo-source">Фото</label>
    <div class="mb-2">
        <img
            id="photo-current-image"
            alt="Фото автора"
            class="rounded {{ empty($photo) ? 'd-none' : '' }}"
            width="160"
            height="160"
            style="object-fit: cover;"
            @if(!empty($photo)) src="{{ url('storage/' . $photo) }}" @endif
        >
    </div>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" id="photo-source" accept="image/jpeg,image/png,image/webp">
            <label class="custom-file-label" for="photo-source">Выберите файл</label>
        </div>
    </div>
    <input type="file" id="photo" name="photo" class="d-none" tabindex="-1">
    <div class="form-text text-muted">JPG, PNG или WEBP. После выбора файла обрежьте квадратную область.</div>
    @error('photo')
    <div class="text-danger">{{ $message }}</div>
    @enderror

    <div id="photo-cropper-wrap" class="d-none mt-3">
        <div style="max-height: 420px;">
            <img id="photo-crop-image" alt="Кадрирование" style="max-width: 100%;">
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-primary" id="photo-crop-apply">Применить кадр</button>
            <button type="button" class="btn btn-outline-secondary" id="photo-crop-cancel">Отмена</button>
        </div>
    </div>
</div>
