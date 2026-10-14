<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
    (function () {
        var sourceInput = document.getElementById('photo-source');
        var photoInput = document.getElementById('photo');
        var cropImage = document.getElementById('photo-crop-image');
        var cropWrap = document.getElementById('photo-cropper-wrap');
        var currentImage = document.getElementById('photo-current-image');
        var form = sourceInput.closest('form');
        var cropper = null;
        var objectUrl = null;
        var submitting = false;

        function resetSourceLabel() {
            var label = sourceInput.nextElementSibling;
            if (label) {
                label.textContent = 'Выберите файл';
            }
        }

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

                var file = new File([blob], 'photo.jpg', {type: 'image/jpeg'});
                var transfer = new DataTransfer();
                transfer.items.add(file);
                photoInput.files = transfer.files;
                showPreview(canvas.toDataURL('image/jpeg', 0.9));
                destroyCropper();
                sourceInput.value = '';
                resetSourceLabel();

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
                resetSourceLabel();
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

        document.getElementById('photo-crop-apply').addEventListener('click', function () {
            applyCrop();
        });

        document.getElementById('photo-crop-cancel').addEventListener('click', function () {
                destroyCropper();
                sourceInput.value = '';
                resetSourceLabel();
            });

        form.addEventListener('submit', function (event) {
            if (!cropper || submitting) {
                return;
            }

                event.preventDefault();
                applyCrop(function () {
                    if (window.jQuery) {
                        jQuery('.summernote').each(function () {
                            var $el = jQuery(this);
                            if ($el.next('.note-editor').length) {
                                $el.val($el.summernote('code'));
                            }
                        });
                    }
                    submitting = true;
                    form.submit();
                });
        });
    })();
</script>
