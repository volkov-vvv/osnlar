@section('javascript')
    <script>
        (function () {
            var $startsAt = $('#starts_at');
            var $endsAt = $('#ends_at');
            var $duration = $('#studygroup-duration');
            var $workdays = $('#studygroup-workdays');

            function pluralize(value, one, few, many) {
                var n = Math.abs(value) % 100;
                var n1 = n % 10;
                if (n > 10 && n < 20) {
                    return many;
                }
                if (n1 > 1 && n1 < 5) {
                    return few;
                }
                if (n1 === 1) {
                    return one;
                }
                return many;
            }

            function formatDuration(totalDays) {
                var weeks = Math.floor(totalDays / 7);
                var days = totalDays % 7;
                var parts = [];

                if (weeks > 0) {
                    parts.push(weeks + ' ' + pluralize(weeks, 'неделя', 'недели', 'недель'));
                }
                if (days > 0 || weeks === 0) {
                    parts.push(days + ' ' + pluralize(days, 'день', 'дня', 'дней'));
                }

                return parts.join(' ');
            }

            function countWorkdays(start, end) {
                var workdays = 0;
                var current = new Date(start.getTime());

                while (current <= end) {
                    var day = current.getDay();
                    if (day !== 0 && day !== 6) {
                        workdays++;
                    }
                    current.setDate(current.getDate() + 1);
                }

                return workdays;
            }

            function updateDuration() {
                var startValue = $startsAt.val();
                var endValue = $endsAt.val();

                if (!startValue || !endValue) {
                    $duration.val('—');
                    $workdays.val('—');
                    return;
                }

                var start = new Date(startValue + 'T00:00:00');
                var end = new Date(endValue + 'T00:00:00');
                if (isNaN(start.getTime()) || isNaN(end.getTime()) || end < start) {
                    $duration.val('—');
                    $workdays.val('—');
                    return;
                }

                var totalDays = Math.round((end - start) / 86400000) + 1;
                var workdays = countWorkdays(start, end);

                $duration.val(formatDuration(totalDays));
                $workdays.val(workdays + ' ' + pluralize(workdays, 'день', 'дня', 'дней'));
            }

            $startsAt.on('change input', updateDuration);
            $endsAt.on('change input', updateDuration);
            updateDuration();
        })();
    </script>
@endsection
