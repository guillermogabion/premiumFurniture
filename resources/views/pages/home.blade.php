@extends('layouts.app')

@section('content')


<div class="{{ auth()->user()->isReset !== '1' ? 'd-none' : '' }}">
    <div id="resetModal" class="modal fade" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <!-- Modal Header -->
                <div class="modal-header custom-orange text-white border-0 rounded-top">
                    <h5 class="modal-title fw-bold" id="invoiceModalLabel">Reset</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 py-4">
                    <form id="updateForm" onsubmit="return validatePasswords(event)">
                        @csrf
                        <div class="form-group position-relative">
                            <input type="password" id="passwordInputReset" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                            <span class="position-absolute"
                                style="top: 53.5%; right: 3rem; transform: translateY(-50%); cursor: pointer; z-index: 10;"
                                onclick="togglePasswordVisibility('passwordInputReset', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group position-relative">
                            <input type="password" id="passwordInputConfirmReset" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            <span class="position-absolute"
                                style="top: 53.5%; right: 3rem; transform: translateY(-50%); cursor: pointer; z-index: 10;"
                                onclick="togglePasswordVisibility('passwordInputConfirmReset', this)">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div id="passwordError" class="text-danger mt-2 d-none">Passwords do not match.</div>
                        <button type="submit" class="btn btn-primary mt-4">Reset Password</button>
                    </form>
                </div>
                <!-- Modal Body -->
            </div>
        </div>
    </div>
</div>
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

                        <li class="nav-item">
                            <a class="nav-link text-white my-cart" style="cursor: pointer;">My Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white my-order" style="cursor: pointer;">My Orders</a>
                        </li>
                        <li class="nav-item  topbar-user dropdown hidden-caret mt-2" style="list-style-type: none;">
                            <a
                                class="dropdown-toggle text-white"
                                data-bs-toggle="dropdown"
                                href="#"
                                aria-expanded="false">
                                My Inbox

                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    @if(isset($inbox2) && count($inbox2) > 0)
                                    @foreach($inbox2 as $message)
                                    <div class="card mb-2 mx-2" onclick="window.location='{{ route('messages', ['inboxId' => $message->id]) }}'" style="cursor: pointer;">
                                        <div class="card-body">
                                            <h6 class="text-sm">{{ $message->user->fullname }}</h6>
                                            <small class="text-muted">{{ $message->created_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                    </div>
                                    <hr>
                                    @endforeach
                                    @else
                                    <li class="text-center">
                                        No messages in your inbox.
                                    </li>
                                    @endif
                                </div>
                            </ul>
                        </li>
                        <li class="nav-item  topbar-user dropdown hidden-caret" style="list-style-type: none;">
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

    <!-- Home Section -->
    <section id="home"
        class="vh-100 d-flex align-items-center justify-content-center text-center text-white"
        style="background-image: url('{{ asset('img/download.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">


            <h1 class="display-4 fw-bold">PREMIER FURNITURE PH</h1>
            <p class="lead">Discover our amazing products and learn more about us.</p>
            <a href="#products" class="btn btn-orange btn-lg">Explore Products</a>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4 text-primary">Our Products</h2>
            <div class="row g-4 justify-content-center">
                <div class="row mb-3">
                    <div class="col-lg-12 col-md-6">
                        <form method="GET" action="{{ route('home') }}#products">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search items..." value="{{ request()->query('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </span>
                            </div>
                            <div class="mt-3">
                                <label for="category" class="form-label">Category</label>
                                <div class="input-group">
                                    <select name="category" id="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($category as $cat)
                                        <option value="{{ $cat->name }}" {{ request()->query('category') == $cat->name ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">Filter</button>
                                    </span>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                @forelse ($items as $item)
                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="position-relative overflow-hidden">
                            <img
                                src="{{ asset('product/' . ($item->images[0] ?? 'img/kaiadmin/logos.png')) }}"
                                class="card-img-top img-fluid cart-btn"
                                alt="{{ $item->name }}"
                                loading="lazy"
                                style="height: 200px; object-fit: cover; cursor: pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-category="{{ $item->category }}"
                                data-image="{{ json_encode($item->images) }}"
                                data-price="{{ $item->price }}"
                                data-ratings="{{ json_encode($item->ratings) }}"
                                data-average-rating="{{ $item->average_rating }}"
                                data-ratings-count="{{ $item->ratings_count }}"
                                data-item-id="{{ $item->user->id}}"
                                data-item-profile="{{ $item->user->profile}}"
                                data-qr="{{$item->user->gcash->gcash_qr_code ?? ''}}"
                                data-item-seller="{{$item->user->id}}">
                        </div>

                        <img
                            src="{{ $profile ? asset('profile/' . $item->user->profile) : 'https://via.placeholder.com/100/CCCCCC/FFFFFF' }}"
                            alt="Image Preview"
                            style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; cursor: pointer;"
                            onclick="window.location='{{route('seller', ['id' => $item->user->id])}}'"
                            class="m-2">
                        <div class="card-body d-flex flex-column >
                            <h5 class=" card-title">{{ $item->name }}</h5>
                            <p class="text-muted small">{{ $item->description }}</p>
                            <p class="text-success fw-bold">₱{{ number_format($item->price, 2) }}</p>
                            <div class="d-flex align-items-center">
                                <div class="text-warning me-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <=floor($item->average_rating))
                                        ★
                                        @else
                                        ☆
                                        @endif
                                        @endfor
                                </div>
                                <span class="small text-muted">({{ $item->ratings_count }} ratings)</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-center text-muted">No products found.</p>
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

<div class="{{ auth()->user()->role === 'client' ? 'd-none' : 'page-inner' }}">
    <div
        class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
        </div>

    </div>
    <div class="row">
        @if ($profile->role == 'admin')
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Users</p>
                                <h4 class="card-title">{{$total_user}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Number of Vendors</p>
                                <h4 class="card-title">{{$total_vendor}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        @endif
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-luggage-cart"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Number of Products</p>
                                <h4 class="card-title">{{$profile->role == 'admin' ? $total_products : $my_total_products}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="far fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Orders</p>
                                <h4 class="card-title">{{$profile->role == 'admin' ? $total_order : $my_total_order}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Order Statistics</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="statChart"></canvas>
                    </div>
                    <div id="myChartLegend"></div>
                </div>
            </div>
        </div>

    </div>
    <div class="row ">
        <div class="col-md-12">
            <div class="card card-round">


            </div>
        </div>
    </div>
    <div class="row d-none">

    </div>
</div>


<div id="editModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-orange text-white border-0 rounded-top">
                <h5 class="modal-title fw-bold" id="editModalLabel">Add to Cart</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>



            <div class="d-flex align-items-center justify-content-between p-2">
                <div class="avatar-sm" alt="Image Preview">
                    <!-- <img
                        id="profile"
                        src="{{ asset('img/kaiadmin/logos.png') }}"
                        alt="Sample Image"
                        class="rounded-circle border shadow-sm"
                        style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                        onclick="setProfileAndRedirect(this)"> -->
                </div>
                <!-- Comment Icon -->
                <button id="commentIcon"
                    class="btn btn-orange rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px;"
                    title="Message Seller">
                    <i class="fas fa-comment-dots"></i>
                </button>

                <!-- Comment Field and Send Button -->
                <div id="commentField" class="position-absolute bg-white p-3 rounded shadow-sm" style="right: 60px; top: 0; display: none; z-index: 1050;">

                    <textarea
                        id="commentInput"
                        class="form-control mb-2"
                        rows="2"
                        placeholder="Write a message..."></textarea>
                    <span id="itemSeller" class="d-none"></span>


                    <button id="sendComment" class="btn btn-primary btn-sm btn-send">Send</button>
                </div>


            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 py-5">
                <form id="cartForm">
                    @csrf
                    <input type="hidden" id="itemId" name="id">
                    <!-- Image Upload Section -->
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center mb-4">
                                <label for="sampleEditImage" class="d-inline-block position-relative">
                                    <a href="#" id="editItemLink" data-lightbox="edit-image-group">
                                        <img
                                            id="editItemImage"
                                            src="{{ asset('img/kaiadmin/logos.png') }}"
                                            alt="Sample Image"
                                            class="rounded-3 border shadow-sm"
                                            style="width: 220px; height: 220px; object-fit: cover; cursor: pointer;">
                                    </a>
                                </label>
                            </div>
                            <div class="image-gallery" id="imageGallery">
                                <!-- Additional images will be appended here dynamically -->
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-secondary">Product Name</label>
                                        <p id="itemName" class="text-dark fw-semibold"></p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-secondary">Category</label>
                                        <p id="itemCategory" class="text-dark fw-semibold"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="ratings-container">
                                <h5 class="fw-bold mb-3">Average Rating</h5>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <!-- Average Rating -->
                                    <div class="text-warning">
                                        <span id="averageRating"></span>
                                    </div>
                                    <!-- Ratings Count -->
                                    <span id="ratingsCount" class="text-muted small">(<span>0</span> ratings)</span>
                                </div>
                                <h5 class="fw-bold mb-3">Reviews</h5>
                                <div id="ratingsList" class="overflow-auto" style="max-height: 500px;">
                                    <!-- Ratings will be dynamically loaded here -->
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="itemQuantity" class="form-label fw-bold text-secondary">Quantity</label>
                            <input
                                type="number"
                                class="form-control rounded-pill px-3 py-2"
                                id="itemQuantity"
                                name="quantity"
                                min="1"
                                placeholder="Enter quantity">
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3">
                            <button
                                type="button"
                                class="btn btn-outline-danger px-4 rounded-pill"
                                data-bs-dismiss="modal">Cancel</button>
                            <button
                                type="submit"
                                class="btn btn-primary px-4 rounded-pill cart-submit-btn">Add to Cart</button>
                        </div>
                    </div>



                    <!-- Product Details -->


                    <!-- Ratings Section -->



                    <!-- Quantity Input -->

                </form>
            </div>

        </div>
    </div>
</div>
<div id="cartModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-0 rounded-top">
                <h5 class="modal-title fw-bold" id="editModalLabel">My Cart</h5>
            </div>
            <div class="modal-body px-5 py-4">
                <div class="row g-4 justify-content-center">
                    @php
                    // Store the first item's shop name to compare with the others
                    $firstShopName = null;
                    @endphp

                    @forelse ($cart as $item)
                    @php
                    // Assign the first shop name
                    if ($loop->first) {
                    $firstShopName = $item->product->user->shop_name;
                    }
                    @endphp

                    <div class="col-md-4 col-lg-3">
                        <div class="card shadow-sm border-0 h-100 rounded-3">
                            <div class="position-relative overflow-hidden">
                                @php
                                $images = json_decode($item->product->images);
                                $firstImage = $images[0] ?? null; // Get the first image or null if the array is empty
                                @endphp
                                <img
                                    src="{{ asset('product/' . ($firstImage ?? 'img/kaiadmin/logos.png')) }}"
                                    class="card-img-top img-fluid"
                                    alt="{{ $item->product->name }}"
                                    loading="lazy"
                                    style="height: 200px; object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-dark text-truncate mb-2">{{ $item->product->name }}</h5>
                                <p class="card-text text-muted small mb-2">{{ $item->product->description }}</p>
                                <p class="card-text text-muted small mb-2" style="background-color:rgb(137, 190, 244); padding: 5px; border-radius: 4px;">
                                    Shop Name: {{ $item->product->user->shop_name }}
                                </p>
                                <p class="card-text text-success fw-bold mb-3">
                                    Price: ₱ {{ number_format($item->product->price, 2) }}
                                </p>
                                <p class="card-text text-secondary mb-3">
                                    Quantity: {{ $item->quantity }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="badge bg-secondary">{{ $item->category }}</span>
                                    <div class="form-check d-flex align-items-center gap-2">
                                        <input
                                            class="form-check-input cart-checkbox"
                                            type="checkbox"
                                            value="{{ $item->id }}"
                                            data-cart="{{$item->id}}"
                                            data-name="{{ $item->product->name }}"
                                            data-price="{{ $item->product->price }}"
                                            data-productId="{{ $item->product->id }}"
                                            data-quantity="{{ $item->quantity }}"
                                            data-owner="{{ $item->product->user->shop_name }}"
                                            data-ownerId="{{ $item->product->user->id }}"
                                            id="cartItem{{ $item->id }}"
                                            style="width: 24px; height: 24px; cursor: pointer;">
                                        <label
                                            class="form-check-label fw-bold text-dark"
                                            for="cartItem{{ $item->id }}"
                                            style="font-size: 1.2rem; cursor: pointer;">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                    <div class="col-12">
                        <p class="text-center text-muted">No products found in your cart.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Hidden Checkout Form -->
                <!-- Checkout Form Container (Initially hidden) -->
                <div id="checkoutFormContainer" style="display: none;" class="pb-2">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-primary text-white border-0 rounded-top">
                            <h5 class="modal-title fw-bold" id="checkoutModalLabel">Complete Checkout</h5>
                        </div>
                        <div class="modal-body px-5 py-4">
                            <form id="checkoutForm" enctype="multipart/form-data">

                                <img
                                    src="{{ asset('qrcode/' . ($item->product->user->gcash->gcash_qr_code ?? 'img/kaiadmin/logos.png')) }}"
                                    class="card-img-top img-fluid"
                                    loading="lazy"
                                    style="height: 400px; object-fit: contain;">
                                <div class="mb-3">
                                    <label for="qrCodeImage" class="form-label fw-bold">Upload QR Code Image</label>
                                    <input type="file" class="form-control" id="qrCodeImage" name="qrCodeImage" accept="image/*" required>
                                </div>
                                <div class="mb-3">
                                    <label for="referenceNumber" class="form-label fw-bold">Reference Number</label>
                                    <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" placeholder="Enter reference number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="paymentType" class="form-label fw-bold">Payment Type</label>
                                    <select id="paymentType" name="paymentType" class="form-select">
                                        <option value="" disabled selected>Select Payment Type</option>
                                        <option value="downpayment">Downpayment</option>
                                        <option value="fullpayment">Fullpayment</option>
                                    </select>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Proceed to Checkout button (Initially hidden) -->
                <div class="d-flex gap-2 justify-content-start align-items-center">
                    <button class="btn btn-warning btn-lg" id="editOrder" style="display: none;">Edit Order</button>
                    <button class="btn btn-success btn-lg" id="proceedCheckoutButton" style="display: none;" data-bs-toggle="modal" data-bs-target="#invoiceModal">Proceed to Checkout</button>
                </div>


                <!-- Edit Order button -->


                <div class="d-flex justify-content-between align-items-center mt-5">
                    <div class="mb-3" id="downpaymentAmount" style="display: none;">
                        <label class="form-label fw-bold">Downpayment Amount</label>
                        <p class="text-success fw-bold">₱ <span id="downpaymentValue">0.00</span></p>
                    </div>

                    <h5 class="text-dark fw-bold">
                        Total: ₱ <span id="totalPrice">0.00</span>
                    </h5>
                    <button class="btn btn-success btn-lg" id="checkoutButton">Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="invoiceModal" class="modal fade" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-orange text-white border-0 rounded-top">
                <h5 class="modal-title fw-bold" id="invoiceModalLabel">Invoice</h5>
                <button type="button" class="btn-close button-x btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-5 py-4">
                <!-- Invoice Header -->
                <div class="mb-4">
                    <h6 class="text-uppercase fw-bold text-secondary">Invoice Details</h6>
                    <p class="text-muted small mb-0">Date: <span id="invoiceDate"></span></p>
                    <p class="text-muted small">Order ID: <span id="orderId"></span></p>
                </div>

                <!-- Invoice Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Shop</th>
                                <th>Price (₱)</th>
                                <th>Quantity</th>
                                <th>Total (₱)</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceDetails">
                            <!-- Invoice items will be dynamically added here -->
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total -->

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <!-- Add data-bs-dismiss="modal" -->
                    <button class="btn btn-success btn-lg button-close-cart">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="orderModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-0 rounded-top">
                <h5 class="modal-title fw-bold" id="editModalLabel">My Orders</h5>
            </div>
            <div class="modal-body px-5 py-4">
                <div class="g-4 justify-content-center">
                    @foreach ($orders as $item)
                    <div class="col-12">
                        <div class="card shadow-sm border-0 h-100 rounded-3">
                            <div class="row">
                                <div class="col-9">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-dark text-truncate mb-1">Order ID: {{ $item->orderId }}</h5>

                                        <p class="card-text text-muted small mb-1">Date: {{ $item->date }}</p>
                                        <span class="badge status-badge 
                                            @if ($item->status == 'to_pay') 
                                                bg-warning
                                            @elseif ($item->status == 'received') 
                                                bg-success
                                            @elseif ($item->status == 'paid') 
                                                bg-primary
                                            @elseif ($item->status == 'canceled') 
                                                bg-danger
                                            @else 
                                                bg-secondary 
                                            @endif">
                                            {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                        </span>

                                        <p class="card-text text-muted small mb-1">Payment Mode: {{ ucwords($item->payment_mode) }}</p>

                                        <p class="card-text text-success fw-bold mb-1">Total: ₱ {{ number_format($item->total, 2) }}</p>
                                        <p class="card-text text-secondary mb-1">Downpayment: ₱ {{ number_format($item->downpayment_amount, 2) }}</p>
                                        <h6 class="mt-3">Products:</h6>
                                        @foreach ($item->products as $product)
                                        @php
                                        // Decode the product_ids to get the quantity for each product
                                        $productIds = json_decode($item->product_ids, true);

                                        // Find the quantity for the current product
                                        $productQuantity = 0;
                                        foreach ($productIds as $productId) {
                                        if ($productId['product_id'] == $product->id) {
                                        $productQuantity = $productId['quantity'];
                                        break;
                                        }
                                        }

                                        // Get the first image of the product
                                        $images = json_decode($product->images);
                                        $firstOrderImage = $images[0] ?? null;
                                        @endphp

                                        <div class="d-flex justify-content-between mb-2">
                                            <img src="{{ asset('product/' . $firstOrderImage) }}" alt="{{ $firstOrderImage }}" style="height: 50px; object-fit: cover;">
                                            <p class="text-dark">{{ $product->name }} (₱ {{ number_format($product->price, 2) }})</p>
                                            <span class="text-secondary">x{{ $productQuantity }}</span>
                                        </div>
                                        @endforeach

                                        <h6 class="mt-3">User Details:</h6>
                                        <p class="card-text text-muted small mb-1">Name: {{ $item->user->fullname }}</p>
                                        <p class="card-text text-muted small mb-1">Email: {{ $item->user->email }}</p>
                                        <p class="card-text text-muted small mb-1">Contact: {{ $item->user->contact }}</p>
                                        <p class="card-text text-muted small mb-1">Address: {{ $item->user->address }}</p>

                                        @if ($item->status == 'received')
                                        <div class="d-flex justify-content-between align-items-center mt-4">
                                            <button class="btn btn-success btn-sm button-rate"
                                                data-productitemid="{{ $product->id }}">
                                                Rate
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Rate Modal -->
<div id="rateModal" class="modal fade" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-[#D2700F] text-white border-0 rounded-top">
                <h5 class="modal-title fw-bold" id="invoiceModalLabel">Rate the Product</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-5 py-4">
                <span id="itemProdid" class="d-none"></span>

                <!-- Star Rating -->
                <div class="stars-rating mt-3 text-center">
                    <span class="star" data-star="1">&#9733;</span>
                    <span class="star" data-star="2">&#9733;</span>
                    <span class="star" data-star="3">&#9733;</span>
                    <span class="star" data-star="4">&#9733;</span>
                    <span class="star" data-star="5">&#9733;</span>
                </div>

                <!-- Textarea for Comments -->
                <div class="mt-4">
                    <label for="ratingComments" class="form-label">Your Comments (optional)</label>
                    <textarea id="ratingComments" class="form-control" rows="4" placeholder="Share your thoughts..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-3">
                    <button class="btn btn-primary btn-rate" id="submitRatingBtn">Submit Rating</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="inboxModal" class="modal fade" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-[#D2700F] text-white border-0 rounded-top">
                <h5 class="modal-title fw-bold" id="invoiceModalLabel">Invoice</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-5 py-4">
                <!-- Invoice Header -->
                <div class="mb-4">
                    <h6 class="text-uppercase fw-bold text-secondary">Invoice Details</h6>
                    <p class="text-muted small mb-0">Date: <span id="invoiceDate"></span></p>
                    <p class="text-muted small">Order ID: <span id="orderId"></span></p>
                </div>

                <!-- Invoice Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Shop</th>
                                <th>Price (₱)</th>
                                <th>Quantity</th>
                                <th>Total (₱)</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceDetails">
                            <!-- Invoice items will be dynamically added here -->
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total -->

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button class="btn btn-success btn-lg" id="checkoutButton" data-bs-toggle="modal" data-bs-target="#invoiceModal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>








<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    var ctx = document.getElementById('statChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line', // Line chart
        data: {
            labels: ['To Pay', 'Preparing', 'To Ship', 'Shipping', 'Received', 'Cancelled'],
            datasets: [{
                label: 'Order Status Count',
                data: [
                    @json($toPayCount),
                    @json($preparingCount),
                    @json($toShipCount),
                    @json($shippingCount),
                    @json($receivedCount),
                    @json($cancelledCount)
                ],
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    function togglePasswordVisibility(inputId, toggleIcon) {
        const input = document.getElementById(inputId);
        const icon = toggleIcon.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function validatePasswords(event) {
        const password = document.getElementById('passwordInputReset').value;
        const confirmPassword = document.getElementById('passwordInputConfirmReset').value;
        const errorDiv = document.getElementById('passwordError');
        if (password !== confirmPassword) {
            event.preventDefault(); // Prevent form submission
            errorDiv.classList.remove('d-none'); // Show error message
            return false;
        }
        errorDiv.classList.add('d-none'); // Hide error message if passwords match
        const form = document.getElementById('updateForm');
        const formData = new FormData(form);

        // Use fetch to send a POST request
        fetch('/changePassword', { // Replace with your endpoint
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                redirect: 'manual'
            })
            .then(response => {
                // If response status is 302, we treat it as success

                // Otherwise, we process JSON response
                return response.json();
            })
            .then(data => {
                // Success response
                Swal.fire({
                    icon: 'success',
                    title: 'Password Reset Successful',
                    text: 'Your password has been successfully reset!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3AA0F3'
                }).then(() => window.location.reload());

                // Handle the response data here, like redirecting if needed
                console.log('Success:', data);
            })
            .catch((error) => {
                // Error response
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#D22701'
                });

                console.error('Error:', error);
            });

        event.preventDefault();
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Only show the modal if it is not hidden
        const resetModal = document.getElementById('resetModal');
        if (resetModal && !resetModal.closest('.d-none')) {
            // Show the modal using Bootstrap
            const modal = new bootstrap.Modal(resetModal);
            modal.show();
        }
    });
    document.getElementById('paymentType').addEventListener('change', function() {
        const paymentType = this.value;
        const grandTotal = calculateGrandTotal(); // Implement a function to calculate the total
        const downpaymentAmount = grandTotal / 2;

        if (paymentType === 'downpayment') {
            document.getElementById('downpaymentAmount').style.display = 'block';
            document.getElementById('downpaymentValue').innerText = downpaymentAmount.toFixed(2);
        } else {
            document.getElementById('downpaymentAmount').style.display = 'none';
        }
    });

    function calculateGrandTotal() {
        let grandTotal = 0;
        const checkedItems = document.querySelectorAll('.cart-checkbox:checked');
        checkedItems.forEach(checkbox => {
            const price = parseFloat(checkbox.getAttribute('data-price'));
            const quantity = parseInt(checkbox.getAttribute('data-quantity'));
            grandTotal += price * quantity;
        });
        return grandTotal;
    }
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.cart-checkbox');
        const totalPriceElement = document.getElementById('totalPrice');

        const updateTotal = () => {
            let total = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const price = parseFloat(checkbox.getAttribute('data-price'));
                    const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                    total += price * quantity;
                }
            });
            totalPriceElement.textContent = total.toFixed(2);
        };

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });
    });
    $('.my-cart').click(function() {
        $('#cartModal').modal('show');
    })
    $('.my-inbox').click(function() {
        $('#inboxModal').modal('show');
    })
    $('.button-close-cart').click(function() {
        $('#invoiceModal').modal('hide');
    })
    $('.my-order').click(function() {
        $('#orderModal').modal('show');
    })




    $(document).ready(function() {
        $('.button-rate').click(function() {
            let product_id = $(this).data('productitemid');

            // return console.log(product_id)
            $('#rateModal').modal('show');
            $('#itemProdid').text(product_id); // Display the product ID in the modal
        });

        $('.cart-btn').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let category = $(this).data('category');
            let price = $(this).data('price');
            let images = $(this).data('image'); // Get the images array
            let ratings = $(this).data('ratings'); // Get ratings JSON data
            let averageRating = $(this).data('average-rating');
            let ratingsCount = $(this).data('ratings-count');
            let profileId = $(this).data('item-id');
            let profile = $(this).data('item-profile');
            let qrcode = $(this).data('qr');
            let itemSeller = $(this).data('item-seller');

            // Clear previous ratings and images
            $('#ratingsList').empty();
            $('#imageGallery').empty(); // Assuming you have an element for the images

            // Display product details
            $('#itemId').val(id);
            $('#profileId').val(id);
            $('#itemName').text(name);
            $('#itemCategory').text(category);
            $('#itemPrice').text(price);
            $('#itemSeller').text(itemSeller);
            $('#editModal').modal('show');

            // Display main image or fallback image
            var imgUrl = images && images.length > 0 ?
                `{{ asset('product') }}/${images[0]}` :
                `{{ asset('img/kaiadmin/logos.png') }}`;

            $('#editItemImage').attr('src', imgUrl);

            // Update the href of the lightbox link to match the image source
            $('#editItemLink').attr('href', imgUrl);

            // Display additional images in a gallery
            if (images && images.length > 1) {
                images.slice(1).forEach(function(image) { // Slice the array to skip the first image
                    var lightboxGroup = 'images'; // Group name for navigation

                    let imageHtml = `
                    <div class="image-gallery-item">
                        <a href="{{ asset('product') }}/${image}" data-lightbox="${lightboxGroup}">
                            <img src="{{ asset('product') }}/${image}" class="custom-img" alt="Product Image">
                        </a>
                    </div>
            `;
                    $('#imageGallery').append(imageHtml); // Append each image to the gallery
                });
            } else {
                $('#imageGallery').append('<p class="text-muted">No additional images available.</p>');
            }

            // Display profile image
            $('#profile').attr('src', profile ?
                `{{ asset('profile') }}/${profile}` :
                `{{ asset('img/kaiadmin/logos.png') }}`);

            // Display QR code
            $('#qrcode').attr('src', qrcode ?
                `{{ asset('qrcode') }}/${qrcode}` :
                `{{ asset('img/kaiadmin/logos.png') }}`);

            // Handle average rating
            if (averageRating !== undefined && averageRating !== null && !isNaN(averageRating) && averageRating > 0) {
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= Math.floor(averageRating)) {
                        starsHtml += '<span class="text-warning">★</span>'; // Filled star
                    } else if (i <= Math.ceil(averageRating) && averageRating % 1 !== 0) {
                        starsHtml += '<span class="text-warning">☆</span>'; // Half star
                    } else {
                        starsHtml += '☆'; // Empty star
                    }
                }
                $('#averageRating').html(starsHtml);
            } else {
                $('#averageRating').html('No rating yet');
            }

            // Display ratings count
            $('#ratingsCount span').text(ratingsCount);

            // Display individual ratings
            if (ratings && ratings.length > 0) {
                ratings.forEach(function(rating) {
                    let ratingHtml = `
                <div class="rating-item mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold">${rating.user.fullname}</div>
                        <div class="text-warning">
                            ${'★'.repeat(Math.floor(rating.rating))}${'☆'.repeat(5 - Math.floor(rating.rating))}
                        </div>
                    </div>
                    <p class="text-muted">${rating.comment}</p>
                </div>
            `;
                    $('#ratingsList').append(ratingHtml);
                });
            } else {
                $('#ratingsList').append('<p class="text-muted">No ratings yet.</p>');
            }
        });







        $('.cart-submit-btn').click(function(e) {
            e.preventDefault();
            let product_id = document.getElementById('itemId').value
            let quantity = document.getElementById('itemQuantity').value
            $.post('/cart_add', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                product_id: product_id,
                quantity: quantity,

            }).done(function(res) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Saving Success',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            }).fail(function(err) {
                if (err.status === 422) {
                    let errors = err.responseJSON.errors;
                    for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            console.error(key + ": " + errors[key]);
                        }
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: errors.name[0],
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message || "An error occurred",
                        confirmButtonText: 'OK'
                    });
                    console.error(err);
                }
            })
        })



    })

    $('.cart-cancel-btn').click(function() {
        $('#editModal').modal('hide');
    })

    function previewEditImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('editItemImage');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewEditProfile(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('editItemProfile');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    document.getElementById('proceedCheckoutButton').addEventListener('click', function() {
        const checkedItems = document.querySelectorAll('.cart-checkbox:checked');
        const invoiceDetails = document.getElementById('invoiceDetails');
        const referenceNumber = document.getElementById('referenceNumber').value;
        const paymentType = document.getElementById('paymentType').value;
        const qrImage = document.getElementById('qrCodeImage').files[0];

        let grandTotal = 0;
        const orders = [];

        // Generate order ID and get current date
        const orderId = 'ORD' + Math.floor(Math.random() * 1000000);
        const currentDate = new Date().toLocaleDateString();

        // Update invoice header details
        document.getElementById('orderId').innerText = orderId;
        document.getElementById('invoiceDate').innerText = currentDate;

        // Clear previous invoice details
        invoiceDetails.innerHTML = '';

        // Add order details to invoice and prepare data for submission
        checkedItems.forEach((checkbox) => {
            const name = checkbox.getAttribute('data-name');
            const cartId = checkbox.getAttribute('data-cart');
            const productId = checkbox.getAttribute('data-productId');
            const owner = checkbox.getAttribute('data-owner');
            const ownerId = checkbox.getAttribute('data-ownerId');
            const price = parseFloat(checkbox.getAttribute('data-price'));
            const quantity = parseInt(checkbox.getAttribute('data-quantity'));
            const total = price * quantity;

            grandTotal += total;

            // Add item row to the invoice table
            invoiceDetails.innerHTML += `
        <tr>
            <td>${name}</td>
            <td>${owner}</td>
            <td>${price.toFixed(2)}</td>
            <td>${quantity}</td>
            <td>${total.toFixed(2)}</td>
        </tr>
        `;

            // Prepare the order object for submission
            orders.push({
                product_id: productId,
                quantity: quantity,
                total: total,
                cart_id: cartId,
            });
        });

        // Add grand total to invoice
        invoiceDetails.innerHTML += `
    <tr>
        <td colspan="4" style="text-align: right;"><strong>Grand Total:</strong></td>
        <td><strong> ₱${grandTotal.toFixed(2)}</strong></td>
    </tr>
    `;

        // Create FormData object
        const formData = new FormData();
        formData.append('order_id', orderId); // Append orderId to FormData
        formData.append('orders', JSON.stringify(orders));
        formData.append('ref_no', referenceNumber);
        formData.append('payment_mode', paymentType);
        if (qrImage) formData.append('image', qrImage);

        // Log FormData contents for debugging
        console.log('FormData contents:');
        for (const [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }

        // Submit the form data via fetch
        fetch('/add_order', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order placed successfully!');
                } else {
                    alert('Something went wrong!');
                }
            })
            .catch(error => console.error('Error:', error));
    });



    document.addEventListener('DOMContentLoaded', function() {
        const commentIcon = document.getElementById('commentIcon');
        const commentField = document.getElementById('commentField');
        const commentInput = document.getElementById('commentInput');

        // Show the comment field when the icon is clicked
        commentIcon.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent triggering document click
            commentField.style.display = 'block';
        });

        // Hide the comment field when clicking outside
        document.addEventListener('click', function() {
            commentField.style.display = 'none';
            commentInput.value = ''; // Clear the input
        });

        // Prevent hiding when clicking inside the comment field
        commentField.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        // Handle the send button click (optional functionality)


        $(document).ready(function() {
            $('.btn-send').click(function(e) {
                e.preventDefault();

                // Get seller ID and message
                let seller_id = document.getElementById('itemSeller').textContent.trim(); // Use textContent and trim to clean spaces
                let message = document.getElementById('commentInput').value.trim(); // Trim the message to remove extra spaces

                if (!message) {
                    Swal.fire({
                        icon: "warning",
                        title: "Empty Message",
                        text: "Please enter a message before sending.",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                if (!seller_id) {
                    Swal.fire({
                        icon: "error",
                        title: "Missing Seller ID",
                        text: "Seller information is missing. Please try again.",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                // Log to verify values
                console.log("Seller ID:", seller_id);
                console.log("Message:", message);

                // Post message to server
                $.post('/send_message', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    seller_id: seller_id,
                    message: message,
                }).done(function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Message sent successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }).fail(function(err) {
                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;
                        let errorMsg = "Validation error occurred.";
                        if (errors && errors.message) {
                            errorMsg = errors.message[0];
                        }
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            text: errorMsg,
                            confirmButtonText: "OK"
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: err.responseJSON.message || "An error occurred. Please try again.",
                            confirmButtonText: "OK"
                        });
                        console.error(err);
                    }
                });
            });

            $('.add-btn').click(function(e) {
                e.preventDefault();
                let name = document.getElementById('addName').value

                $.post('/type_add', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: name,

                }).done(function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Saving Success',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }).fail(function(err) {
                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;
                        for (let key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                console.error(key + ": " + errors[key]);
                            }
                        }
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            text: errors.name[0],
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: err.responseJSON.message || "An error occurred",
                            confirmButtonText: 'OK'
                        });
                        console.error(err);
                    }
                })
            })

        });

    });

    let selectedRating = 0;

    // Star click event listener
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = this.getAttribute('data-star');
            document.querySelectorAll('.star').forEach(star => {
                if (star.getAttribute('data-star') <= selectedRating) {
                    star.style.color = 'gold';
                } else {
                    star.style.color = '';
                }
            });
        });
    });

    // Submit rating and comment
    document.getElementById('submitRatingBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const comment = document.getElementById('ratingComments').value.trim();
        const productId = document.getElementById('itemProdid').innerText;

        if (selectedRating > 0) {
            $.post('/add_rate', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    rating: selectedRating,
                    comment: comment,
                    product_id: productId
                })
                .done(function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your rating has been submitted.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                })
                .fail(function(err) {
                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            text: Object.values(errors).join(", "),
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: err.responseJSON.message || "An error occurred",
                            confirmButtonText: 'OK'
                        });
                        console.error(err);
                    }
                });
        } else {
            Swal.fire({
                icon: "warning",
                title: "Missing Rating",
                text: "Please select a rating before submitting.",
                confirmButtonText: "OK"
            });
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const editModal = document.getElementById('editModal');
        const ratingsList = document.getElementById('ratingsList');

        editModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Button that triggered the modal
            const itemRatings = JSON.parse(button.getAttribute('data-ratings') || '[]');

            // Clear existing ratings
            ratingsList.innerHTML = '';

            if (itemRatings.length > 0) {
                // Build ratings dynamically
                itemRatings.forEach((rating) => {
                    const ratingElement = document.createElement('div');
                    ratingElement.className = 'rating-item mb-3';
                    ratingElement.innerHTML = `
                    <div class="d-flex align-items-center mb-2">
                        <div class="text-warning me-2">
                            ${'&#9733;'.repeat(rating.stars)}${'&#9734;'.repeat(5 - rating.stars)}
                        </div>
                        <p class="mb-0 text-dark">${rating.comment}</p>
                    </div>
                    <small class="text-muted">- ${rating.user_id}</small>
                `;
                    ratingsList.appendChild(ratingElement);
                });
            } else {
                ratingsList.innerHTML = '<p class="text-muted">No ratings available for this item.</p>';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const cartCheckboxes = document.querySelectorAll('.cart-checkbox');

        cartCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
                const selectedOwner = this.dataset.owner; // Get the shop name of the checked item
                const isChecked = this.checked;

                // Enable/disable other checkboxes based on the selected shop
                cartCheckboxes.forEach((otherCheckbox) => {
                    if (otherCheckbox !== this) {
                        // Only enable if it's from the same shop, or disable otherwise
                        otherCheckbox.disabled = isChecked && otherCheckbox.dataset.owner !== selectedOwner;
                    }
                });
            });
        });
    });

    document.getElementById('checkoutButton').addEventListener('click', function() {
        // Show the checkout form above the "Proceed to Checkout" button
        document.getElementById('checkoutFormContainer').style.display = 'block';
        document.getElementById('proceedCheckoutButton').style.display = 'block'; // Show "Proceed to Checkout" button
        document.getElementById('editOrder').style.display = 'block'; // Show "Proceed to Checkout" button
        document.getElementById('checkoutButton').style.display = 'none'; // Hide the original "Checkout" button

        // Disable all checkboxes in the cart
        const checkboxes = document.querySelectorAll('.cart-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.disabled = true;
        });
    });

    document.getElementById("editOrderButton").addEventListener("click", function() {
        // Select all checkboxes with the class 'cart-checkbox' and uncheck them
        document.querySelectorAll(".cart-checkbox").forEach(function(checkbox) {
            checkbox.checked = false;
        });

        // Optionally reset the total price and hide checkout-related elements
        document.getElementById("totalPrice").textContent = "0.00";
        document.getElementById("proceedCheckoutButton").style.display = "none";
        document.getElementById("checkoutFormContainer").style.display = "none";
    });
</script>

@endsection