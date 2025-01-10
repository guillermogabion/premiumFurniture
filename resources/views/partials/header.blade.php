<header class="sticky-top custom-bg text-white shadow-sm @if(auth()->user()->role !== 'client') d-none @endif">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="#">
                <img
                    src="{{ asset('img/kaiadmin/logos.png') }}" alt="Image Preview"
                    style="width: auto; height: 50px; border-radius: 50%; object-fit: cover; padding: 5px;"
                    class="navbar-brand" />
                <span class="text-sm text-nowrap text-white fs-6 logo-text navbar-brand">PREMIER FURNITURE PH</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link text-white" href="#home" onclick="window.location='{{route('home')}}'">Back Home</a>
                    </li>
                    <li class="nav-item topbar-user dropdown hidden-caret" style="list-style-type: none;">
                        <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                            @if ($profile)
                            <div class="avatar-sm mx-2">
                                <img src="{{ asset('profile/' . $profile->profile) }}"
                                    alt="Image Preview"
                                    style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            </div>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn">
                            <div class="dropdown-user-scroll scrollbar-outer">
                                <li>
                                    <div class="user-box">
                                        <div class="avatar-lg">
                                            @if($profile)
                                            <img
                                                src="{{ asset('profile/' . $profile->profile) }}"
                                                alt="image profile" class="avatar-img rounded" />
                                            @endif
                                        </div>
                                        <div class="u-text">
                                            <h4>{{ explode(' ', $profile->fullname ?? '')[0] ?? '' }}</h4>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout text-primary"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </div>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>