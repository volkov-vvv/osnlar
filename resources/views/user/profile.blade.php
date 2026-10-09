@extends('layouts.main2')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <style>
        .profile-form .form-control {
            border-color: #dee2e6;
        }
        .profile-form .form-control:focus {
            border-color: #ced4da;
            box-shadow: 0 0 0 0.2rem rgba(206, 212, 218, 0.35);
        }
    </style>
@endpush

@section('content')
    <div class="row pt-5 pb-5">
        <div class="col text-center">
            <h1>Профиль</h1>
        </div>
    </div>

    <main class="blog">
        <div class="container">
            <section class="featured-posts-section">
                <div class="row justify-content-center">
                    <div class="col-lg-8 fetured-post blog-post" data-aos="fade-right">
                        <div class="card">
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <form class="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4 text-center" id="avatar-current">
                                        @if($user->avatar)
                                            <img id="avatar-current-image" src="{{ url('storage/' . $user->avatar) }}" alt="Аватар" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                                        @else
                                            <div id="avatar-current-placeholder" class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                                <i class="bi bi-person" style="font-size: 3rem;"></i>
                                            </div>
                                            <img id="avatar-current-image" alt="Аватар" class="rounded-circle d-none" width="120" height="120" style="object-fit: cover;">
                                        @endif
                                    </div>

                                    <div class="mb-3 form-group">
                                        <label for="lastname">Фамилия</label>
                                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                                        @error('lastname')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 form-group">
                                        <label for="name">Имя</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" value="{{ $user->email ?: '—' }}" readonly>
                                    </div>

                                    <div class="mb-3 form-group">
                                        <label for="phone">Телефон</label>
                                        <input type="text" class="form-control" id="phone" value="{{ $user->phone ? '+' . ($user->phone_prefix ?: '7') . $user->phone : '—' }}" readonly>
                                    </div>

                                    <div class="mb-3 form-group">
                                        <label for="avatar-source">Аватарка</label>
                                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar-source" accept="image/jpeg,image/png,image/webp">
                                        <input type="file" id="avatar" name="avatar" class="d-none" tabindex="-1">
                                        <div class="form-text">JPG, PNG или WEBP. После выбора файла обрежьте квадратную область. Если файл не выбран, текущая аватарка сохранится.</div>
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

                                    <div class="mb-3 mt-4">
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                        <a class="btn btn-outline-secondary" href="{{ $homeUrl }}">Назад</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script>
        (function () {
            var sourceInput = document.getElementById('avatar-source');
            var avatarInput = document.getElementById('avatar');
            var cropImage = document.getElementById('avatar-crop-image');
            var cropWrap = document.getElementById('avatar-cropper-wrap');
            var currentImage = document.getElementById('avatar-current-image');
            var placeholder = document.getElementById('avatar-current-placeholder');
            var form = sourceInput.closest('form');
            var cropper = null;
            var objectUrl = null;
            var submitting = false;

            function destroyCropper() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                    objectUrl = null;
                }
                cropWrap.classList.add('d-none');
            }

            function showPreview(url) {
                currentImage.src = url;
                currentImage.classList.remove('d-none');
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }

            function applyCrop(done) {
                if (!cropper) {
                    if (done) done();
                    return;
                }

                var canvas = cropper.getCroppedCanvas({
                    width: 512,
                    height: 512,
                    imageSmoothingQuality: 'high'
                });

                canvas.toBlob(function (blob) {
                    if (!blob) {
                        return;
                    }

                    var file = new File([blob], 'avatar.jpg', {type: 'image/jpeg'});
                    var transfer = new DataTransfer();
                    transfer.items.add(file);
                    avatarInput.files = transfer.files;
                    showPreview(canvas.toDataURL('image/jpeg', 0.9));
                    destroyCropper();
                    sourceInput.value = '';

                    if (done) done();
                }, 'image/jpeg', 0.9);
            }

            sourceInput.addEventListener('change', function () {
                var file = this.files && this.files[0];
                if (!file) {
                    return;
                }

                if (!/^image\/(jpeg|png|webp)$/.test(file.type)) {
                    this.value = '';
                    window.alert('Допустимые форматы: JPG, PNG или WEBP');
                    return;
                }

                destroyCropper();
                objectUrl = URL.createObjectURL(file);
                cropImage.src = objectUrl;
                cropWrap.classList.remove('d-none');
                cropper = new Cropper(cropImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    dragMode: 'move',
                    background: false,
                    responsive: true
                });
            });

            document.getElementById('avatar-crop-apply').addEventListener('click', function () {
                applyCrop();
            });

            document.getElementById('avatar-crop-cancel').addEventListener('click', function () {
                destroyCropper();
                sourceInput.value = '';
            });

            form.addEventListener('submit', function (event) {
                if (!cropper || submitting) {
                    return;
                }

                event.preventDefault();
                applyCrop(function () {
                    submitting = true;
                    form.submit();
                });
            });
        })();
    </script>
@endsection
