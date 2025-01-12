@extends('layouts.app')
@section('content')

<div class="container-fluid page-body-wrapper full-page-wrapper">

    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto mt-4">
                @if (isset($message))
                @if ($status == 'success')
                <div id="successBanner" class="alert text-center"
                    style="
                                    background-color: rgba(0, 128, 0, 0.5); 
                                    color: white; 
                                    font-weight: bold; 
                                    padding: 15px; 
                                    border-radius: 5px;
                                    margin-bottom: 20px;
                                    border: none;
                                ">
                    {{ $message }}
                </div>
                @else
                <div id="successBanner" class="alert text-center"
                    style="
                                    background-color: rgb(240, 38, 3, 0.5); 
                                    color: white; 
                                    font-weight: bold; 
                                    padding: 15px; 
                                    border-radius: 5px;
                                    margin-bottom: 20px;
                                    border: none;
                                ">
                    {{ $message }}
                </div>
                @endif
                @endif
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                    <div class="modal-header d-flex justify-content-center">
                        <h5 class="modal-title">Reset Password</h5>
                    </div>
                    <div class="modal-body">
                        <form id="resetPasswordForm" method="POST" action="{{ route('reset_my_password') }}">
                            @csrf
                            <div class="form-group">
                                <label for="resetEmail">Email:</label>
                                <input type="email" id="resetEmail" name="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="resetContact">Contact Number:</label>
                                <input type="number" id="resetContact" name="contact" class="form-control">
                            </div>
                            <button type="submit" class="btn custom-orange mt-4 reset-btn w-100 text-white">Submit</button>
                        </form>
                        <a href="{{ route('login') }}" class="btn btn-secondary mt-3 w-100">Return</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Automatically hide the banner after 5 seconds
    document.addEventListener("DOMContentLoaded", function() {
        const banner = document.getElementById("successBanner");
        if (banner) {
            setTimeout(() => {
                banner.style.display = "none";
            }, 5000); // 5000ms = 5 seconds
        }
    });
</script>

@endsection