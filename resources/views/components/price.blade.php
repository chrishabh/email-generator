<section id="pricing" class="section-padding">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Plans & Pricing</h2>
            <h6 class="wow fadeInDown" data-wow-delay="0.4s" style="color:black">Try first, decide later, No credit card
                required!</h6>
            <div class="shape wow fadeInDown" data-wow-delay="0.5s"></div>
        </div>
        <div class="row wow fadeInDown" data-wow-delay="1.2s">
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
        </div>
    </div>
</section>
