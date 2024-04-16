@extends('layouts.main-about')
@section('content')
    <div class="row pt-5 pb-5">
        <div class="col text-center">
            <h3>Сведения об образовательной организации</h3>
        </div>
    </div>
    <div class="row">

        <div class="col-md-3">
            <!-- Tabs nav -->
            <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist"
                 aria-orientation="vertical">
                <a class="nav-link mb-3 p-3 shadow active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home"
                   role="tab" aria-controls="v-pills-home" aria-selected="true">
                    <i class="fa-solid fa-house mr-2"></i>
                    <span class="font-weight-bold small text-uppercase">Основные сведения</span></a>

                <a class="nav-link mb-3 p-3 shadow" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile"
                   role="tab" aria-controls="v-pills-profile" aria-selected="false">
                    <i class="fa-regular fa-folder-open mr-2"></i>
                    <span class="font-weight-bold small text-uppercase">Документы</span></a>

                <a class="nav-link mb-3 p-3 shadow" id="v-pills-messages-tab" data-toggle="pill"
                   href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                    <i class="fa-solid fa-user-tie mr-2"></i>
                    <span class="font-weight-bold small text-uppercase">Руководство. Педагогический (научно-педагогический) состав</span></a>

                <a class="nav-link mb-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill"
                   href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                    <i class="fa-solid fa-desktop mr-2"></i>
                    <span class="font-weight-bold small text-uppercase">Материально-техническое обеспечение и оснащенность</span></a>
                <a class="nav-link mb-3 p-3 shadow" id="v-pills-sreda-tab" data-toggle="pill" href="#v-pills-sreda"
                   role="tab" aria-controls="v-pills-sreda" aria-selected="false">
                    <i class="fa-solid fa-wheelchair mr-2"></i>
                    <span class="font-weight-bold small text-uppercase">Доступная среда</span></a>
                <a class="nav-link mb-3 p-3 shadow" id="v-pills-inet-tab" data-toggle="pill" href="#v-pills-inet"
                   role="tab" aria-controls="v-pills-inet" aria-selected="false">
                    <i class="fa-solid fa-globe mr-2"></i>
                    <span class="font-weight-bold small text-uppercase">Деятельность в ИТ</span></a>
            </div>
        </div>
        @foreach($abouts as $about)
            <div class="col-md-9">
                <!-- Tabs content -->
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade shadow rounded bg-white show active p-5" id="v-pills-home" role="tabpanel"
                         aria-labelledby="v-pills-home-tab">
                        <h4 class="font-italic mb-4">Основные сведения</h4>
                        <p class="font-italic text-muted mb-2">
                            {!! $about->about_main !!}
                        </p>
                    </div>

                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-profile" role="tabpanel"
                         aria-labelledby="v-pills-profile-tab">
                        <h4 class="font-italic mb-4">Документы</h4>
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
                                    <td><a href="{{url('storage/' . $document->file)}}" target="_blank">{{$document->title}}</a></td>
                                    <td>{{$document->updated_at}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>

                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-messages" role="tabpanel"
                         aria-labelledby="v-pills-messages-tab">
                        <h4 class="font-italic mb-4">Руководство. Педагогический (научно-педагогический) состав</h4>
                        <p class="font-italic text-muted mb-2">
                            {!! $about->about_management !!}
                        </p>
                    </div>

                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-settings" role="tabpanel"
                         aria-labelledby="v-pills-settings-tab">
                        <h4 class="font-italic mb-4">Материально-техническое обеспечение и оснащенность</h4>
                        <p class="font-italic text-muted mb-2">
                            {!! $about->about_computers !!}
                        </p>
                    </div>

                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-sreda" role="tabpanel"
                         aria-labelledby="v-pills-sreda-tab">
                        <h4 class="font-italic mb-4">Доступная среда</h4>
                        <p class="font-italic text-muted mb-2">
                            {!! $about->about_all !!}
                        </p>
                    </div>

                    <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-inet" role="tabpanel"
                         aria-labelledby="v-pills-inet-tab">
                        <h4 class="font-italic mb-4">Деятельность в ИТ</h4>
                        <p class="font-italic text-muted mb-2">
                            {!! $about->about_it !!}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
