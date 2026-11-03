<footer class="edica-footer mt-auto">
    <div class="footer-glass">
        <div class="row footer-widget-area">
            <div class="col-md-4 footer-col">
                <h3 class="footer-col-title">Контакты</h3>
                <p class="footer-org-name">ООО «Центр повышения квалификации <br>и профессиональной подготовки «Основание»</p>
                <p class="footer-text">129110, г. Москва,<br>ул. Гиляровского д. 57, стр. 1</p>
                <p class="footer-text">
                    <a class="footer-link" href="tel:+74996096020">+7 (499) 609-60-20</a>
                </p>
                <p class="footer-text">
                    <a class="footer-link" href="mailto:edu@partnerdpo.ru">edu@partnerdpo.ru</a>
                </p>
                <nav class="footer-social-links" aria-label="Социальные сети">
                    <a href="{{ url('https://vk.com/osnovanie_study') }}" target="_blank" rel="noopener" aria-label="ВКонтакте"><i class="fab fa-vk" aria-hidden="true"></i></a>
                    <a href="{{ url('https://ok.ru/group/70000005562055') }}" target="_blank" rel="noopener" aria-label="Одноклассники"><i class="fab fa-odnoklassniki" aria-hidden="true"></i></a>
                    <a href="{{ url('https://t.me/osnovanie_study') }}" target="_blank" rel="noopener" aria-label="Telegram"><i class="fab fa-telegram" aria-hidden="true"></i></a>
                    <a href="{{ url('https://max.ru/id7751117260_biz') }}" target="_blank" rel="noopener" aria-label="MAX">
                        <svg class="footer-social-max" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
                            <path fill="currentColor" fill-rule="evenodd" d="M12 2.2C6.7 2.2 2.4 6.3 2.4 11.35c0 2.9 1.35 5.5 3.5 7.25L4.1 21.8l3.6-1.4c1.3.5 2.75.8 4.3.8 5.3 0 9.6-4.1 9.6-9.15S17.3 2.2 12 2.2Zm0 3.35c3.55 0 6.4 2.7 6.4 6.05S15.55 17.65 12 17.65c-1.1 0-2.15-.25-3.05-.7l-2.45.95.9-2.2c-1.15-1.15-1.85-2.7-1.85-4.4 0-3.35 2.85-6.05 6.45-6.05Z"/>
                        </svg>
                    </a>
                </nav>
            </div>
            <div class="col-md-4 footer-col">
                <h3 class="footer-col-title">Реквизиты</h3>
                <p class="footer-text">ИНН/КПП: 7751117260/770201001</p>
                <p class="footer-text">ОГРН: 5177746204763</p>
                <h3 class="footer-col-title footer-col-title--spaced">Режим работы</h3>
                <p class="footer-text">Пн–Пт, 9:00 – 18:00</p>
            </div>
            <div class="col-md-4 footer-col">
                <h3 class="footer-col-title">Документы</h3>
                <nav class="footer-nav">
                    <a href="{{ asset('files/reg_org.pdf') }}" class="nav-link" target="_blank" rel="noopener">Карточка организации</a>
                    <a href="{{ asset('files/lic.pdf') }}" class="nav-link" target="_blank" rel="noopener">Образовательная лицензия</a>
                    <a href="{{ route('author.index') }}" class="nav-link">Авторы / Эксперты</a>
                </nav>
            </div>
        </div>
        <div class="footer-bottom-content">
            <nav class="nav footer-bottom-nav">
                <a href="{{ asset('files/lic.pdf') }}" target="_blank" rel="noopener">Образовательная лицензия №Л035-01298-77/00180209 от 29.12.2021</a>
            </nav>
            <p class="footer-copy mb-0">© {{ date('Y') }} Основание. Все права защищены</p>
        </div>
    </div>
</footer>
