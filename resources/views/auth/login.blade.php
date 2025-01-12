@extends('layouts.app')

@section('content')

<!-- Sticky Header -->
<header class="sticky-top custom-bg text-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="#">
                <img
                    src="{{ asset('img/kaiadmin/logos.png') }}"
                    alt="Image Preview"
                    style="display: block; width: auto; height: 50px; border-radius: 50%; object-fit: cover; padding: 5px; cursor: pointer;"
                    class="navbar-brand" />
                <span class="text-sm text-nowrap text-white fs-6 logo-text navbar-brand">PREMIER FURNITURE PH</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" data-bs-toggle="modal" data-bs-target="#loginModal" href="#">Sign In</a>
                    </li>
                </ul>
            </div>
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
                    <form method="GET" action="{{ route('login') }}#products">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search items..." value="{{ request()->query('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
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
                            class="card-img-top img-fluid"
                            alt="{{ $item->name }}"
                            loading="lazy"
                            style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-dark text-truncate">{{ $item->name }}</h5>
                        <p class="card-text text-muted small mb-2">{{ $item->description }}</p>
                        <p class="card-text text-success fw-bold mb-3">Price: ₱{{ number_format($item->price, 2) }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    {!! $i <=floor($item->average_rating) ? '&#9733;' : '&#9734;' !!}
                                    @endfor
                            </div>
                            <span class="small text-muted">({{ $item->ratings_count }} ratings)</span>
                        </div>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary">{{ $item->category }}</span>
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

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="text"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                            placeholder="Email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group mb-3 position-relative">
                        <input type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required
                            autocomplete="current-password"
                            id="passwordInputLog"
                            placeholder="Password">
                        <span class="position-absolute"
                            style="top: 50%; right: 1rem; transform: translateY(-50%); cursor: pointer; z-index: 10;"
                            onclick="togglePasswordVisibility('passwordInputLog', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Sign In</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <small>
                        Don't have an account? <a href="{{ route('register') }}">Create</a><br>
                        Forgot password? <a href="{{ route('reset') }}">Reset my Password</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="resetModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h5 class="modal-title">Reset Password</h5>
            </div>
            <div class="modal-body">
                <form class="pt-3" method="POST" id="resetPasswordForm">
                    @csrf
                    <div class="form-group">
                        <label for="editName">Email:</label>
                        <input type="email" id="resetEmail" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editName">Contact Number:</label>
                        <input type="number" id="resetContact" name="contact" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 reset-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


@if (session('login_error'))
<script>
    window.alert(@json(session('login_error')));
</script>
@endif


<script>
    function togglePasswordVisibility(inputId, icon) {

        const input = document.getElementById(inputId);
        const eyeIcon = icon.querySelector("i");
        if (input.type === "password") {
            input.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
    $(document).ready(function() {
        $('.modal-show').click(function() {
            $('#resetModal').modal('show')
        })
        $('.reset-btn').click(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

            let email = $('#resetEmail').val();
            let contact = $('#resetContact').val();

            $.ajax({
                url: `{{ route('reset_my_password') }}`, // Correct use of Laravel's route helper
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    email: email,
                    contact: contact,
                },
                success: function(res) {
                    if (res.success) {
                        window.alert(res.message);
                    }
                },
                error: function(err) {
                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;
                        let errorMessage = Object.values(errors).flat().join('\n');
                        window.alert('Validation Error: ' + errorMessage); // Show validation error message
                    } else {
                        window.alert('Error: ' + (err.responseJSON.message || 'An error occurred')); // Show generic error message
                    }
                },
            });
        });




    })
</script>