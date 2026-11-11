@extends('admin.layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Редактирование пользователя: "{{ $user->name }}"</h1>
                </div>
            </div>
        </div>
    </div>

    @php
        if (old('org_ids') !== null) {
            $selectedOrgIds = collect(old('org_ids'))->map(fn ($id) => (int) $id)->all();
        } else {
            $selectedOrgTitles = $user->orgs
                ->map(fn ($org) => mb_strtolower(trim((string) $org->organization_title)))
                ->filter()
                ->all();
            $selectedOrgIds = $organizations
                ->filter(function ($organization) use ($selectedOrgTitles) {
                    $orgTitleKey = mb_strtolower(trim((string) $organization->organization_title));

                    return $orgTitleKey !== '' && in_array($orgTitleKey, $selectedOrgTitles, true);
                })
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        $selectedStudyGroupIds = collect(
            old('study_group_ids', $user->studyGroups->pluck('id')->all())
        )->map(fn ($id) => (int) $id)->all();
    @endphp

    <section class="content">
        <div class="container-fluid">
            <form
                action="{{ route('admin.user.update', $user->id) }}"
                method="post"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PATCH')
                @include('admin.user._form', [
                    'isEdit' => true,
                    'user' => $user,
                    'selectedOrgIds' => $selectedOrgIds,
                    'selectedStudyGroupIds' => $selectedStudyGroupIds,
                ])
            </form>
        </div>
    </section>

    @include('admin.user._agent_orgs_modal', [
        'organizations' => $organizations,
        'selectedOrgIds' => $selectedOrgIds,
    ])
    @include('admin.user._study_groups_modal', [
        'studyGroups' => $studyGroups,
        'selectedStudyGroupIds' => $selectedStudyGroupIds,
    ])
@endsection

@section('javascript')
    @include('admin.user._avatar_script')
    @include('admin.user._agent_orgs_picker_script')
    @include('admin.user._study_groups_picker_script')
    @include('admin.user._password_script')
    <script>
        function toggleRoleForms() {
            var role = $('#role option:selected').val();
            var isAgent = role == 2;
            var isCompany = role == 3;

            $('#agent-form').toggle(isAgent);
            $('#company-form').toggle(isCompany);
            $('#role-data-empty').toggle(!isAgent && !isCompany);
        }

        $('#role').on('change', toggleRoleForms);

        $(document).ready(function () {
            toggleRoleForms();

            $('[data-mask]').inputmask();

            var input = document.querySelector('#phone');
            const iti = window.intlTelInput(input, {
                strictMode: true,
                showSelectedDialCode: true,
                nationalMode: false,
                initialCountry: 'ru',
                onlyCountries: ['ru', 'by'],
                i18n: {
                    ru: 'Россия',
                    by: 'Беларусь',
                },
                hiddenInput: () => ({ phone: 'phone' }),
                utilsScript: "{{ asset('js/intlTelInput/utils.js') }}?1712939239769"
            });

            input.addEventListener('countrychange', () => {
                $('#phone_prefix').val(iti.getSelectedCountryData().dialCode);
            });
        });
    </script>
@endsection
