@extends('layouts.app')

@section('content')

<div class="page-inner">
    <div class="content-wrapper">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <label for="" class="card-title">My Products</label>
                                </div>
                                <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary add-item"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addModal"
                                        {{ $profile && $profile->status !== 'active' ? 'disabled' : '' }}>
                                        Add Product
                                    </button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <form method="GET" action="{{ route('products') }}">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search product..." value="{{ request()->query('search') }}">
                                            <span class="input-group-append">
                                                <button class="btn btn-outline-secondary d-none" type="submit">Search</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="basic-datatables" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                @foreach($headers as $header)
                                                <th>{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($items as $item)
                                            <tr>
                                                <td>{{ $item->id}}</td>

                                                <td>
                                                    @if ($item->images)
                                                    @php
                                                    // Decode JSON if needed, or clean up brackets and quotes
                                                    $cleanedImages = str_replace(['[', ']', '"'], '', $item->images);
                                                    $images = explode(',', $cleanedImages); // Split into an array
                                                    @endphp
                                                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                                        @foreach ($images as $image)
                                                        <img src="{{ asset('product/' . trim($image)) }}" alt="Product Image"
                                                            style="width: 50px; height: 50px; border-radius: 5px; object-fit: cover;">
                                                        @endforeach
                                                    </div>
                                                    @else
                                                    <img src="https://via.placeholder.com/50" alt="Default Placeholder"
                                                        style="width: 50px; height: 50px; border-radius: 5px; object-fit: cover;">
                                                    @endif
                                                </td>

                                                <td>{{ $item->name}}</td>
                                                <td>{{ $item->price }}</td>
                                                <td> <button class="badge 
                                                                    @if ($item->status == 'active') badge-success
                                                                    @else badge-danger
                                                                    @endif
                                                                    " type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ $item->status == 'active' ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-rounded btn-icon edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $item->id }}" data-name="{{$item->name}}" data-category="{{ $item->category }}" data-price="{{ $item->price }}" data-description="{{ $item->description }}" data-images="{{ json_encode($item->images) }}">
                                                        <i class="fa fa-edit text-primary"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="alert alert-info">No Items</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $items->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h5 class="modal-title">Add Product</h5>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf
                    <div class="form-group">
                        <div class="upload-area" onclick="document.getElementById('sampleImages').click()">
                            <p class="upload-text"><span class="upload-link">Browse</span> to Upload Images</p>
                            <input type="file" id="sampleImages" name="sampleImages[]" accept="image/*" multiple style="display: none;" onchange="previewImages(event)">
                        </div>
                        @error('sampleImage')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div id="imagePreviewContainer" class="preview-container"></div>

                    <div class="form-group">
                        <label for="addName">Item Name:</label>
                        <input type="text" id="addName" name="userName" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="addRole">Select a Category:</label>
                        <select class="form-control" id="addCategory" name="category" required>
                            <option value="" disabled selected>Select a category</option>
                            @foreach ($category as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addPrice">Price:</label>
                        <input type="number" id="addPrice" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addDesc">Description:</label>
                        <input type="text" id="addDesc" name="description" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 add-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h5 class="modal-title">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <div class="form-group text-center">
                        <div class="row">
                            <div class="col-12">
                                <label for="sampleEditImage">
                                    <div id="editItemImagesContainer" class="preview-container">
                                        <!-- Images will be appended here dynamically -->
                                    </div>
                                </label>
                            </div>
                        </div>

                        <input type="file" id="sampleEditImage" name="sampleImage" multiple accept="image/*" style="display: none;" onchange="previewEditImages(event)">
                        @error('sampleEditImage')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>






                    <input type="hidden" id="itemId" name="id">
                    <div class="form-group">
                        <label for="addName">Item Name:</label>
                        <input type="text" id="itemName" name="userName" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="addRole">Select a Category:</label>
                        <select class="form-control" id="itemCategory" name="category" required>
                            <option value="" disabled selected>Select a category</option>
                            @foreach ($category as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addPrice">Price:</label>
                        <input type="number" id="itemPrice" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addDesc">Description:</label>
                        <input type="text" id="itemDescription" name="description" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 edit-submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script>
    function previewImages(event) {
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        imagePreviewContainer.innerHTML = ''; // Clear previous previews

        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                imagePreviewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    function previewEditImages(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('editItemImagesPreview');
        previewContainer.innerHTML = ''; // Clear previous previews

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.createElement('img');
                img.src = reader.result;
                img.style.cssText = 'width: 50px; height: 50px; border-radius: 5px; object-fit: cover; margin-right: 5px;';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    $(document).ready(function() {
        $('.add-item').click(function() {
            $('#addModal').modal('show');
        });

        $('.edit-btn').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let category = $(this).data('category');
            let price = $(this).data('price');
            let description = $(this).data('description');
            let imagesRaw = $(this).data('images'); // Get the raw string from the data attribute
            console.log('Raw images data:', imagesRaw);

            // Parse the raw string twice to get the actual array
            let images = JSON.parse(JSON.parse(imagesRaw));
            console.log('Parsed images:', images);

            // Check if it's now an array
            console.log('Is Array:', Array.isArray(images)); // This should log true

            // Assign values to modal inputs
            $('#itemId').val(id);
            $('#itemName').val(name);
            $('#itemCategory').val(category);
            $('#itemPrice').val(price);
            $('#itemDescription').val(description);

            // Clear previous images
            $('#editItemImagesContainer').empty();

            // If there are images, display them
            if (Array.isArray(images)) {
                console.log('true');
                images.forEach(function(image) {
                    // Construct the image element for each image
                    let imgElement = `<img src="{{ asset('product/') }}/${image}" alt="Product Image" style="width: 220px; height: 220px; border-radius: 20px; margin: 5px;">`;
                    $('#editItemImagesContainer').append(imgElement);
                });
            } else {
                // If no images are available, use a default image
                console.log('false');
                let defaultImage = `<img src="{{ asset('img/kaiadmin/logos.png') }}" alt="Default Image" style="width: 220px; height: 220px; border-radius: 20px;">`;
                $('#editItemImagesContainer').append(defaultImage);
            }

            // Show the modal
            $('#editModal').modal('show');
        });






        $('.add-btn').click(function(e) {
            e.preventDefault();

            let name = document.getElementById('addName').value;
            let category = document.getElementById('addCategory').value;
            let price = document.getElementById('addPrice').value;
            let description = document.getElementById('addDesc').value;
            let sampleImages = document.getElementById('sampleImages').files;

            let formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('name', name);
            formData.append('category', category);
            formData.append('price', price);
            formData.append('description', description);

            Array.from(sampleImages).forEach((image, index) => {
                formData.append(`images[${index}]`, image);
            });

            $.ajax({
                url: '/products_add',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Product added successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => window.location.reload());
                },
                error: function(err) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "An error occurred",
                        confirmButtonText: 'OK'
                    });
                }
            });
        });



        $('.edit-submit-btn').click(function(e) {
            e.preventDefault();

            let id = $('#itemId').val();
            let name = $('#itemName').val();
            let category = $('#itemCategory').val();
            let price = $('#itemPrice').val();
            let description = $('#itemDescription').val();
            let images = document.getElementById('sampleEditImages').files;

            let formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('id', id);
            formData.append('name', name);
            formData.append('category', category);
            formData.append('price', price);
            formData.append('description', description);

            Array.from(images).forEach((image, index) => {
                formData.append(`images[${index}]`, image);
            });

            $.ajax({
                url: '/products_update',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Update Successful',
                        icon: 'success',
                        confirmButtonText: 'OK',
                    })  
                },
                error: function(err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred. Please try again.',
                        confirmButtonText: 'OK',
                    });
                },
            });
        });



    })

    function setStatus(eventStatus, itemId) {
        document.getElementById('statusInput' + itemId).value = eventStatus;
        document.getElementById('statusForm' + itemId).submit();
    }
</script>

@endsection