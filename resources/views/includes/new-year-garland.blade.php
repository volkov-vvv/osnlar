@if(!empty($newYearThemeEnabled))
    <div class="ny-garland" aria-hidden="true">
        <svg class="ny-garland__wire" viewBox="0 0 1000 18" preserveAspectRatio="none" focusable="false">
            <path d="M0 6 C 80 14, 160 2, 250 8 S 420 16, 500 7 S 680 2, 750 10 S 920 16, 1000 6"></path>
        </svg>
        <div class="ny-garland__lights">
            @for($i = 0; $i < 22; $i++)
                <span class="ny-garland__light"></span>
            @endfor
        </div>
    </div>
@endif
