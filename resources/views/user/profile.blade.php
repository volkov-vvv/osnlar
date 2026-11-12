@extends('layouts.main2')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <style>
        .profile-card {
            border: 1px solid #ced4da;
            border-radius: 1rem;
            background: linear-gradient(135deg, #f7fbf8 0%, #ffffff 60%);
            overflow: hidden;
        }
        .profile-card .card-body {
            padding: 1.5rem;
        }
        .profile-section-title {
            margin: 0 0 1rem;
            color: #094ca1;
            font-size: 1.15rem;
            font-weight: 700;
        }
        .profile-hint {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .profile-form .form-control {
            border-color: #ced4da;
        }
        .profile-form .form-control:focus {
            border-color: #094ca1;
            box-shadow: 0 0 0 0.2rem rgba(9, 76, 161, 0.15);
        }
        .profile-form input[type="file"].form-control {
            height: 45px;
            padding: 0;
            line-height: 1.5;
            overflow: hidden;
        }
        .profile-form input[type="file"].form-control::file-selector-button,
        .profile-form input[type="file"].form-control::-webkit-file-upload-button {
            height: 100%;
            margin: 0;
            margin-inline-end: 0.75rem;
            padding: 0 0.9rem;
            border: 0;
            border-right: 1px solid #ced4da;
            border-radius: 0;
            background-color: #e9ecef;
            color: #212529;
            font: inherit;
            font-weight: 500;
            line-height: 1.5;
            cursor: pointer;
        }
        .profile-form input[type="file"].form-control:hover:not(:disabled):not([readonly])::file-selector-button,
        .profile-form input[type="file"].form-control:hover:not(:disabled):not([readonly])::-webkit-file-upload-button {
            background-color: #dde0e3;
        }
        .profile-card .btn-profile-save {
            background-color: #094ca1;
            border-color: #094ca1;
            color: #fff;
        }
        .profile-card .btn-profile-save:hover,
        .profile-card .btn-profile-save:focus {
            background-color: #073a7a;
            border-color: #073a7a;
            color: #fff;
        }
        .profile-avatar-wrap {
            width: 180px;
            height: 180px;
            margin: 0 auto;
        }
        .profile-avatar-wrap img,
        .profile-avatar-placeholder {
            width: 180px;
            height: 180px;
            object-fit: cover;
        }
        .profile-avatar-wrap img.profile-avatar-clickable {
            cursor: pointer;
        }
        .profile-avatar-placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ced4da;
            background: #f8f9fa;
            color: #6c757d;
        }
        #avatar-full-modal .modal-body {
            padding: 0;
            text-align: center;
            background: #000;
        }
        #avatar-full-modal .modal-body img {
            max-width: 100%;
            max-height: 90vh;
            width: auto;
            height: auto;
        }
    </style>
@endpush

@section('content')
    <div class="row pt-5 pb-4">
        <div class="col text-center">
            <h1>Профиль</h1>
        </div>
    </div>

    <main class="blog">
        <div class="container pb-5">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card profile-card">
                        <div class="card-body">
                            <h2 class="profile-section-title">Личные данные</h2>
                            <p class="profile-hint mb-4">Заполните профиль перед началом обучения. Email и телефон изменить на этой странице нельзя.</p>

                            <form class="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-4 text-center" id="avatar-current">
                                    <div class="profile-avatar-wrap">
                                        @if($user->avatar)
                                            <img id="avatar-current-image" src="{{ url('storage/' . $user->avatar) }}" alt="Фото" class="rounded-circle profile-avatar-clickable" title="Открыть фото" role="button" tabindex="0">
                                        @else
                                            <div id="avatar-current-placeholder" class="rounded-circle profile-avatar-placeholder">
                                                <i class="bi bi-person" style="font-size: 4.5rem;"></i>
                                            </div>
                                            <img id="avatar-current-image" alt="Фото" class="rounded-circle d-none">
                                        @endif
                                    </div>
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
                                    <label for="middlename">Отчество</label>
                                    <input type="text" class="form-control @error('middlename') is-invalid @enderror" id="middlename" name="middlename" value="{{ old('middlename', $user->middlename) }}">
                                    @error('middlename')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-row">
                                    <div class="mb-3 form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" value="{{ $user->email ?: '—' }}" readonly>
                                    </div>
                                    <div class="mb-3 form-group col-md-6">
                                        <label for="phone">Телефон</label>
                                        <input type="text" class="form-control" id="phone" value="{{ $user->phone ? '+' . ($user->phone_prefix ?: '7') . $user->phone : '—' }}" readonly>
                                    </div>
                                </div>

                                <div class="mb-3 form-group">
                                    <label for="avatar-source">Фото</label>
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar-source" accept="image/jpeg,image/png,image/webp">
                                    <input type="file" id="avatar" name="avatar" class="d-none" tabindex="-1">
                                    <div class="form-text profile-hint">JPG, PNG или WEBP. После выбора файла обрежьте квадратную область. Если файл не выбран, текущая аватарка сохранится.</div>
                                    @error('avatar')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <div id="avatar-cropper-wrap" class="d-none mt-3">
                                        <div style="max-height: 420px;">
                                            <img id="avatar-crop-image" alt="Кадрирование" style="max-width: 100%;">
                                        </div>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-profile-save" id="avatar-crop-apply">Применить кадр</button>
                                            <button type="button" class="btn btn-outline-secondary" id="avatar-crop-cancel">Отмена</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0 mt-4">
                                    <button type="submit" class="btn btn-profile-save">Сохранить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="avatar-full-modal" tabindex="-1" role="dialog" aria-labelledby="avatar-full-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="avatar-full-modal-label">Фото</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="avatar-full-image" src="" alt="Фото">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script>
        (function () {
            var sourceInput = document.getElementById('avatar-source');
            var avatarInput = document.getElementById('avatar');
            var cropImage = document.getElementById('avatar-crop-image');
            var cropWrap = document.getElementById('avatar-cropper-wrap');
            var currentImage = document.getElementById('avatar-current-image');
            var placeholder = document.getElementById('avatar-current-placeholder');
            var fullImage = document.getElementById('avatar-full-image');
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

            function enableAvatarOpen() {
                currentImage.classList.add('profile-avatar-clickable');
                currentImage.setAttribute('title', 'Открыть фото');
                currentImage.setAttribute('role', 'button');
                currentImage.setAttribute('tabindex', '0');
            }

            function openAvatarFull() {
                if (!currentImage || !currentImage.src || currentImage.classList.contains('d-none')) {
                    return;
                }
                fullImage.src = currentImage.src;
                if (window.jQuery) {
                    window.jQuery('#avatar-full-modal').modal('show');
                }
            }

            function showPreview(url) {
                currentImage.src = url;
                currentImage.classList.remove('d-none');
                enableAvatarOpen();
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }

            currentImage.addEventListener('click', openAvatarFull);
            currentImage.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openAvatarFull();
                }
            });

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

            document.querySelectorAll('[data-dismiss="alert"]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var alert = this.closest('.alert');
                    if (alert) {
                        alert.remove();
                    }
                });
            });
        })();
    </script>
@endpush
