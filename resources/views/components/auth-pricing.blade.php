
@php
    $plans = [
            [
                'name' => 'Single Email Verification',
                'price' => '0',
                'color' => '  text-white', // Green
                'transform'=>'',
                'header'=> 'background:#0E866D;font-weight:bold',
                'priceCss'=>"font-size: 36px;font-weight: 400;color: #4A4A4A;font-family: 'Arial', sans-serif;text-align: center;display: block;",
                'features' => [
                    'Free Email Check',
                    'Unlimited Email Credits',
                    'Unlimited Users',
                    'Free API Integration',
                    '24x7 Customer Support',
                    'No Credit Card Required'
                ]
            ],
            [
                'name' => 'Bulk Email Verification',
                'price' => '0',
                'color' => 'text-white', // Blue
                'transform' =>'transform',
                'header'=> 'background:#3F51B5;font-weight:bold',
                'priceCss'=>"font-size: 36px;font-weight: 400;color: #4A4A4A;font-family: 'Arial', sans-serif;text-align: center;display: block;",
                'features' => [
                    'Bulk Email Check',
                    'Unlimited Email Credits',
                    'Unlimited Users',
                    'Free API Integration',
                    '24x7 Customer Support',
                    'No Credit Card Required'
                ]
            ],
            [
                'name' => 'B2B Email Finder',
                'price' => '0',
                'color' => '  text-white', // Orange
                'transform'=>'',
                'header'=> 'background:#D6721D;font-weight:bold',
                'priceCss'=>"font-size: 36px;font-weight: 400;color: #4A4A4A;font-family: 'Arial', sans-serif;text-align: center;display: block;",
                'features' => [
                    'Free B2B Email Finding',
                    'Unlimited Email Credits',
                    'Unlimited User Access',
                    '24x7 Customer Support',
                    'No Credit Card Required'
                ]
            ]
        ];

@endphp




<div class="min-vh-100 bg-light d-flex align-items-center py-5 price-custom-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Plans & Pricing</h2>
            <h6 class="pricing-sub-header wow fadeInDown" data-wow-delay="0.4s">Try first, decide later, No credit card
                required!</h6>
            <div class="shape wow fadeInDown" data-wow-delay="0.5s"></div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center wow fadeInDown custom-margin">
            @foreach($plans as $plan)
                <div class="col padding-bottom">
                    <div class="custom-card h-100 shadow-sm {{$plan['transform']}}"  >
                        <div class="card-header {{ $plan['color'] }} text-white text-center fw-bold" style="{{$plan['header']}}">
                            <h3 class="h5 mb-0 font-weight-bold ">{{ $plan['name'] }}</h3>
                        </div>

                        <div class="card-body text-center p-4">
                            <div class="display-6 fw-bold mb-1" style="{{$plan['priceCss']}}">${{ $plan['price'] }}</div>
                            <div class="text-muted small font-weight-normal">per month</div>

                            <ul class="features">
                                @foreach($plan['features'] as $feature) 
                                    <li class="included">{{ $feature }}</li>
                                @endforeach 
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- <div class="section-header text-center"> 
            <div class="header-button" style="margin-top:2rem;">
                <a rel="nofollow" href="/signup" class="btn btn-home-common">Sign up now and get 100 FREE Credits</a>
            </div>
            <p class="checkbox-text">
                <img decoding="async" src="assets/checkmark.png" width="15px" height="15px"> No monthly payment, no upfront fee, credits never expire. <br>
            </p>
        </div> --}}
    </div>
</div>