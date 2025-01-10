<div class="main-header @if(auth()->user()->role == 'client') d-none @endif">

    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img
                    src="{{ $organization ? asset('orgImage/' . $organization->orgImage) : 'https://via.placeholder.com/100/CCCCCC/FFFFFF' }}" alt="Image Preview" style="display: block; width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd; padding: 5px; cursor: pointer;" onclick="document.getElementById('orgImage').click();"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <!-- Navbar Header -->
    <nav
        class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">


            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                    class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                    <a
                        class="nav-link dropdown-toggle"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-expanded="false"
                        aria-haspopup="true">
                        <i class="fa fa-search"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-search animated fadeIn">
                        <form class="navbar-left navbar-form nav-search">
                            <div class="input-group">
                                <input
                                    type="text"
                                    placeholder="Search ..."
                                    class="form-control" />
                            </div>
                        </form>
                    </ul>
                </li>

                <li class="nav-item  topbar-user dropdown hidden-caret mt-2" style="list-style-type: none;">
                    <a
                        class="dropdown-toggle "
                        data-bs-toggle="dropdown"
                        href="#"
                        aria-expanded="false">
                        My Inbox

                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            @if(isset($inbox) && count($inbox) > 0)
                            @foreach($inbox as $message)
                            <div class="card mb-2 mx-2" onclick="window.location='{{ route('messages', ['inboxId' => $message->id]) }}'" style="cursor: pointer;">
                                <div class="card-body">

                                    @if($profile->role !== 'vendor')
                                    <h6 class="text-sm">{{ $message->user->fullname }}</h6>
                                    @else
                                    <h6 class="text-sm">{{ $message->user_customer->fullname }}</h6>
                                    @endif
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


                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a
                        class="dropdown-toggle profile-pic"
                        data-bs-toggle="dropdown"
                        href="#"
                        aria-expanded="false">
                        @if ($profile)
                        <div class="avatar-sm mx-2">
                            <img src="{{ $profile ? asset('profile/' . $profile->profile) : 'https://via.placeholder.com/100/CCCCCC/FFFFFF' }}" alt="Image Preview" style="display: block; width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd; padding: 5px; cursor: pointer;">
                        </div>
                        <span class="profile-username pt-2">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold">{{$profile->name ?? null}}</span>
                        </span>
                        @else
                        <span class="badge badge-warning ">Please Complete your Profile</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if($profile)
                                        <img
                                            src="{{ $profile ? asset('profile/' . $profile->profile) : 'https://via.placeholder.com/100/CCCCCC/FFFFFF' }}"
                                            alt="image profile"
                                            class="avatar-img rounded" />
                                        @else
                                        @endif
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ explode(' ', $profile->fullname ?? '')[0] ?? '' }}</h4>

                                        @if ($profile && $profile->status == 'disabled')
                                        <button
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#detailDrawer"
                                            type="button"
                                            class="btn btn-xs btn-secondary btn-sm detail-update">
                                            Verify Account
                                        </button>
                                        @else
                                        <button
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#detailDrawer"
                                            type="button"
                                            class="btn btn-xs btn-secondary btn-sm detail-update" disabled>
                                            Account Verified
                                        </button>
                                        @endif



                                    </div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Account Setting</a>
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
    </nav>
    <!-- End Navbar -->

    <div class="offcanvas offcanvas-end" tabindex="-1" id="detailDrawer" aria-labelledby="detailDrawerLabel">
        <div class="offcanvas-header">
            <h5 id="detailDrawerLabel">Send Supported Document</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="supportDocumentsForm" enctype="multipart/form-data">
                @csrf

                <!-- Support Documents Section -->
                <div class="mb-3">
                    <label for="supportDocuments" class="form-label">Add Support Documents (Max: 10)</label>
                    <input type="file" name="documents[]" id="supportDocuments" class="form-control" accept="image/*,.pdf,.doc,.docx" multiple onchange="previewDocuments(event)">
                    <small class="text-muted">Allowed file types: Images, PDFs, Word Documents</small>
                </div>

                <!-- Preview Section -->
                <div id="documentsPreview" class="mb-3">
                    <h6>Document Previews:</h6>
                    <div id="previewContainer" class="row g-2"></div>
                </div>

                <div class="mb-3">
                    <label for="additionalImage" class="form-label">Upload Personal GCash QRcode Image</label>
                    <input type="file" name="additional_image" id="additionalImage" class="form-control" accept="image/*" onchange="previewAdditionalImage(event)">
                    <small class="text-muted">Only image files are allowed for this field.</small>
                </div>

                <!-- Additional Image Preview -->
                <div class="mb-3 text-center">
                    <img id="additionalImagePreview" src="https://via.placeholder.com/100/CCCCCC/FFFFFF" alt="Additional Image Preview" style="width: 100px; height: 100px; border-radius: 5px; border: 1px solid #ddd; object-fit: cover;">
                </div>

                <!-- Submit Button -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>


</div>

<style>

</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>



<script>
    $(document).ready(function() {
        $('#supportDocumentsForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Create a FormData object from the form
            let formData = new FormData(this);

            // Handle the additional image (GCash QR code)
            let additionalImage = $('#additionalImage')[0].files[0];
            if (additionalImage) {
                formData.append('additional_image', additionalImage);
            }

            // Perform the AJAX request to upload both documents and the image
            $.ajax({
                url: '{{ route("support_document") }}', // Adjust this to your form submission route
                type: 'POST',
                data: formData,
                processData: false, // Prevents jQuery from processing the data
                contentType: false, // Prevents jQuery from setting the content type
                success: function(response) {
                    // Show success message
                    Swal.fire({
                        title: 'Success!',
                        text: 'Documents and GCash QR code uploaded successfully',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); // Reload the page after success
                        }
                    });
                },
                error: function(err) {
                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;
                        for (let key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                console.error(key + ": " + errors[key]);
                            }
                        }
                        // Show validation error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please check the input data.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // Show general error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: err.responseJSON.message || 'An error occurred',
                            confirmButtonText: 'OK'
                        });
                        console.error(err);
                    }
                }
            });
        });
    });




    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result; // Set the source of the image preview
                imagePreview.style.display = 'block'; // Make the image visible
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            imagePreview.src = ''; // Clear the preview if no file is selected
            imagePreview.style.display = 'none'; // Hide the image
        }
    }

    function previewImageOrg(event) {
        const imagePreview = document.getElementById('imagePreviewOrg');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result; // Set the source of the image preview
                imagePreview.style.display = 'block'; // Make the image visible
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            imagePreview.src = ''; // Clear the preview if no file is selected
            imagePreview.style.display = 'none'; // Hide the image
        }
    }

    function previewDocuments(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('previewContainer');
        previewContainer.innerHTML = ''; // Clear existing previews

        if (files.length > 10) {
            alert("You can only upload up to 10 documents.");
            event.target.value = ""; // Clear the input
            return;
        }

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function(e) {
                const colDiv = document.createElement('div');
                colDiv.className = 'col-4 text-center';

                let previewElement;

                if (file.type.startsWith('image/')) {
                    // Image preview
                    previewElement = document.createElement('img');
                    previewElement.src = e.target.result;
                    previewElement.style = "width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px;";
                } else {
                    // Generic preview for non-image files
                    previewElement = document.createElement('div');
                    previewElement.style = "width: 100%; height: 50px; border: 1px solid #ddd; padding: 5px; border-radius: 5px; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;";
                    previewElement.textContent = file.name;
                }

                colDiv.appendChild(previewElement);
                previewContainer.appendChild(colDiv);
            };

            reader.readAsDataURL(file);
        });
    }

    function previewAdditionalImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('additionalImagePreview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result; // Update the preview image source
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = "https://via.placeholder.com/100/CCCCCC/FFFFFF"; // Reset preview if no file selected
        }
    }
</script>