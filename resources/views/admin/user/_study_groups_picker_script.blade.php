<script>
    (function ($) {
        var catalog = {};
        try {
            catalog = JSON.parse($('#study-groups-catalog').text() || '{}') || {};
        } catch (e) {
            catalog = {};
        }

        function selectedIdsFromInputs() {
            return $('#study-groups-inputs input[name="study_group_ids[]"]')
                .map(function () { return String($(this).val()); })
                .get();
        }

        function renderSummary(ids) {
            var $summary = $('#study-groups-summary');
            var $count = $('#study-groups-count');
            $summary.empty();

            if (!ids.length) {
                $count.text('Не выбрано');
                $summary.append('<div class="text-muted small">Учебные группы не выбраны.</div>');
                return;
            }

            $count.text('Выбрано: ' + ids.length);
            ids.forEach(function (id) {
                var title = catalog[String(id)] || ('#' + id);
                var $badge = $('<span class="badge badge-secondary mr-1 mb-1"></span>');
                $badge.append(document.createTextNode(title + ' '));
                $badge.append(
                    $('<a href="#" class="text-white study-group-remove" title="Убрать">&times;</a>').attr('data-id', id)
                );
                $summary.append($badge);
            });
        }

        function writeInputs(ids) {
            var $inputs = $('#study-groups-inputs').empty();
            ids.forEach(function (id) {
                $inputs.append($('<input>', {
                    type: 'hidden',
                    name: 'study_group_ids[]',
                    value: id
                }));
            });
            renderSummary(ids);
        }

        function syncCheckedState($checkbox) {
            $checkbox.closest('.study-group-item').toggleClass('is-checked', $checkbox.prop('checked'));
        }

        function syncModalFromInputs() {
            var selected = selectedIdsFromInputs();
            $('.study-group-checkbox').each(function () {
                var $checkbox = $(this);
                $checkbox.prop('checked', selected.indexOf(String($checkbox.val())) !== -1);
                syncCheckedState($checkbox);
            });
            $('#studyGroupsSearch').val('');
            filterList('');
        }

        function filterList(query) {
            var q = String(query || '').trim().toLowerCase();
            $('.study-group-item').each(function () {
                var title = String($(this).data('title') || '');
                $(this).toggle(!q || title.indexOf(q) !== -1);
            });
        }

        function visibleCheckboxes() {
            return $('.study-group-item:visible .study-group-checkbox');
        }

        $('#studyGroupsModal').on('show.bs.modal', syncModalFromInputs);

        $('#studyGroupsSearch').on('input', function () {
            filterList($(this).val());
        });

        $(document).on('change', '.study-group-checkbox', function () {
            syncCheckedState($(this));
        });

        $('#studyGroupsSelectAll').on('click', function () {
            visibleCheckboxes().prop('checked', true).each(function () {
                syncCheckedState($(this));
            });
        });

        $('#studyGroupsClearAll').on('click', function () {
            visibleCheckboxes().prop('checked', false).each(function () {
                syncCheckedState($(this));
            });
        });

        $('#studyGroupsApply').on('click', function () {
            var ids = $('.study-group-checkbox:checked')
                .map(function () { return String($(this).val()); })
                .get();
            writeInputs(ids);
            $('#studyGroupsModal').modal('hide');
        });

        $(document).on('click', '.study-group-remove', function (e) {
            e.preventDefault();
            var removeId = String($(this).data('id'));
            var ids = selectedIdsFromInputs().filter(function (id) {
                return id !== removeId;
            });
            writeInputs(ids);
        });

        renderSummary(selectedIdsFromInputs());
    })(jQuery);
</script>
