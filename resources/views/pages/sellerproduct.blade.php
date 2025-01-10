@extends('layouts.app')

@section('content')

<header class="sticky-top custom-bg text-white shadow-sm @if(auth()->user()->role !== 'client') d-none @endif">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="#">
                <img
                    src="{{ asset('img/kaiadmin/logos.png') }}" alt="Image Preview" style="display: block; width: auto; height: 50px; border-radius: 50%; object-fit: cover; padding: 5px; cursor: pointer;"
                    class="navbar-brand"
                    height="10" />
                <span class="text-sm text-nowrap text-white fs-6 logo-text navbar-brand">PREMIER FURNITURE PH</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#home"
                            onclick="window.location='{{route('home')}}'">Back Home</a> <!-- Fixed anchor -->
                    </li>
                    <li class="nav-item topbar-user dropdown hidden-caret" style="list-style-type: none;">
                        <a
                            class="dropdown-toggle profile-pic"
                            data-bs-toggle="dropdown"
                            href="#"
                            aria-expanded="false">
                            @if ($profile)
                            <div class="avatar-sm mx-2">
                                <img src="{{ $profile ? asset('profile/' . $profile->profile) : 'https://via.placeholder.com/100/CCCCCC/FFFFFF' }}" alt="Image Preview" style="display: block; width: 40px; height: 40px; border-radius: 50%; object-fit: cover; padding: 5px; cursor: pointer;">
                            </div>

                            @else
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn">
                            <div class="dropdown-user-scroll scrollbar-outer">
                                <li>
                                    <div class="user-box">
                                        <div class="avatar-lg">
                                            @if($profile)
                                            <img
                                                src="{{ $profile ? asset('profile/' . $profile->profile) : 'https://via.placeholder.com/100/CCCCCC/FFFFFF' }} "
                                                alt="image profile"
                                                class="avatar-img rounded" />
                                            @else
                                            @endif
                                        </div>
                                        <div class="u-text">
                                            <h4>{{ explode(' ', $profile->fullname ?? '')[0] ?? '' }}</h4>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout text-primary"></i>
                                        {{ __('Logout') }}
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
            <!-- Profile Avatar and Dropdown -->


            <div></div>
        </div>
    </nav>
</header>

<section id="products" class="py-5 bg-light">
    <div class="container">
        <!-- Display the Shop Name -->
        @if (!empty($items) && isset($items[0]->shop_name))
        <h2 class="text-center mb-4 text-primary">{{ $items[0]->shop_name }}</h2>
        @else
        <h2 class="text-center mb-4 text-primary">Shop Name</h2>
        @endif
        <div class="row g-4 justify-content-center">
            <!-- Search Form -->
            <div class="row mb-3">
                <div class="col-lg-12 col-md-6">
                    <form method="GET" action="{{ route('home') }}#products">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search items..." value="{{ request()->query('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Display -->
            @forelse ($items as $item)
            @forelse ($item->sellers as $seller)
            <div class="col-md-4 col-lg-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="position-relative overflow-hidden">
                        <img
                            src="{{ asset('product/' . ($seller->image ?? 'img/kaiadmin/logos.png')) }}"
                            class="card-img-top img-fluid"
                            alt="{{ $seller->name ?? 'Product' }}"
                            loading="lazy"
                            style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-dark">{{ $seller->name ?? 'Product Name' }}</h5>
                        <p class="card-text text-muted small">{{ $seller->description ?? 'No description available.' }}</p>
                        <p class="card-text text-success fw-bold">Price: ₱ {{ number_format($seller->price, 2) }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    {!! $i <=floor($seller->average_rating ?? 0) ? '&#9733;' : '&#9734;' !!}
                                    @endfor
                            </div>
                            <span class="small text-muted">({{ $seller->ratings_count ?? 0 }} ratings)</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">No sellers found for this product.</p>
            </div>
            @endforelse
            @empty
            <div class="col-12">
                <p class="text-center text-muted">No products found.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection