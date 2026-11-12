<script>
    (function () {
        const lastnameInput = document.querySelector('input[name="lastname"]');
        const firstnameInput = document.querySelector('input[name="firstname"]');
        const middlenameInput = document.querySelector('input[name="middlename"]');
        const nameInput = document.querySelector('input[name="name"]');

        if (!lastnameInput || !firstnameInput || !middlenameInput || !nameInput) {
            return;
        }

        function buildPseudonym() {
            const lastname = lastnameInput.value.trim();
            const firstname = firstnameInput.value.trim();
            const middlename = middlenameInput.value.trim();

            if (!lastname) {
                return '';
            }

            let pseudonym = lastname;

            if (firstname) {
                pseudonym += ' ' + firstname.charAt(0).toLocaleUpperCase('ru-RU') + '.';

                if (middlename) {
                    pseudonym += middlename.charAt(0).toLocaleUpperCase('ru-RU') + '.';
                }
            }

            return pseudonym;
        }

        const isReadonly = nameInput.readOnly;
        let manualEdit = false;
        const initialAuto = buildPseudonym();
        if (!isReadonly && nameInput.value.trim() !== '' && nameInput.value.trim() !== initialAuto) {
            manualEdit = true;
        }

        if (!isReadonly) {
            nameInput.addEventListener('input', function () {
                manualEdit = true;
            });
        }

        function syncPseudonym() {
            if (manualEdit) {
                return;
            }

            nameInput.value = buildPseudonym();
        }

        [lastnameInput, firstnameInput, middlenameInput].forEach(function (input) {
            input.addEventListener('input', syncPseudonym);
        });

        if (!manualEdit) {
            syncPseudonym();
        }
    })();
</script>
