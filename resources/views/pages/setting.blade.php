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
                                    <label for="" class="card-title">Settings</label>
                                </div>
                                <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary add-item" data-bs-toggle="modal" data-bs-target="#addModal">Add Item</button>
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
                                                <td>{{ $item->type }}</td>
                                                <td>
                                                    @if ($item->type == 'faq')
                                                    <p>NULL</p>
                                                    @else
                                                    <img style="display: block; width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd; padding: 5px; cursor: pointer;" src="{{ asset('upload-image/' . $item->image)}}" alt="" srcset="">
                                                    @endif
                                                </td>
                                                <td>{{ $item->message}}</td>
                                                <td>{{ $item->submessage}}</td>

                                                <td>
                                                    <button type="button" class="btn btn-rounded btn-icon edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
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
                <h5 class="modal-title">Add Item</h5>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf
                    <div class="form-group">
                        <label for="addType">Type:</label>
                        <select id="addType" name="type" class="form-control" required>
                            <option value="welcome">Welcome</option>
                            <option value="faq">FAQ</option>
                        </select>
                    </div>

                    <div class="form-group w-100" style="height: 150px;">
                        <div class="d-flex justify-content-center align-items-center">
                            <label for="welcomePicture" class="">
                                <img id="welcomeImage" src="https://www.gravatar.com/avatar/?d=mp&s=120" alt="Welcome Image" style="width: 100%; height: 120px; cursor: pointer;">
                            </label>
                            <input type="file" id="welcomePicture" name="welcomePicture" accept="image/*" style="display: none;" onchange="previewImage(event)">
                            @error('welcomePicture')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="submessage">Submessage:</label>
                        <textarea id="submessage" name="submessage" class="form-control" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 add-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
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
<!-- Froala Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/froala-editor@4.0.10/css/froala_editor.pkgd.min.css" rel="stylesheet">

<!-- jQuery (required for Froala Editor) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Froala Editor JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@4.0.10/js/froala_editor.pkgd.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<!-- Froala Editor CSS -->

<script>
    $(document).ready(function() {
        // Monitor the dropdown value change
        $('#addType').change(function() {
            const type = $(this).val();

            if (type === 'faq') {
                // Hide the image field
                $('.form-group.w-100').hide();
            } else {
                // Show the image field
                $('.form-group.w-100').show();
            }
        });

        // Initial state on page load
        if ($('#addType').val() === 'faq') {
            $('.form-group.w-100').hide();
        } else {
            $('.form-group.w-100').show();
        }
    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('welcomeImage');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    $(document).ready(function() {
        // Initialize Froala editor on the textarea with ID 'message'

        new FroalaEditor('#message', {
            toolbarButtons: ['bold', 'italic', 'underline', 'fontSize', 'fontFamily', 'align', 'insertLink'],
            quickInsertEnabled: false,
        });

        // Initialize Froala editor on the textarea with ID 'submessage'
        new FroalaEditor('#submessage', {
            toolbarButtons: ['bold', 'italic', 'underline', 'fontSize', 'fontFamily', 'align', 'insertLink'],
            quickInsertEnabled: false,
        });
    });

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
        return true;
    }
    $(document).ready(function() {
        $('.add-item').click(function() {
            $('#addModal').modal('show');
        });

        $('.edit-btn').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            $('#itemId').val(id);
            $('#itemName').val(name);
            $('#editModal').modal('show');
        });

        $('.add-btn').click(function(e) {
            e.preventDefault();
            let type = document.getElementById('addType').value
            let message = document.getElementById('message').value
            let submessage = document.getElementById('submessage').value
            let image = document.getElementById('welcomePicture').files[0]


            let formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('type', type);
            formData.append('message', message);
            formData.append('submessage', submessage);
            if (image) {
                formData.append('image', image); // Add the image file to the request
            }
            $.ajax({
                url: '/addText',
                type: 'POST',
                data: formData,
                contentType: false, // Prevent jQuery from setting content type
                processData: false, // Prevent jQuery from processing the FormData object
                success: function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your content has been added successfully.',
                        icon: 'success',
                    });
                },
                error: function(err) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong.',
                        icon: 'error',
                    });
                },
            });
        })

        $('.edit-submit-btn').click(function(e) {
            e.preventDefault();

            // Get form values
            let id = $('#itemId').val();
            let name = $('#itemName').val();


            $.post('/type_edit', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
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
        });


    })

    function setStatus(eventStatus, itemId) {
        document.getElementById('statusInput' + itemId).value = eventStatus;
        document.getElementById('statusForm' + itemId).submit();
    }
</script>

@endsection