<div class="mb-3 form-group">
    <label for="avatar-source">Фото</label>
    <div class="mb-2">
        <img
            id="avatar-current-image"
            alt="Фото"
            class="img-circle elevation-1 {{ empty($avatar) ? 'd-none' : '' }}"
            width="160"
            height="160"
            style="object-fit: cover;"
            @if(!empty($avatar)) src="{{ url('storage/' . $avatar) }}" @endif
        >
        @if(empty($avatar))
            <span
                id="avatar-current-placeholder"
                class="d-inline-block rounded-circle"
                style="width: 160px; height: 160px; background-color: #d6d8db;"
            ></span>
        @endif
    </div>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror" id="avatar-source" accept="image/jpeg,image/png,image/webp">
            <label class="custom-file-label" for="avatar-source">Выберите файл</label>
        </div>
    </div>
    <input type="file" id="avatar" name="avatar" class="d-none" tabindex="-1">
    <div class="form-text text-muted">JPG, PNG или WEBP. После выбора файла обрежьте квадратную область. Если файл не выбран, текущее фото сохранится.</div>
    @error('avatar')
    <div class="text-danger">{{ $message }}</div>
    @enderror

    <div id="avatar-cropper-wrap" class="d-none mt-3">
        <div style="max-height: 420px;">
            <img id="avatar-crop-image" alt="Кадрирование" style="max-width: 100%;">
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-primary" id="avatar-crop-apply">Применить кадр</button>
            <button type="button" class="btn btn-outline-secondary" id="avatar-crop-cancel">Отмена</button>
        </div>
    </div>
</div>
