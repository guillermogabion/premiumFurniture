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

                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#loadingModal" class="btn btn-rounded btn-icon delete-btn" data-id="{{ $item->id }}">
                                                        <i class="fa fa-trash text-danger"></i>
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
<div>
    <div id="editModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <!-- Modal Header -->
                <div class="modal-header custom-orange text-white border-0 rounded-top">
                    <h5 class="modal-title fw-bold" id="invoiceModalLabel">Edit</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 py-4">
                    <div class="modal-body">
                        <form id="editForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" id="editId" name="id">
                                <label for="editType">Type:</label>
                                <select id="editType" name="type" class="form-control" required>
                                    <option value="welcome">Welcome</option>
                                    <option value="faq">FAQ</option>
                                </select>
                            </div>

                            <!-- Image Upload Section -->
                            <div class="form-group w-100" style="height: 150px;">
                                <div class="d-flex justify-content-center align-items-center">
                                    <label for="editPicture">
                                        <img id="editImage" src="https://www.gravatar.com/avatar/?d=mp&s=120" alt="Welcome Image" style="width: 100%; height: 120px; cursor: pointer;">
                                    </label>
                                    <input type="file" id="editPicture" name="editPicture" accept="image/*" style="display: none;" onchange="previewEditImage(event)">
                                    @error('editPicture')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Message Section -->
                            <div class="form-group">
                                <label for="editMessage">Message:</label>
                                <textarea id="editMessage" name="editMessage" class="form-control" required></textarea>
                                @error('editMessage')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- Submessage Section -->
                            <div class="form-group">
                                <label for="editSubMessage">Submessage:</label>
                                <textarea id="editSubMessage" name="editSubMessage" class="form-control" required></textarea>
                                @error('editSubMessage')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary mt-4 edit-submit-btn">Submit</button>
                        </form>

                        <script>
                            // Initialize Froala Editor on the fields
                            new FroalaEditor('#editMessage', {
                                toolbarButtons: ['bold', 'italic', 'underline', 'fontSize', 'fontFamily', 'align', 'insertLink'],
                                quickInsertEnabled: false,
                            });

                            new FroalaEditor('#editSubMessage', {
                                toolbarButtons: ['bold', 'italic', 'underline', 'fontSize', 'fontFamily', 'align', 'insertLink'],
                                quickInsertEnabled: false,
                            });
                        </script>

                    </div>

                </div>
                <!-- Modal Body -->
            </div>
        </div>
    </div>
</div>

<div id="loadingModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="loadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="loader" id="loader"></div>
            </div>
        </div>
    </div>
</div>
<!-- Froala Editor CSS -->

<!-- jQuery (required for Froala Editor) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Froala Editor JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@4.0.10/js/froala_editor.pkgd.min.js"></script>

<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/froala-editor@4.0.10/css/froala_editor.pkgd.min.css" rel="stylesheet">
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

    $('.edit-btn').click(function() {
        let id = $(this).data('id');
        let type = $(this).data('type');
        let message = $(this).data('message');
        let submessage = $(this).data('submessage');
        let images = $(this).data('image');



        $('#editId').val(id);
        $('#editType').val(type);
        const itemMessageEditor = new FroalaEditor('#editMessage', {
            toolbarButtons: ['bold', 'italic', 'underline', 'fontSize', 'fontFamily', 'align', 'insertLink'],
            quickInsertEnabled: false,
        });

        const itemSubMessageEditor = new FroalaEditor('#editSubMessage', {
            toolbarButtons: ['bold', 'italic', 'underline', 'fontSize', 'fontFamily', 'align', 'insertLink'],
            quickInsertEnabled: false,
        });

        // Set the values in the Froala editor fields
        itemMessageEditor.html.set(message); // Set the message in the editor
        itemSubMessageEditor.html.set(submessage);


        if (images) {
            $('#editImage').attr('src', '/upload-image/' + images);
        } else {
            // If no image is available, use a default image
            let defaultImage = `<img src="{{ asset('img/kaiadmin/logos.png') }}" alt="Default Image" style="width: 220px; height: 220px; border-radius: 20px;">`;
            $('#editImage').append(defaultImage);
        }



        // Show the modal
        $('#editModal').modal('show');
    })


    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('welcomeImage');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewEditImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('editImage');
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
                    }).then(() => window.location.reload());
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


        $('.delete-btn').click(function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('#loadingModal').show();

            setTimeout(function() {
                $.post('/delete-item', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,



                }).done(function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Removing Success',
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


            }, 2000)
        })


    })

    function setStatus(eventStatus, itemId) {
        document.getElementById('statusInput' + itemId).value = eventStatus;
        document.getElementById('statusForm' + itemId).submit();
    }
</script>

@endsection