{{--
  Expects: $pageUrl (string), e.g. 'cc.lid.index'
  Exposes: window.OsnlarFilterPersist.scheduleSave(data), .load(callback)
  Flushes pending save on page leave so filters/colvis are not lost to the 2s debounce.
--}}
<script>
    window.OsnlarFilterPersist = (function () {
        var timer = null;
        var pending = null;
        var pageUrl = @json($pageUrl);
        var token = @json(csrf_token());
        var saveUrl = @json(route('filters.save'));
        var getUrl = @json(route('filters.get'));

        function saveNow(state, sync) {
            pending = null;
            $.ajax({
                url: saveUrl,
                method: 'POST',
                async: !sync,
                data: {
                    _token: token,
                    page_url: pageUrl,
                    state: JSON.stringify(state)
                }
            });
        }

        function scheduleSave(data) {
            pending = data;
            clearTimeout(timer);
            timer = setTimeout(function () {
                saveNow(data, false);
            }, 2000);
        }

        function load(callback) {
            $.ajax({
                url: getUrl,
                data: { page_url: pageUrl },
                dataType: 'json',
                success: function (json) {
                    callback(json);
                },
                error: function () {
                    callback(null);
                }
            });
        }

        $(window).on('pagehide', function () {
            if (!pending) {
                return;
            }
            clearTimeout(timer);
            saveNow(pending, true);
        });

        return {
            scheduleSave: scheduleSave,
            load: load
        };
    })();
</script>
