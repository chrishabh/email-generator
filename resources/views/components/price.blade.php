{{-- <section id="pricing" class="section-padding">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .pricing-table {
            width: 100%;
            border-collapse: separate;
        }

        .pricing-table th,
        .pricing-table td {
            padding: 14px;
            text-align: center;
        }

        .pricing-table th {
            /* background-color: #333; */
            color: #fff;
        }

        .pricing-table td {
            border: 1px solid #ccc;
        }

        .starter {
            background-color: #76923c;
            color: white;
            border-collapse: separate;
            /* Use separate to allow rounding */
            border-spacing: 0;
            /* Remove any space between table cells */
            border-radius: 12px;
            /* Round all four corners */
            overflow: hidden;
            transform: scale(1.2);
        }

        .basic {
            background-color: #d64d26;
            color: white;
            border-collapse: separate;
            /* Use separate to allow rounding */
            border-spacing: 0;
            /* Remove any space between table cells */
            border-radius: 12px;
            /* Round all four corners */
            overflow: hidden;
        }

        .standard {
            background-color: #f79b00;
            color: white;
            border-collapse: separate;
            /* Use separate to allow rounding */
            border-spacing: 0;
            /* Remove any space between table cells */
            border-radius: 12px;
            /* Round all four corners */
            overflow: hidden;
        }

        .premium {
            background-color: #76923c;
            color: white;
        }

        .credit {
            background-color: #00a9b5;
            color: white;
        }

        .check {
            color: green;
            font-size: 20px;
        }

        .cross {
            color: red;
            font-size: 20px;
        }

        .price {
            font-size: 24px;
            font-weight: bold;
        }

        .verification {
            font-size: 24px;
            font-weight: bold;
            border-collapse: separate;
            /* Use separate to allow rounding */
            border-spacing: 0;
            /* Remove any space between table cells */
            border-radius: 12px;
            /* Round all four corners */
            overflow: hidden;
        }
    </style>
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Plans & Pricing</h2>
            <h6 class="pricing-sub-header wow fadeInDown" data-wow-delay="0.4s">Try first, decide later, No credit card
                required!</h6>
            <div class="shape wow fadeInDown" data-wow-delay="0.5s"></div>
        </div>
        <!-- <div class="row wow fadeInDown" data-wow-delay="1.2s">
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 2em;">
                <h4 class="price-logo"><img src="assets/pricing/Verification Credits.png" alt=""></h4>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 2em;">
                <h4 class="price-logo"><img class="img-fluid" src="assets/pricing/Logo_55.png" alt=""></h4>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 2em;">
                <h4 class="price-logo"><img class="img-fluid" src="assets/pricing/neverbounce-logo-black-new.png"
                        alt=""></h4>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 2em;">
                <h4 class="price-logo"><img class="img-fluid" style="margin-bottom: 1em;"
                        src="assets/pricing/ZeroBounce_55.png" alt=""></h4>
            </div>
        </div>
        <div class="row wow fadeInDown" data-wow-delay="1.4s">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 2em;">
                <h5 style="color: black; ">5,000 Verifications</h5>
                <div class="shape"></div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 2em;">
            @if(auth()->check())
                <form action="{{ route('create.Order') }}" method="POST">
                @csrf
                    <input type="hidden" name="input_value" id="input_value"  value="9">
                    <button  type="submit" class="btn btn-common" style="font-weight:bold" >$9</button>
                </form>
            @else
            <button class="btn btn-common" style="font-weight:bold" >$9</button>
            @endif
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 2em;">
                <button class="btn-comparision">$40</button>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 2em;">
                <button class="btn-comparision">$45</button>
            </div>
        </div>
        <div class="row wow fadeInDown" data-wow-delay="1.6s">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
                <h5 style="color: black;">10,000 Verifications</h5>
                <div class="shape"></div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            @if(auth()->check())
                <form action="{{ route('create.Order') }}" method="POST">
                @csrf
                    <input type="hidden" name="input_value" id="input_value"  value="14">
                    <button  type="submit" class="btn btn-common" style="font-weight:bold" >$14</button>
                </form>
            @else
            <button class="btn btn-common" style="font-weight:bold" >$14</button>
            @endif
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$50</button>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$80</button>
            </div>
        </div>
        <div class="row wow fadeInDown" data-wow-delay="1.8s">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
                <h5 style="color: black;">25,000 Verifications</h5>
                <div class="shape"></div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            @if(auth()->check())
                <form action="{{ route('create.Order') }}" method="POST">
                @csrf
                    <input type="hidden" name="input_value" id="input_value"  value="28">
                    <button  type="submit" class="btn btn-common" style="font-weight:bold" >$28</button>
                </form>
            @else
            <button class="btn btn-common" style="font-weight:bold" >$28</button>
            @endif
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$125</button>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$190</button>
            </div>
        </div>
        <div class="row wow fadeInDown" data-wow-delay="2.0s">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
                <h5 style="color: black;">50,000 Verifications</h5>
                <div class="shape"></div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            @if(auth()->check())
                <form action="{{ route('create.Order') }}" method="POST">
                @csrf
                    <input type="hidden" name="input_value" id="input_value"  value="45">
                    <button  type="submit" class="btn btn-common" style="font-weight:bold" >$45</button>
                </form>
            @else
            <button class="btn btn-common" style="font-weight:bold" >$45</button>
            @endif
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$250</button>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$375</button>
            </div>
        </div>
        <div class="row wow fadeInDown" data-wow-delay="2.2s">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
                <h5 style="color: black;">100K Verifications</h5>
                <div class="shape"></div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            @if(auth()->check())
                <form action="{{ route('create.Order') }}" method="POST">
                @csrf
                    <input type="hidden" name="input_value" id="input_value"  value="75">
                    <button  type="submit" class="btn btn-common" style="font-weight:bold" >$75</button>
                </form>
            @else
            <button class="btn btn-common" style="font-weight:bold" >$75</button>
            @endif
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$400</button>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$425</button>
            </div>
        </div>
        <div class="row wow fadeInDown" data-wow-delay="2.4s">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
                <h5 style="color: black;">200K Verifications</h5>
                <div class="shape"></div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
            @if(auth()->check())
                <form action="{{ route('create.Order') }}" method="POST">
                @csrf
                    <input type="hidden" name="input_value" id="input_value"  value="125">
                    <button  type="submit" class="btn btn-common" style="font-weight:bold" >$125</button>
                </form>
            @else
            <button class="btn btn-common" style="font-weight:bold" >$125</button>
            @endif
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" style="margin-top: 1em;">
                <button class="btn-comparision">$800</button>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6" style="margin-top: 1em;">
                <button class="btn-comparision">$850</button>
            </div>
        </div>
        <div class="row wow fadeInDown" data-wow-delay="2.6s">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
                <h5 style="color: black;">500K Verifications</h5>
                <div class="shape"></div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            @if(auth()->check())
                <form action="{{ route('create.Order') }}" method="POST">
                @csrf
                    <input type="hidden" name="input_value" id="input_value"  value="250">
                    <button  type="submit" class="btn btn-common" style="font-weight:bold" >$250</button>
                </form>
            @else
            <button class="btn btn-common" style="font-weight:bold" >$250</button>
            @endif
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$1500</button>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$1800</button>
            </div>
        </div>
        <div class="row wow fadeInDown" data-wow-delay="2.6s">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
                <h5 style="color: black;">1M Verifications</h5>
                <div class="shape"></div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            @if(auth()->check())
                <form action="{{ route('create.Order') }}" method="POST">
                @csrf
                    <input type="hidden" name="input_value" id="input_value"  value="450">
                    <button  type="submit" class="btn btn-common" style="font-weight:bold" >$450</button>
                </form>
            @else
            <button class="btn btn-common" style="font-weight:bold" >$450</button>
            @endif
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$3000</button>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
                <button class="btn-comparision">$2750</button>
            </div>
        </div> -->

        <table class="pricing-table wow fadeInDown" data-wow-delay="1.2s">
            <thead>
                <tr class="wow fadeInDown" data-wow-delay="1.4s">
                    <th class="verification credit">Verifications Credits</th>
                    <th class="price starter">Bouncee</th>
                    <th class="price basic">NeverBounce</th>
                    <th class="price standard">ZeroBounce</th>
                </tr>
            </thead>
            <tbody>
                <tr class="wow fadeInDown" data-wow-delay="1.5s">
                    <td class="verification credit">2,000 Verifications</td>
                    <td class="price starter">$5.00</td>
                    <td class="price basic">$16.00</td>
                    <td class="price standard">$20.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.6s">
                    <td class="verification credit">5,000 Verifications</td>
                    <td class="price starter">$9.00</td>
                    <td class="price basic">$40.00</td>
                    <td class="price standard">$45.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.7s">
                    <td class="verification credit">10,000 Verifications</td>
                    <td class="price starter">$14.00</td>
                    <td class="price basic">$50.00</td>
                    <td class="price standard">$80.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.8s">
                    <td class="verification credit">25,000 Verifications</td>
                    <td class="price starter">$28.00</td>
                    <td class="price basic">$125.00</td>
                    <td class="price standard">$190.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.9s">
                    <td class="verification credit">50,000 Verifications</td>
                    <td class="price starter">$45.00</td>
                    <td class="price basic">$250.00</td>
                    <td class="price standard">$375.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.10s">
                    <td class="verification credit">100K Verifications</td>
                    <td class="price starter">$75.00</td>
                    <td class="price basic">$400.00</td>
                    <td class="price standard">$425.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.11s">
                    <td class="verification credit">200K Verifications</td>
                    <td class="price starter">$125.00</td>
                    <td class="price basic">$800.00</td>
                    <td class="price standard">$850.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.12s">
                    <td class="verification credit">500K Verifications</td>
                    <td class="price starter">$250.00</td>
                    <td class="price basic">$1,500.00</td>
                    <td class="price standard">$1,800.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.13s">
                    <td class="verification credit">1M Verifications</td>
                    <td class="price starter">$450.00</td>
                    <td class="price basic">$3,000.00</td>
                    <td class="price standard">$2,750.00</td>
                </tr>
            </tbody>
        </table>

        <div class="section-header text-center">
            <!-- <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Plans & Pricing</h2> -->
            <!-- <h6 class="pricing-sub-header wow fadeInDown" data-wow-delay="0.4s">Try first, decide later, No credit card
                required!</h6> -->
            <div class="header-button" style="margin-top:2rem;">
                <a rel="nofollow" href="/signup" class="btn btn-home-common">Sign up now and get 100 FREE Credits</a>
            </div>
            <p class="checkbox-text">
                <img decoding="async" src="assets/checkmark.png" width="15px" height="15px"> No monthly payment, no upfront fee, credits never expire. <br> <img class="checkbox-text" decoding="async" src="assets/checkmark.png" width="15px" height="15px"> All prices include taxes and fees.
            </p>
        </div>
    </div>

</section> --}}





@php

$plans = [
        [
            'name' => 'Basic Limited Plan',
            'price' => '',
            'color' => 'text-white',
            'transform' => '',
            'header'=> 'background:#0E866D;font-weight:bold',
            'priceCss'=>"display:none;", // hide single price
            'features' => [
                'Limited: $5 → 2,000 credits',
                'Limited: $10 → 10,000 credits',
                'Limited: $20 → 25,000 credits',
                'Limited: $50 → 100,000 credits',
                'Limited: $100 → 250,000 credits',
               
            ]
        ],
        [
            'name' => 'Unlimited Plan',
            'price' => '',
            'color' => 'text-white',
            'transform' =>'transform',
            'header'=> 'background:#3F51B5;font-weight:bold',
            'priceCss'=>"display:none;",
            'features' => [
                'Unlimited: $19 → 7 Days',
                'Unlimited: $49 → 1 Month',
                'Unlimited: $129 → 3 Months',
                'Unlimited: $249 → 6 Months',
            ]
        ],
        [
            'name' => 'Pro Limited Plan',
            'price' => '',
            'color' => 'text-white',
            'transform'=>'',
            'header'=> 'background:#D6721D;font-weight:bold',
            'priceCss'=>"display:none;",
            'features' => [
                'Limited: $200 → 600,000 credits',
                'Limited: $400 → 1.5M credits',
                'Limited: $800 → 5M credits',
                'Limited: $1500 → 10M credits',
                'Limited: $3000 → 25M credits',
               
            ]
        ]



      
    ];

@endphp

<style>
.price-custom-section .custom-card  {
    background-color: #fff;
    color: #333;
    border-radius: 12px; /* Smooth rounded corners */
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2) !important; /* Shadow effect */
    overflow: visible; /* Ensure rounded corners are visible */
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out; /* Smooth effect */
}


.price-custom-section .custom-card .card-header{
    background: #0c7743; /* Adjust color as needed */
    color: white;
    font-weight: bold;
    padding: 23px;
    font-size: 18px;
    position: relative;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.price-custom-section .card-header::after {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 100%;
    height: 10px;
    background: white;
    mask-image: url('https://upload.wikimedia.org/wikipedia/commons/6/6e/Wave_crest_pattern.svg');
    mask-size: 30px 10px;
    mask-repeat: repeat-x;
}


/* .custom-card:hover {
    transform: scale(1.05);
    box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
} */

.price-custom-section .transform{
    transform: scale(1.12);
    box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
}

.price-custom-section .features {
    list-style: none;
    padding: 0;
    margin: 15px 0;
}

.price-custom-section .features li {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    font-size: 14px;
    /* font-weight: bold; */
    background: #f2f2f2;
    border-radius: 8px;
    /* margin: 18px 0; */
} 

.price-custom-section .features li:nth-child(odd){
    background: #f2f2f2; 
}
.price-custom-section .features li:nth-child(even){
    background: transparent; 
}

.price-custom-section .features .included::before {
    content: "✔";
    color: green;
    font-size: 18px;
    font-weight: bold;
    padding-right: 1.9em;
}
.price-custom-section .included {
    color: #6c757d;
}
.price-custom-section .custom-margin{
    margin-block: 6em;

}
 
@media(max-width:576px){
    .price-custom-section .transform{
        transform: none;
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
    }
    .price-custom-section .padding-bottom{
        padding-block: 2em;
    }
    .price-custom-section .custom-margin{
    margin-block: 3em;

}
}
@media(min-width:768px){

}
@media(min-width:992px){

}
@media(min-width:1200px){

}

</style>

<section id="pricing" class="section-padding">
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

                                <ul class="features">
                                    @foreach($plan['features'] as $feature) 
                                        <li class="included">{{ $feature }}</li>
                                    @endforeach 
                                </ul>
                                <a href='/signup' class="select-btn btn " style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);{{$plan['header']}}">Start Free</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="section-header text-center"> 
                {{-- <div class="header-button" style="margin-top:2rem;">
                    <a rel="nofollow" href="/signup" class="btn btn-home-common">Sign up now and get 100 FREE Credits</a>
                </div> --}}
                <p class="checkbox-text">
                    <img decoding="async" src="assets/checkmark.png" width="15px" height="15px"> No monthly payment, no upfront fee, credits never expire. <br>
                </p>
            </div>
        </div>
    </div>
</section>