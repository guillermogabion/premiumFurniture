@extends('layouts.app')

@section('content')
<div class="{{ auth()->user()->role !== 'client' ? 'd-none' : '' }}">

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
                            <a class="nav-link text-white" href="#home">Home</a> <!-- Fixed anchor -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#products">Products</a> <!-- Fixed anchor -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#about">About Us</a> <!-- Fixed anchor -->
                        </li>
                    </ul>
                </div>
                <!-- Profile Avatar and Dropdown -->
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
                                        <h4>{{$profile->fullname[0] ?? null}}</h4>
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
            </div>
        </nav>
    </header>

    <!-- Home Section -->
    <section id="home"
        class="vh-100 d-flex align-items-center justify-content-center text-center text-white"
        style="background-image: url('{{ asset('img/download.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Our Website</h1>
            <p class="lead">Discover our amazing products and learn more about us.</p>
            <a href="#products" class="btn btn-primary btn-lg">Explore Products</a>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-4">Our Products</h2>
            <div class="row g-4">
                @forelse ($items as $item)
                <div class="col-md-4">
                    <div class="card position-relative overflow-hidden  rounded shadow-lg">
                        <div class="card " style=" width: 25rem;">
                            <img
                                src="{{ asset('product/' . ($item->image ? $item->image : 'img/kaiadmin/logos.png')) }}"
                                loading="lazy"
                                class="card-img-top"
                                alt="{{ $item->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{$item->name}}</h5>
                                <p class="card-text">{{$item->description}}</p>
                                <p class="card-text">{{$item->price}}</p>
                                <div class="mt-auto d-flex justify-content-end gap-2">
                                    <button
                                        type="button"
                                        class="btn btn-rounded  btn-primary btn-icon cart-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#cartModal"
                                        data-id="{{ $item->id }}"
                                        data-name="{{$item->name}}"
                                        data-category="{{$item->category}}"
                                        data-image="{{$item->image}}"
                                        data-price="{{ $item->price }}">
                                        <i class="fa fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-center">No products found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">About Us</h2>
            <p class="lead text-center">
                We are a company dedicated to providing high-quality products and excellent customer service.
                Our team works tirelessly to meet the needs of our valued customers.
            </p>
        </div>
    </section>
</div>

@endsection