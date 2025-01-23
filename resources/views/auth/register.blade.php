@extends('layouts.app')

@section('content')
<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="main-panel">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">

                        <h4>New here?</h4>
                        <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                        <form class="pt-3" method="POST" action="{{ route('register_web') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Profile Picture -->
                            <div class="form-group text-center">
                                <label for="profilePicture" class="profile-circle">
                                    <img id="profileImage" src="https://www.gravatar.com/avatar/?d=mp&s=120" alt="Profile Image" style="width: 120px; height: 120px; border-radius: 50%; cursor: pointer;">
                                </label>
                                <input type="file" id="profilePicture" name="profilePicture" accept="image/*" style="display: none;" onchange="previewImage(event)">
                                @error('profilePicture')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div class="form-group">
                                <input type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname') }}" required autocomplete="fullname" placeholder="Full Name">
                                @error('fullname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- User ID -->
                            <div class="form-group">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control @error('contact') is-invalid @enderror" name="contact" value="{{ old('contact') }}" required autocomplete="contact" placeholder="Contact">
                                @error('contact')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address" placeholder="Address"></textarea>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- Role Selection -->
                            <div class="form-group">

                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="" disabled selected>Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="N/A">Prefer not to Say</option>
                                </select>
                            </div>
                            <div class="form-group">

                                <select class="form-control" id="role" name="role" required>
                                    <option value="" disabled selected>Create Account As</option>
                                    <option value="client">Customer/Client</option>
                                    <option value="vendor">Seller</option>
                                </select>
                            </div>

                            <!-- Vendor-Specific Fields -->
                            <div id="vendorFields" style="display: none;">
                                <div class="form-group">
                                    <input type="text" id="shopName" class="form-control" name="shop_name" placeholder="Shop Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" id="shopType" class="form-control" name="type" placeholder="Type">
                                </div>
                            </div>

                            <!-- Password Fields -->
                            <div class="form-group position-relative">
                                <input type="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                                <span class="position-absolute"
                                    style="top: 53.5%; right: 3rem; transform: translateY(-50%); cursor: pointer;"
                                    onclick="togglePasswordVisibility('passwordInput', this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group position-relative">
                                <input type="password" id="passwordInputConfirm" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                                <span class="position-absolute"
                                    style="top: 53.5%; right: 3rem; transform: translateY(-50%); cursor: pointer;"
                                    onclick="togglePasswordVisibility('passwordInputConfirm', this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>

                            <!-- Terms Checkbox -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <label class="form-check-label text-muted">
                                        <input type="checkbox" class="form-check-input" id="termsCheckbox">
                                        I agree to all Terms & Conditions
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-3">
                                <button type="submit" id="submitButton" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">{{ __('SIGN UP') }}</button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview Profile Image
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profileImage');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Toggle Vendor Fields
    document.getElementById('role').addEventListener('change', function() {
        const vendorFields = document.getElementById('vendorFields');
        const shopName = document.getElementById('shopName');
        const shopType = document.getElementById('shopType');

        if (this.value === 'vendor') {
            vendorFields.style.display = 'block';
        } else {
            vendorFields.style.display = 'none';
            shopName.value = '';
            shopType.value = '';
        }
    });

    // Enable/Disable Submit Button
    document.addEventListener('DOMContentLoaded', function() {
        const termsCheckbox = document.getElementById('termsCheckbox');
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;
        termsCheckbox.addEventListener('change', function() {
            submitButton.disabled = !termsCheckbox.checked;
        });
    });

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
</script>
@endsection