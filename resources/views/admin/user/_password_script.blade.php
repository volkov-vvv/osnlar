<script>
    (function () {
        var passwordInput = document.getElementById('user-password');
        var toggleBtn = document.getElementById('user-password-toggle');
        var toggleIcon = document.getElementById('user-password-toggle-icon');
        var generateBtn = document.getElementById('user-password-generate');

        if (!passwordInput || !toggleBtn || !toggleIcon) {
            return;
        }

        function setPasswordVisible(visible) {
            passwordInput.type = visible ? 'text' : 'password';
            toggleIcon.classList.toggle('fa-eye', !visible);
            toggleIcon.classList.toggle('fa-eye-slash', visible);
        }

        toggleBtn.addEventListener('click', function () {
            setPasswordVisible(passwordInput.type === 'password');
        });

        if (generateBtn) {
            generateBtn.addEventListener('click', function () {
                var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                var password = '';
                for (var i = 0; i < 8; i++) {
                    password += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                passwordInput.value = password;
                setPasswordVisible(true);
                passwordInput.focus();
            });
        }
    })();
</script>
