<script>
    (function () {
        var $popup = null;

        function hidePopup() {
            if ($popup) {
                $popup.remove();
                $popup = null;
            }
        }

        $(document).on('mouseenter', '.course-short-title-hint', function () {
            hidePopup();
            var title = $(this).attr('data-full-title');
            if (!title) {
                return;
            }

            var rect = this.getBoundingClientRect();
            $popup = $('<div class="course-short-title-popup"></div>').text(title).appendTo('body');

            var popupWidth = $popup.outerWidth();
            var popupHeight = $popup.outerHeight();
            var left = rect.left + (rect.width / 2) - (popupWidth / 2);
            var top = rect.top - popupHeight - 8;

            if (left < 8) {
                left = 8;
            }
            if (left + popupWidth > window.innerWidth - 8) {
                left = window.innerWidth - popupWidth - 8;
            }
            if (top < 8) {
                top = rect.bottom + 8;
            }

            $popup.css({ left: left + 'px', top: top + 'px' });
        });

        $(document).on('mouseleave', '.course-short-title-hint', hidePopup);
        $(window).on('scroll.courseShortTitleHint resize.courseShortTitleHint', hidePopup);
    })();
</script>
