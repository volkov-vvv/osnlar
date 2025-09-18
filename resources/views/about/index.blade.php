@extends('layouts.main2')
@section('content')

    <main class="blog">
        <div class="container">

            <section class="featured-posts-section">
                <div class="row pt-5" data-aos="fade-up">
                    <div class="row pb-5">
                    <div class="col ">
                        <h1 class="text-center">Сведения об организации</h1>
                    </div>
                    </div>
                    <div class="row pb-5">

                        <div class="col-md-3">
                            <!-- Tabs nav -->
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                                <a class="nav-link mb-3 p-3 shadow " id="v-pills-home-tab" data-toggle="pill"
                                   href="#v-pills-home"
                                   role="tab" type="button" aria-controls="v-pills-home" aria-selected="true">
                                    <i class="bi bi-house"></i>
                                    <span>Основные сведения</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-profile-tab" data-toggle="pill"
                                   href="#v-pills-profile"
                                   role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                    <i class="bi bi-file-earmark"></i>
                                    <span>Документы</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-messages-tab" data-toggle="pill"
                                   href="#v-pills-messages" role="tab" aria-controls="v-pills-messages"
                                   aria-selected="false">
                                    <i class="bi bi-person"></i>
                                    <span>Руководство. Педагогический (научно-педагогический) состав</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill"
                                   href="#v-pills-settings" role="tab" aria-controls="v-pills-settings"
                                   aria-selected="false">
                                    <i class="bi bi-pc-display-horizontal"></i>
                                    <span>Материально-техническое обеспечение и оснащенность</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-sreda-tab" data-toggle="pill"
                                   href="#v-pills-sreda"
                                   role="tab" aria-controls="v-pills-sreda" aria-selected="false">
                                    <i class="bi bi-person-wheelchair"></i>
                                    <span>Доступная среда</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-inet-tab" data-toggle="pill"
                                   href="#v-pills-inet"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-hdd-network"></i>
                                    <span>Деятельность в ИТ</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-services-tab" data-toggle="pill"
                                   href="#v-pills-services"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-journal-bookmark"></i>
                                    <span>Контакты</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-tech-tab" data-toggle="pill"
                                   href="#v-pills-tech"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-tools"></i>
                                    <span>Технические средства</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-admissions-tab" data-toggle="pill"
                                   href="#v-pills-admissions"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-ticket-perforated"></i>
                                    <span>Вакантные места для приема и перевода обучающихся</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-catering-tab" data-toggle="pill"
                                   href="#v-pills-catering"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-cup-hot"></i>
                                    <span>Организация питания в образовательной организации</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-global-tab" data-toggle="pill"
                                   href="#v-pills-global"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-globe"></i>
                                    <span>Международное сотрудничество</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-financial-tab" data-toggle="pill"
                                   href="#v-pills-financial"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-cash"></i>
                                    <span>Финансово-хозяйственная деятельность</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-tuition-tab" data-toggle="pill"
                                   href="#v-pills-tuition"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-wallet2"></i>
                                    <span>Платные образовательные услуги</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-education-tab" data-toggle="pill"
                                   href="#v-pills-education"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-mortarboard"></i>
                                    <span>Образование</span></a>
                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-grants-tab" data-toggle="pill"
                                   href="#v-pills-grants"
                                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                                    <i class="bi bi-person-raised-hand"></i>
                                    <span>Стипендии и меры поддержки обучающихся</span></a>
                            </div>
                        </div>
                        @foreach($abouts as $about)
                            <div class="col-md-9">
                                <!-- Tabs content -->
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade shadow rounded bg-white show active p-5" id="v-pills-home"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-home-tab">
                                        <h4 class="mb-4">Основные сведения</h4>
                                        <p class="mb-2">
                                            {!! $about->about_main !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-profile"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-profile-tab">
                                        <h4 class="mb-4">Документы</h4>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th scope="col">№</th>
                                                <th scope="col">Название документа</th>
                                                <th scope="col">Дата обновления</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($documents as $document)
                                                <tr>
                                                    <th scope="row">{{$document->id}}</th>
                                                    <td><a href="{{url('storage/' . $document->file)}}"
                                                           target="_blank">{{$document->title}}</a></td>
                                                    <td>{{$document->updated_at}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-messages"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-messages-tab">
                                        <h4 class="mb-4">Руководство. Педагогический (научно-педагогический)
                                            состав</h4>
                                        <p class="mb-2">
                                            {!! $about->about_management !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-settings"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-settings-tab">
                                        <h4 class=" mb-4">Материально-техническое обеспечение и
                                            оснащенность</h4>
                                        <p class="mb-2">
                                            {!! $about->about_computers !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-sreda"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-sreda-tab">
                                        <h4 class=" mb-4">Доступная среда</h4>
                                        <p class=" text-muted mb-2">
                                            {!! $about->about_all !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-inet"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-inet-tab">
                                        <h4 class=" mb-4">Деятельность в ИТ</h4>
                                        <p class=" text-muted mb-2">
                                            {!! $about->about_it !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-services"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-services-tab">
                                        <h4 class=" mb-4">Контакты</h4>
                                        <p class=" text-muted mb-2">
                                            {!! $about->about_services !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-tech"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-tech-tab">
                                        <h4 class=" mb-4">Технические средства</h4>
                                        <p class=" text-muted mb-2">
                                            {!! $about->about_tech !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-admissions"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-admissions-tab">
                                        <h4 class=" mb-4">Вакантные места для приема и перевода
                                            обучающихся</h4>
                                        <p class=" text-muted mb-2">
                                            {!! $about->about_admissions !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-catering"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-vak-catering">
                                        <h4 class=" mb-4">Организация питания в образовательной
                                            организации</h4>
                                        <p class=" text-muted mb-2">
                                            {!! $about->about_catering !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-global"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-vak-global">
                                        <h4 class=" mb-4">Международное сотрудничество</h4>
                                        <p class=" text-muted mb-2">
                                            {!! $about->about_global !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-financial"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-vak-financial">
                                        <h4 class="mb-4">Финансово-хозяйственная деятельность</h4>
                                        <p class="text-muted mb-2">
                                            {!! $about->about_financial !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-tuition"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-vak-tuition">
                                        <h4 class="mb-4">Платные образовательные услуги</h4>
                                        <p class="text-muted mb-2">
                                            {!! $about->about_tuition !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-education"
                                         role="tabpanel"
                                         aria-labelledby="v-pills-vak-education">
                                        <h4 class="mb-4">Образование</h4>
                                        <p class="text-muted mb-2">
                                            {!! $about->about_education !!}
                                        </p>
                                    </div>

                                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-grants"
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
                    <div class="row pb-5"></div>
                    <div class="row pb-5">
                        <p>  </p>
                    </div>
                </div>
            </section>


        </div>

    </main>

@endsection
