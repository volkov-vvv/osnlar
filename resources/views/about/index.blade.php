@extends('layouts.main2')

@push('styles')
<style>
    .about-page-title {
        color: #000;
    }
    .about-page .nav-pills .nav-link {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 0.85rem 1rem;
        border: 1px solid #d7e4f5;
        border-radius: 1rem;
        background: linear-gradient(135deg, #eef4fb 0%, #f8fbff 60%, #ffffff 100%);
        color: #094ca1;
        font-weight: 700;
        line-height: 1.3;
        box-shadow: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .about-page .nav-pills .nav-link:hover,
    .about-page .nav-pills .nav-link:focus,
    .about-page .nav-pills .nav-link.active,
    .about-page .nav-pills .nav-link.active:hover,
    .about-page .nav-pills .nav-link.active:focus {
        color: #094ca1;
        background: linear-gradient(135deg, #eef4fb 0%, #f8fbff 60%, #ffffff 100%);
        border-color: #094ca1;
        box-shadow: 0 6px 18px rgba(9, 76, 161, 0.1);
        text-decoration: none;
    }
    .about-nav-icon {
        flex: 0 0 2.75rem;
        width: 2.75rem;
        height: 2.75rem;
        margin-right: 0.75rem;
        border-radius: 50%;
        background: #fff;
        color: #094ca1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        box-shadow: 0 4px 14px rgba(9, 76, 161, 0.12);
    }
    .about-pane {
        border: 1px solid #e3e6ea;
        border-radius: 1rem;
        background: linear-gradient(135deg, #f3f4f6 0%, #f8f9fa 60%, #ffffff 100%);
        padding: 2rem 1.5rem;
    }
    .about-pane h4 {
        margin-bottom: 1.25rem;
        color: #094ca1;
        font-weight: 700;
    }
    .about-doc {
        display: flex;
        align-items: center;
        margin-bottom: 0.85rem;
        padding: 1.1rem 1.35rem;
        border: 1px solid #d7e4f5;
        border-radius: 1rem;
        background: #fff;
        color: inherit;
        text-decoration: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .about-doc:hover,
    .about-doc:focus {
        color: inherit;
        text-decoration: none;
        border-color: #094ca1;
        box-shadow: 0 6px 18px rgba(9, 76, 161, 0.1);
    }
    .about-doc-icon {
        flex: 0 0 3.5rem;
        width: 3.5rem;
        height: 3.5rem;
        margin-right: 1rem;
        border-radius: 50%;
        background: #eef4fb;
        color: #094ca1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }
    .about-doc-label,
    .about-doc-title {
        display: block;
    }
    .about-doc-label {
        margin-bottom: 0.15rem;
        color: #5c6b7a;
        font-size: 0.95rem;
        line-height: 1.3;
    }
    .about-doc-title {
        color: #094ca1;
        font-size: 1.15rem;
        font-weight: 700;
        line-height: 1.3;
    }
    .about-page .featured-posts-section {
        padding-bottom: 6rem;
    }
    @media (min-width: 768px) {
        .about-layout-row {
            align-items: flex-start;
            padding-bottom: 3rem;
        }
        .about-content-sticky {
            position: sticky;
            top: 110px;
            align-self: flex-start;
            max-height: calc(100vh - 130px);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding: 4px;
            margin: -4px;
        }
        .about-pane {
            padding: 2.25rem 2rem;
        }
    }
</style>
@endpush

@section('content')

    <main class="blog about-page">
        <div class="container">

            <section class="featured-posts-section">
                <div class="row pt-5 pb-4" data-aos="fade-up">
                    <div class="col">
                        <h2 class="about-page-title text-center d-none d-lg-block">Сведения об организации</h2>
                        <h3 class="about-page-title text-center d-lg-none">Сведения об организации</h3>
                    </div>
                </div>

                <div class="row pb-5 about-layout-row">
                    <div class="col-md-3">
                        <!-- Tabs nav -->
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill"
                               href="#v-pills-home"
                               role="tab" type="button" aria-controls="v-pills-home" aria-selected="true">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-house"></i></span>
                                <span>Основные сведения</span></a>
                            <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill"
                               href="#v-pills-profile"
                               role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-file-earmark"></i></span>
                                <span>Документы</span></a>
                            <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill"
                               href="#v-pills-messages" role="tab" aria-controls="v-pills-messages"
                               aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-person"></i></span>
                                <span>Руководство. Педагогический (научно-педагогический) состав</span></a>
                            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill"
                               href="#v-pills-settings" role="tab" aria-controls="v-pills-settings"
                               aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-pc-display-horizontal"></i></span>
                                <span>Материально-техническое обеспечение и оснащенность</span></a>
                            <a class="nav-link" id="v-pills-sreda-tab" data-toggle="pill"
                               href="#v-pills-sreda"
                               role="tab" aria-controls="v-pills-sreda" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-person-wheelchair"></i></span>
                                <span>Доступная среда</span></a>
                            <a class="nav-link" id="v-pills-inet-tab" data-toggle="pill"
                               href="#v-pills-inet"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-hdd-network"></i></span>
                                <span>Деятельность в ИТ</span></a>
                            <a class="nav-link" id="v-pills-services-tab" data-toggle="pill"
                               href="#v-pills-services"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-journal-bookmark"></i></span>
                                <span>Контакты</span></a>
                            <a class="nav-link" id="v-pills-tech-tab" data-toggle="pill"
                               href="#v-pills-tech"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-tools"></i></span>
                                <span>Технические средства</span></a>
                            <a class="nav-link" id="v-pills-admissions-tab" data-toggle="pill"
                               href="#v-pills-admissions"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-ticket-perforated"></i></span>
                                <span>Вакантные места для приема и перевода обучающихся</span></a>
                            <a class="nav-link" id="v-pills-catering-tab" data-toggle="pill"
                               href="#v-pills-catering"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-cup-hot"></i></span>
                                <span>Организация питания в образовательной организации</span></a>
                            <a class="nav-link" id="v-pills-global-tab" data-toggle="pill"
                               href="#v-pills-global"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-globe"></i></span>
                                <span>Международное сотрудничество</span></a>
                            <a class="nav-link" id="v-pills-financial-tab" data-toggle="pill"
                               href="#v-pills-financial"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-cash"></i></span>
                                <span>Финансово-хозяйственная деятельность</span></a>
                            <a class="nav-link" id="v-pills-tuition-tab" data-toggle="pill"
                               href="#v-pills-tuition"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-wallet2"></i></span>
                                <span>Платные образовательные услуги</span></a>
                            <a class="nav-link" id="v-pills-education-tab" data-toggle="pill"
                               href="#v-pills-education"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-mortarboard"></i></span>
                                <span>Образование</span></a>
                            <a class="nav-link" id="v-pills-grants-tab" data-toggle="pill"
                               href="#v-pills-grants"
                               role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                <span class="about-nav-icon" aria-hidden="true"><i class="bi bi-person-raised-hand"></i></span>
                                <span>Стипендии и меры поддержки обучающихся</span></a>
                        </div>
                    </div>

                    @foreach($abouts as $about)
                        <div class="col-md-9 about-content-sticky">
                            <!-- Tabs content -->
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade about-pane show active" id="v-pills-home"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-home-tab">
                                    <h4 class="mb-4">Основные сведения</h4>
                                    <p class="mb-2">
                                        {!! $about->about_main !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-profile"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-profile-tab">
                                    <h4>Документы</h4>
                                    @foreach($documents as $document)
                                        <a class="about-doc" href="{{ url('storage/' . $document->file) }}" target="_blank" rel="noopener">
                                            <span class="about-doc-icon" aria-hidden="true"><i class="fas fa-file-alt"></i></span>
                                            <span>
                                                <span class="about-doc-label">Обновлено {{ $document->updated_at->format('d.m.Y') }}</span>
                                                <span class="about-doc-title">{{ $document->title }}</span>
                                            </span>
                                        </a>
                                    @endforeach
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-messages"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-messages-tab">
                                    <h4 class="mb-4">Руководство. Педагогический (научно-педагогический)
                                        состав</h4>
                                    <p class="mb-2">
                                        {!! $about->about_management !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-settings"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-settings-tab">
                                    <h4 class=" mb-4">Материально-техническое обеспечение и
                                        оснащенность</h4>
                                    <p class="mb-2">
                                        {!! $about->about_computers !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-sreda"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-sreda-tab">
                                    <h4 class=" mb-4">Доступная среда</h4>
                                    <p class=" text-muted mb-2">
                                        {!! $about->about_all !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-inet"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-inet-tab">
                                    <h4 class=" mb-4">Деятельность в ИТ</h4>
                                    <p class=" text-muted mb-2">
                                        {!! $about->about_it !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-services"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-services-tab">
                                    <h4 class=" mb-4">Контакты</h4>
                                    <p class=" text-muted mb-2">
                                        {!! $about->about_services !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-tech"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-tech-tab">
                                    <h4 class=" mb-4">Технические средства</h4>
                                    <p class=" text-muted mb-2">
                                        {!! $about->about_tech !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-admissions"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-admissions-tab">
                                    <h4 class=" mb-4">Вакантные места для приема и перевода
                                        обучающихся</h4>
                                    <p class=" text-muted mb-2">
                                        {!! $about->about_admissions !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-catering"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-vak-catering">
                                    <h4 class=" mb-4">Организация питания в образовательной
                                        организации</h4>
                                    <p class=" text-muted mb-2">
                                        {!! $about->about_catering !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-global"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-vak-global">
                                    <h4 class=" mb-4">Международное сотрудничество</h4>
                                    <p class=" text-muted mb-2">
                                        {!! $about->about_global !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-financial"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-vak-financial">
                                    <h4 class="mb-4">Финансово-хозяйственная деятельность</h4>
                                    <p class="text-muted mb-2">
                                        {!! $about->about_financial !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-tuition"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-vak-tuition">
                                    <h4 class="mb-4">Платные образовательные услуги</h4>
                                    <p class="text-muted mb-2">
                                        {!! $about->about_tuition !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-education"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-vak-education">
                                    <h4 class="mb-4">Образование</h4>
                                    <p class="text-muted mb-2">
                                        {!! $about->about_education !!}
                                    </p>
                                </div>

                                <div class="tab-pane fade about-pane" id="v-pills-grants"
                                     role="tabpanel"
                                     aria-labelledby="v-pills-vak-grants">
                                    <h4 class="mb-4">Стипендии и меры поддержки обучающихся</h4>
                                    <p class="text-muted mb-2">
                                        {!! $about->about_grants !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

        </div>
    </main>

@endsection
