@extends('layout.main')


@php
    $headerData = array();
    $headerData['whichPageRequest'] ='pricing';
@endphp

@section('main-section')
    @push('title')
        <title>Plans | bouncee</title>
    @endpush


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
    gap: 10px;
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

                                <ul class="features">
                                    @foreach($plan['features'] as $feature) 
                                        <li class="included">{{ $feature }}</li>
                                    @endforeach 
                                </ul>
                                <a href='/signup' class="select-btn btn " style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);{{$plan['header']}}">Start Now</a>
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


@endsection
