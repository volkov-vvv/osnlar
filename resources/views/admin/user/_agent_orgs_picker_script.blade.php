<script>
    (function ($) {
        var catalog = {};
        try {
            catalog = JSON.parse($('#agent-orgs-catalog').text() || '{}') || {};
        } catch (e) {
            catalog = {};
        }

        function selectedIdsFromInputs() {
            return $('#agent-orgs-inputs input[name="org_ids[]"]')
                .map(function () { return String($(this).val()); })
                .get();
        }

        function renderSummary(ids) {
            var $summary = $('#agent-orgs-summary');
            var $count = $('#agent-orgs-count');
            $summary.empty();

            if (!ids.length) {
                $count.text('Не выбрано');
                $summary.append('<div class="text-muted small">Организации не выбраны.</div>');
                return;
            }

            $count.text('Выбрано: ' + ids.length);
            ids.forEach(function (id) {
                var title = catalog[String(id)] || ('#' + id);
                var $badge = $('<span class="badge badge-secondary mr-1 mb-1"></span>');
                $badge.append(document.createTextNode(title + ' '));
                $badge.append(
                    $('<a href="#" class="text-white agent-org-remove" title="Убрать">&times;</a>').attr('data-id', id)
                );
                $summary.append($badge);
            });
        }

        function writeInputs(ids) {
            var $inputs = $('#agent-orgs-inputs').empty();
            ids.forEach(function (id) {
                $inputs.append($('<input>', {
                    type: 'hidden',
                    name: 'org_ids[]',
                    value: id
                }));
            });
            renderSummary(ids);
        }

        function syncModalFromInputs() {
            var selected = selectedIdsFromInputs();
            $('.agent-org-checkbox').each(function () {
                $(this).prop('checked', selected.indexOf(String($(this).val())) !== -1);
            });
            $('#agentOrgsSearch').val('');
            filterList('');
        }

        function filterList(query) {
            var q = String(query || '').trim().toLowerCase();
            $('.agent-org-item').each(function () {
                var title = String($(this).data('title') || '');
                $(this).toggle(!q || title.indexOf(q) !== -1);
            });
        }

        function visibleCheckboxes() {
            return $('.agent-org-item:visible .agent-org-checkbox');
        }

        $('#agentOrgsModal').on('show.bs.modal', syncModalFromInputs);

        $('#agentOrgsSearch').on('input', function () {
            filterList($(this).val());
        });

        $('#agentOrgsSelectAll').on('click', function () {
            visibleCheckboxes().prop('checked', true);
        });

        $('#agentOrgsClearAll').on('click', function () {
            visibleCheckboxes().prop('checked', false);
        });

        $('#agentOrgsApply').on('click', function () {
            var ids = $('.agent-org-checkbox:checked')
                .map(function () { return String($(this).val()); })
                .get();
            writeInputs(ids);
            $('#agentOrgsModal').modal('hide');
        });

        $(document).on('click', '.agent-org-remove', function (e) {
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
