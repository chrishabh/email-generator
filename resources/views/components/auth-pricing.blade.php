<section id="pricing" class="section-padding">
    <div class="min-vh-100 bg-light d-flex align-items-center py-5 price-custom-section">
        <div class="container">

            <!-- Page Header -->
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Plans & Pricing</h2>
                <h6 class="pricing-sub-header">Choose Limited or Unlimited — No credit card required!</h6>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-pills justify-content-center mb-4" id="pricingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button 
                        class="nav-link active" 
                        id="limited-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#limited" 
                        type="button" 
                        role="tab" 
                        aria-controls="limited" 
                        aria-selected="true">
                        Limited Plans
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button 
                        class="nav-link" 
                        id="unlimited-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#unlimited" 
                        type="button" 
                        role="tab" 
                        aria-controls="unlimited" 
                        aria-selected="false">
                        Unlimited Plans
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="pricingTabsContent">

                <!-- Limited Plans -->
                <div class="tab-pane fade show active" id="limited" role="tabpanel" aria-labelledby="limited-tab">
                    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
                        @foreach([
                            ['price'=>'5', 'credits'=>'2,000'],
                            ['price'=>'10', 'credits'=>'10,000'],
                            ['price'=>'20', 'credits'=>'25,000'],
                            ['price'=>'50', 'credits'=>'100,000'],
                            ['price'=>'100', 'credits'=>'250,000'],
                            ['price'=>'200', 'credits'=>'600,000'],
                            ['price'=>'400', 'credits'=>'1.5M'],
                            ['price'=>'800', 'credits'=>'5M'],
                            ['price'=>'1500', 'credits'=>'10M'],
                            ['price'=>'3000', 'credits'=>'25M'],
                        ] as $plan)
                        <div class="col" style="padding: 10px;">
                            <div class="custom-card h-100 shadow-sm">
                                <div class="card-header text-center btn-primary text-white fw-bold" style="
    background: #007bff;">
                                    ₹{{ $plan['price'] }}
                                </div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4 text-center">
                                    <h5 class="mb-3">{{ $plan['credits'] }} Credits</h5>

                                    <form action="{{ url('/checkout') }}" method="POST" class="w-100 d-flex justify-content-center">
                                        @csrf
                                        <input type="hidden" name="price" value="{{ $plan['price'] }}">
                                        <input type="hidden" name="credits" value="{{ $plan['credits'] }}">
                                        <input type="hidden" name="plan_name" value="Limited">

                                        <button type="submit" class="btn btn-primary mt-2">
                                            Purchase
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Unlimited Plans -->
                <div class="tab-pane fade" id="unlimited" role="tabpanel" aria-labelledby="unlimited-tab">
                    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
                        @foreach([
                            ['price'=>'19', 'duration'=>'7 Days'],
                            ['price'=>'49', 'duration'=>'1 Month'],
                            ['price'=>'129', 'duration'=>'3 Months'],
                            ['price'=>'249', 'duration'=>'6 Months'],
                        ] as $plan)
                        <div class="col" style="padding: 10px;">
                            <div class="custom-card h-100 shadow-sm">
                                <div class="card-header text-center bg-primary text-white fw-bold">
                                    ₹{{ $plan['price'] }}
                                </div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center p-4 text-center">
                                    <h5 class="mb-3">Unlimited for {{ $plan['duration'] }}</h5>

                                    <form action="{{ url('/checkout') }}" method="POST" class="w-100 d-flex justify-content-center">
                                        @csrf
                                        <!-- Hidden values sent in POST request -->
                                        <input type="hidden" name="price" value="{{ $plan['price'] }}">
                                        <input type="hidden" name="duration" value="{{ $plan['duration'] }}">
                                        <input type="hidden" name="plan_name" value="Unlimited">

                                        <button type="submit" class="btn btn-primary mt-2">
                                            Purchase
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Make sure you have Bootstrap JS included -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
