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


            <div></div>
        </div>
    </nav>
</header>

<section id="products" class="py-5 bg-light">
    <div class="container">
        @if (!empty($items) && isset($items[0]->shop_name))
        <h2 class="text-center mb-4 text-primary">{{ $items[0]->shop_name }}</h2>
        @else
        <h2 class="text-center mb-4 text-primary">Shop Name</h2>
        @endif
        <div class="row g-4 justify-content-center">
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

            @forelse ($items as $item)

            @forelse ($item->sellers as $seller)

            <div class="col-md-4 col-lg-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="position-relative overflow-hidden">

                        <img
                            src="{{ asset('product/' . ($seller->images[0] ?? 'img/kaiadmin/logos.png')) }}"
                            class="card-img-top img-fluid  cart-btn"
                            alt="{{ $seller->name ?? 'Product' }}"
                            loading="lazy"
                            style="height: 200px; object-fit: cover;"

                            data-bs-toggle="modal"
                            data-bs-target="#editModal"
                            data-id="{{ $seller->id }}"
                            data-name="{{ $seller->name }}"
                            data-description="{{ $seller->description }}"
                            data-category="{{ $seller->category }}"
                            data-image="{{ json_encode($seller->images) }}"
                            data-price="{{ $seller->price }}"
                            data-ratings="{{ json_encode($seller->ratings) }}"
                            data-average-rating="{{ $seller->average_rating }}"
                            data-ratings-count="{{ $seller->ratings_count }}"
                            data-qr="{{$seller->user->gcash->gcash_qr_code ?? ''}}"
                            data-item-seller="{{$item->id}}">
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
                            <p id="itemDescription"></p>


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
                                <h5 class="fw-bold mb-3">Price</h5>
                                <div class="mb-3">
                                    <p id="itemPrice" class="text-dark fw-semibold"></p>
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

<script>
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

            $('.isread-btn').click(function(e) {
                e.preventDefault();

                let id = $(this).data('id');

                $.post('/read', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,


                }).done(function(res) {

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


            $('.add-btn').click(function(e) {
                e.preventDefault();
                let name = document.getElementById('addName').value

                $.post('/type_add', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: name,

                }).done(function(res) {

                }).fail(function(err) {

                })
            })

        });

    });

    document.addEventListener('DOMContentLoaded', () => {
        const editModal = document.getElementById('editModal');
        const ratingsList = document.getElementById('ratingsList');

        editModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            const itemRatings = JSON.parse(button.getAttribute('data-ratings') || '[]');

            ratingsList.innerHTML = '';

            if (itemRatings.length > 0) {
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
    $(document).ready(function() {
        $('.cart-btn').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let category = $(this).data('category');
            let description = $(this).data('description');
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
            $('#itemDescription').text(description);
            $('#itemPrice').text('₱' + price);
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

    function previewEditImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('editItemImage');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection