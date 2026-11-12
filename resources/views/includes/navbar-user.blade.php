@php
    $user = auth()->user();
    $displayName = trim(($user->name ?? '') . ' ' . ($user->lastname ?? ''));
    $roleLabel = $user->getRoles()[$user->role] ?? '';
@endphp
<li class="nav-item dropdown">
    <a href="#" class="navbar-user d-flex align-items-center text-dark" data-toggle="dropdown" aria-expanded="false">
        <img
            src="{{ $user->avatar ? url('storage/' . $user->avatar) : asset('dist/img/user2-160x160.jpg') }}"
            class="navbar-user-avatar img-circle elevation-1"
            alt="User Image"
        >
        <span class="navbar-user-meta d-none d-sm-flex flex-column ml-2">
            <span class="navbar-user-name">
                {{ $displayName !== '' ? $displayName : 'Профиль' }}
                <i class="navbar-user-caret fas fa-caret-down ml-1"></i>
            </span>
            @if($roleLabel !== '')
                <span class="navbar-user-role">{{ $roleLabel }}</span>
            @endif
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-user-menu">
        <a class="dropdown-item" href="{{ route('profile.edit') }}">
            <i class="fas fa-user mr-2"></i> Профиль
        </a>
        <a class="dropdown-item" href="{{ route('help.index') }}">
            <i class="fas fa-question-circle mr-2"></i> Справка
        </a>
        <div class="dropdown-divider"></div>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="dropdown-item">
                <i class="fas fa-sign-out-alt mr-2"></i> Выйти
            </button>
        </form>
    </div>
</li>
