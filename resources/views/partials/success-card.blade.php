@php
    $successTitle = $title ?? 'Заявка принята!';
    $successSubtitle = $subtitle ?? 'Поздравляем!';
    $successLead = $lead ?? 'Скоро на указанный Вами телефон позвонит сотрудник Контакт-центра. Ожидайте звонка.';
    $successCourse = $courseTitle ?? null;
    $successHomeUrl = $homeUrl ?? route('main.index');
@endphp

<style>
    .app-success {
        padding: 3rem 0 5rem;
    }
    .app-success__card {
        max-width: 40rem;
        margin: 0 auto;
        padding: 2.5rem 1.75rem 2rem;
        text-align: center;
        border: 1px solid #c5e6ce;
        border-radius: 1.25rem;
        background: linear-gradient(160deg, #e5f6ea 0%, #f3fbf5 45%, #ffffff 100%);
        box-shadow: 0 14px 36px rgba(31, 138, 66, 0.1);
    }
    .app-success__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4.25rem;
        height: 4.25rem;
        margin-bottom: 1.25rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #1f8a42 0%, #28a745 100%);
        color: #fff;
        font-size: 1.85rem;
        box-shadow: 0 10px 22px rgba(31, 138, 66, 0.28);
    }
    .app-success__title {
        margin: 0 0 0.5rem;
        color: #145c2e;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .app-success__subtitle {
        margin: 0 0 1.25rem;
        color: #1b7a3a;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.3;
    }
    .app-success__course {
        margin: 0 auto 1.35rem;
        max-width: 34rem;
        padding: 0.75rem 1rem;
        border: 1px solid rgba(31, 138, 66, 0.18);
        border-radius: 0.85rem;
        background: rgba(255, 255, 255, 0.75);
        color: #1f2937;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.4;
    }
    .app-success__text {
        margin: 0 auto 0.85rem;
        max-width: 32rem;
        color: #3f6b4d;
        font-size: 1.05rem;
        line-height: 1.55;
    }
    .app-success__contacts {
        margin: 1.25rem 0 1.75rem;
        color: #3f6b4d;
        font-size: 1rem;
        line-height: 1.55;
    }
    .app-success__contacts a {
        color: #1b7a3a;
        font-weight: 700;
        text-decoration: none;
    }
    .app-success__contacts a:hover,
    .app-success__contacts a:focus {
        color: #145c2e;
        text-decoration: underline;
    }
    .app-success__btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 11rem;
        padding: 0.75rem 1.5rem;
        border: 1px solid #176b33;
        border-radius: 0.8rem;
        background: linear-gradient(135deg, #1f8a42 0%, #28a745 100%);
        color: #fff;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none;
        box-shadow: 0 10px 20px rgba(31, 138, 66, 0.22);
        transition: background 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
    }
    .app-success__btn:hover,
    .app-success__btn:focus {
        background: linear-gradient(135deg, #176b33 0%, #1f8a42 100%);
        color: #fff;
        text-decoration: none;
        box-shadow: 0 12px 24px rgba(23, 107, 51, 0.28);
        transform: translateY(-1px);
    }
    @media (max-width: 575.98px) {
        .app-success {
            padding: 2rem 0 3.5rem;
        }
        .app-success__card {
            padding: 2rem 1.15rem 1.5rem;
        }
        .app-success__title {
            font-size: 1.55rem;
        }
        .app-success__subtitle {
            font-size: 1.1rem;
        }
        .app-success__btn {
            width: 100%;
        }
    }
</style>

<main class="blog app-success">
    <div class="container">
        <div class="app-success__card" data-aos="fade-up">
            <div class="app-success__icon" aria-hidden="true">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="app-success__title">{{ $successTitle }}</h1>
            <h2 class="app-success__subtitle">{{ $successSubtitle }}</h2>

            @if(!empty($successCourse))
                <p class="app-success__course">{{ $successCourse }}</p>
            @endif

            <p class="app-success__text">{{ $successLead }}</p>
            <p class="app-success__contacts">
                Вы всегда можете написать нам на почту
                <a href="mailto:edu@partnerdpo.ru">edu@partnerdpo.ru</a>
                или позвонить по телефону
                <a href="tel:+74996096020">+7 (499) 609-60-20</a>.
            </p>

            <a href="{{ $successHomeUrl }}" class="app-success__btn">На главную</a>
        </div>
    </div>
</main>
