@extends('layouts.minimal')

@section('title', 'Setup Wizard')

@section('content')

    <style>
        
        .progress {
            width: 13%;
        }

        .step-content {
            min-height: 300px;
            transition: all 0.3s ease;
        }

        .step-header {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .step-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #2f6fed;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            margin: 0px 15px 15px;
        }

        .step-circle.inactive {
            background: #cbd5e1;
        }

        .connected {
            background: #78b854;
        }

    </style>


    <div class="page error-bg" id="particles-js">
        <!-- Start::error-page -->
        <div class="error-page  ">
            <div class="container">
                <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
                    <div class="">
                        <div class="my-5 d-flex justify-content-center">
                            <a href="index.html">
                                <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                                <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                            </a>
                        </div>
                        <div class="card custom-card rectangle2">
                            <div class="card-body p-5 rectangle3">
                                <p class="h4 fw-semibold mb-2 text-center">Setup Your Store</p>
                                <p class="mb-4 text-muted op-7 fw-normal text-center">Welcome To Geek Search Console</p>

                                <!-- STEP HEADER -->
                                <div class="row my-4">
                                    <div class="col text-center">
                                        <div class="step-header">
                                            <div class="progress mb-3" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                                            </div>
                                            <div class="step-circle">✓</div>
                                            <p class="fs-16 me-3">Step 1 of 3</p>

                                            <div class="progress mb-3" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                                            </div>
                                            <div class="step-circle">✓</div>
                                            <p class="fs-16 me-3">Step 2 of 3</p>

                                            <div class="progress mb-3" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                                            </div>
                                            <div class="step-circle">✓</div>
                                            <p class="fs-16 me-3">Step 3 of 3</p>

                                            <div class="progress mb-3" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-6 mx-auto step-content">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <div class="card-title mx-auto">Connect Your Google Account</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center border p-2 rounded">
                                                
                                                <img src="{{ asset('assets/images/setup-wizard/01.png') }}" alt="img" width="350" class="img-fluid rounded my-2">

                                                <p class="fs-14 text-muted fs-semibold mb-0">Sign in with your Google account to connect to Geek Search Console.</p>

                                               {{-- {{ $store = 0 }} --}}
                                                
                                                @if($store)
                                                    <!-- <a href="{{ url('google/redirect') }}" class="btn btn-primary-gradient btn-wave my-3">Connect to Google</a> -->

                                                    <a
                                                        href="{{ route('google.redirect', ['store_id' => $store->store_id]) }}"
                                                        class="btn btn-primary-gradient btn-wave my-3"
                                                    >
                                                        Connect Google
                                                    </a>


                                                @else
                                                    <!-- <button type="button" class="btn btn-success-gradient btn-wave my-3 fs-16">
                                                        <i class="bi bi-patch-check"></i> Connected 
                                                    </button> -->

                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <div class="connected rounded py-2 px-4 my-3 fs-16 text-white">
                                                            <i class="bi bi-patch-check"></i> Connected
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- <a href="#" class="link d-block">Learn More</a> -->
                                                
                                            </div> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 mx-auto  step-content d-none">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <div class="card-title mx-auto">Verify Your Domain</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center border p-2">
                                                <a href="javascript:void(0);">
                                                    <img src="{{ asset('assets/images/setup-wizard/02.png') }}" alt="img" width="350" class="img-fluid rounded my-2">
                                                </a>
                                                
                                                <p class="fs-14 text-muted fs-semibold mb-0">Verify your store domain with Geek Search Console.</p>

                                        
                                                {{ $store = 1 }}
                                                
                                                @if(!$store)
                                                    <button class="btn btn-primary my-1 me-2 disabled">Complete Step 1 First</button>
                                                @elseif($store == 1)
                                                    <form method="POST" action="{{ url('/generate-meta') }}">
                                                        @csrf
                                                        <a href="{{ url('google/redirect') }}" class="btn btn-primary-gradient btn-wave my-3">Verify Domain</a>
                                                        <input type="text" name="store_id" value="80032334062">
                                                        <button type="submit">Generate Meta</button>
                                                    </form>

                                                    <form method="POST" action="{{ url('inject-meta') }}">
                                                        @csrf
                                                        <input type="text" name="store_id" value="80032334062">
                                                        <button type="submit">Inject Meta</button>
                                                    </form>

                                                    <form method="POST" action="{{ url('verify-domain') }}">
                                                        @csrf
                                                        <input type="text" name="store_id" value="80032334062">
                                                        <button type="submit">Verify Domain</button>
                                                    </form>

                                                    <form method="POST" action="{{ url('submit-sitemap') }}">
                                                        @csrf
                                                        <input type="text" name="store_id" value="80032334062">
                                                        <button type="submit">Submit Sitemap</button>
                                                    </form>

                                                    <button id="verifyBtn">Verify Domain</button>
                                                @else
                                                    <!-- <button type="button" class="btn btn-primary my-1 me-2 disabled">
                                                        <span class="badge ms-2 bg-white text-primary">✔</span> Domain Verified 
                                                    </button> -->

                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <div class="connected rounded py-2 px-4 my-3 fs-16 text-white">
                                                            <i class="bi bi-patch-check"></i> Domain Verified
                                                        </div>
                                                    </div>
                                                @endif

                                                <small class="d-block mt-2">
                                                    Need Help? <a href="#" class="link">Verify Domain</a>
                                                </small>
                                            </div> 
                                        </div>

                                        <div class="step" id="step-2">
                                            <h5>Verify Domain</h5>

                                            <p>Copy this meta tag and paste into your Shopify theme:</p>

                                            <textarea id="metaTag" class="form-control mb-3" rows="3" readonly></textarea>

                                            <button class="btn btn-outline-primary mb-3" onclick="copyMeta()">
                                                Copy Meta Tag
                                            </button>

                                            <p>
                                                Go to: <b>Shopify → Online Store → Themes → Edit Code → theme.liquid</b><br>
                                                Paste inside &lt;head&gt;
                                            </p>

                                            <button class="btn btn-success" onclick="verifyDomain()">
                                                Verify Domain
                                            </button>

                                            <div id="verifyStatus" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 mx-auto  step-content d-none">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <div class="card-title mx-auto">Submit Your Sitemap</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center border p-2">
                                                <a href="javascript:void(0);">
                                                    <img src="{{ asset('assets/images/setup-wizard/03.png') }}" alt="img" width="350" class="img-fluid rounded my-2">
                                                </a>
                                                
                                                <p class="fs-14 text-muted fs-semibold mb-0">Submit your sitemap to Google for better indexing.</p>

                                                <button class="btn btn-primary my-1 me-2 disabled">Complete Step 2 First</button>

                                                <form method="POST" action="">
                                                    @csrf
                                                    <button class="btn btn-primary-gradient btn-wave">Submit Sitemap</button>
                                                </form>

                                                <button type="button" class="btn btn-primary my-1 me-2 disabled">
                                                    <span class="badge ms-2 bg-white text-primary">✔</span> Completed 
                                                </button>
                                                <a href="#" class="d-block mt-2">Skip for Now</a>
                                            </div> 
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button id="prevBtn" class="btn btn-secondary">Previous</button>
                                    <button id="nextBtn" class="btn btn-primary">Next</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End::error-page -->
    </div>


    <!-- Start::app-content -->
    <div class="main-content">
        {{--
        <div class="container py-5">

            <h3 class="fw-medium text-center">Setup Your Store</h3>

            <!-- Progress -->
            <div class="text-center my-4">
                <h5 id="stepTitle">Step 1 of 3</h5>
                <div class="progress w-50 mx-auto">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 33%"></div>
                </div>
            </div>

            <!-- STEP 1 -->
            <div class="step-content">
                <div class="col-xl-6 mx-auto">
                    <div class="card custom-card">
                        <div class="card-header text-center">Connect Your Google Account</div>
                        <div class="card-body text-center">
                            <p>Connect your Google account.</p>
                            <button class="btn btn-primary">Connect to Google</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2 -->
            <div class="step-content d-none">
                <div class="col-xl-6 mx-auto">
                    <div class="card custom-card">
                        <div class="card-header text-center">Verify Your Domain</div>
                        <div class="card-body text-center">
                            <p>Verify your domain.</p>
                            <button class="btn btn-primary">Verify Domain</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="step-content d-none">
                <div class="col-xl-6 mx-auto">
                    <div class="card custom-card">
                        <div class="card-header text-center">Submit Sitemap</div>
                        <div class="card-body text-center">
                            <p>Submit sitemap.</p>
                            <button class="btn btn-primary">Submit Sitemap</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="text-center mt-4">
                <button id="prevBtn" class="btn btn-secondary">Previous</button>
                <button id="nextBtn" class="btn btn-primary">Next</button>
            </div>

        </div> --}}
    </div>
    <!-- End::app-content -->

    
    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll(".step-content");
        const nextBtn = document.getElementById("nextBtn");
        const prevBtn = document.getElementById("prevBtn");
        const progressBar = document.getElementById("progressBar");
        const stepTitle = document.getElementById("stepTitle");

        function showStep(index) {
            steps.forEach((step, i) => {
                step.classList.toggle("d-none", i !== index);
            });

            // Update progress
            let percent = ((index + 1) / steps.length) * 100;
            progressBar.style.width = percent + "%";
            stepTitle.innerText = `Step ${index + 1} of ${steps.length}`;

            // Button visibility
            prevBtn.style.display = index === 0 ? "none" : "inline-block";
            nextBtn.innerText = index === steps.length - 1 ? "Finish" : "Next";

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        nextBtn.addEventListener("click", () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });

        prevBtn.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Init
        showStep(currentStep);
    </script>

    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll(".step-content");
        const nextBtn = document.getElementById("nextBtn");
        const prevBtn = document.getElementById("prevBtn");

        function showStep(index) {
            steps.forEach((step, i) => {
                step.classList.toggle("d-none", i !== index);
            });

            // Hide/show buttons
            prevBtn.style.display = index === 0 ? "none" : "inline-block";
            nextBtn.innerText = index === steps.length - 1 ? "Finish" : "Next";

            // Scroll top
            window.scrollTo({ top: 0, behavior: "smooth" });
        }

        nextBtn.addEventListener("click", () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });

        prevBtn.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Init
        showStep(currentStep);
    </script>


    <!-- new code for step 2 verify domain  -->



    <script>
        document.getElementById('verifyBtn').addEventListener('click', function() {

            let btn = this;
            btn.innerText = "Verifying...";
            btn.disabled = true;

            fetch('/verify-domain', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {

                if (data.status) {
                    btn.innerText = "Verified ✅";

                    setTimeout(() => {
                        window.location.href = "/submit-sitemap";
                    }, 1000);

                } else {
                    btn.innerText = "Try Again";
                    btn.disabled = false;
                    alert(data.message);
                }
            });
        });
    </script>


    <script>
        function loadMetaTag() {
            axios.post('/generate-meta', {store_id: storeId})
                .then(res => {
                    document.getElementById('metaTag').value = res.data.meta_tag;
                });
        }

        function copyMeta() {
            let textarea = document.getElementById('metaTag');
            textarea.select();
            document.execCommand("copy");
            alert("Copied!");
        }

        function verifyDomain() {
            document.getElementById('verifyStatus').innerHTML = "Checking...";

            axios.post('/verify-domain', {store_id: storeId})
                .then(res => {
                    if (res.data.status === 'verified') {
                        document.getElementById('verifyStatus').innerHTML = "✅ Verified";
                        changeStep(1);
                    } else {
                        document.getElementById('verifyStatus').innerHTML = "❌ Not verified yet";
                    }
                });
        }

        function submitSitemap() {
            document.getElementById('sitemapStatus').innerHTML = "Submitting...";

            axios.post('/submit-sitemap', {store_id: storeId})
                .then(res => {
                    document.getElementById('sitemapStatus').innerHTML = "✅ Submitted";
                    changeStep(1);
                });
        }

    </script>

    <!-- veryfy domain code ended  -->

     
@endsection

