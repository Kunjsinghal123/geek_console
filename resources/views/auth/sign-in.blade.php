@extends('layouts.minimal')

@section('title', 'Sign In')

@section('content')

    <div class="page error-bg" id="particles-js">
        <!-- Start::sign-in-page -->
        <div class="error-page  ">
            <div class="container">
                <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
                    <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                        <div class="my-5 d-flex justify-content-center">
                            <a href="{{ route('login') }}">
                                <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                                <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                            </a>
                        </div>
                        
                        <div class="card custom-card rectangle2">
                            <div class="card-body p-5 rectangle3">

                                <p class="h4 fw-semibold mb-2 text-center">Sign In</p>

                                <p class="mb-4 text-muted op-7 fw-normal text-center">Welcome To Geek Search Console</p>

                                <form>
                                    @csrf 
                                    <label for="signin-email" class="form-label text-default">Store Email</label>

                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input type="email" class="form-control form-control-lg" id="signin-email" placeholder="Store's Email" aria-label="Recipient's Email" aria-describedby="send-otp-btn" required>
                                            
                                            <button class="btn btn-primary" type="button" id="send-otp-btn">
                                                Send OTP
                                            </button>
                                        </div>

                                        <div id="email-error" class="invalid-feedback"></div>
                                        <div id="email-success" class="valid-feedback"></div>
                                    </div>
                                    
                                    <div id="otp-section" style="display:none;">

                                        <label for="one" class="form-label text-default">OTP</label>
                                        
                                        <div class="row gy-3">
                                            <div class="col-xl-12 mb-3">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <input type="text" class="form-control text-center otp" maxlength="1">
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="text" class="form-control text-center otp" maxlength="1">
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="text" class="form-control text-center otp" maxlength="1">
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="text" class="form-control text-center otp" maxlength="1">
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="text" class="form-control text-center otp" maxlength="1">
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="text" class="form-control text-center otp" maxlength="1">
                                                    </div>
                                                </div>

                                                <div id="otp-error" class="invalid-feedback"></div>
                                                <div id="otp-success" class="valid-feedback"></div>
                                            </div>

                                            <div class="col-xl-12 d-grid mt-2">
                                                <button type="button" id="verify-otp-btn" class="btn btn-lg btn-primary">Verify OTP</button>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <p class="fs-12 text-danger mt-3 mb-0">Don't share the verification code with anyone</p>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End::sign-in-page -->
    </div>
     
@endsection