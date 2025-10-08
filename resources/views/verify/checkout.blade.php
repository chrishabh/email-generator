@extends('layout.main')

@section('main-section')
<section class="bg-gradient-light min-vh-100 d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Checkout Card -->
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header text-center modern-header">
                        <h3 class="header-title">Confirm Your Plan</h3>
                        <p class="header-subtext">Fast, secure, and hassle-free checkout</p>
                    </div>
                    <!-- Body -->
                    <div class="card-body p-5 bg-white">

                        <!-- Plan Summary -->
                        <div class="mb-5">
                            <h5 class="fw-bold mb-3">Your Selected Plan</h5>
                            <div class="bg-light p-4 rounded shadow-sm border">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Plan Type:</span>
                                    <strong>{{ $plan_name }}</strong>
                                </div>
                                @if(!empty($credits))
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Credits:</span>
                                    <strong>{{ $credits }}</strong>
                                </div>
                                @endif
                                @if(!empty($duration))
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Duration:</span>
                                    <strong>{{ $duration }}</strong>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Base Price:</span>
                                    <strong>{{$plan_currency}}<span id="baseAmount">{{ number_format($price, 2) }}</span></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Form -->
                        <form method="POST" action="{{ url('/create-order') }}" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="price" value="{{ $price }}">
                            <input type="hidden" name="credits" value="{{ $credits ?? '' }}">
                            <input type="hidden" name="duration" value="{{ $duration ?? '' }}">
                            <input type="hidden" name="plan_name" value="{{ $plan_name }}">

                            <div class="mb-4">
                                <label class="form-label fw-semibold">GST Number (Optional)</label>
                                <input type="text" style="border-radius: 10px;" class="form-control form-control-lg rounded-pill border-primary" name="gst_number" placeholder="Enter GST number">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" >Promo Code (Optional)</label>
                                <div class="input-group">
                                    <input type="text" style="border-radius: 10px;" class="form-control form-control-lg rounded-start-pill border-primary" name="promo_code" placeholder="Enter promo code">
                                    <button type="button" id="applyPromo" style="margin-left: 10px;"class="btn btn-primary rounded-end-pill">Apply</button>
                                </div>
                            </div>

                            <!-- Price Summary -->
                            <div class="bg-light border rounded p-4 shadow-sm mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Base Amount:</span>
                                    <span>{{$currency}}<span id="displayBase">{{ number_format($base_price, 2) }}</span></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>GST ({{$gst}}%):</span>
                                    <span>{{$currency}}<span id="displayGST">{{ number_format($gst_amount, 2) }}</span></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discount:</span>
                                    <span class="text-success">-{{$currency}}<span id="displayDiscount">0.00</span></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold fs-5 text-primary">
                                    <span>Total:</span>
                                    <span>{{$currency}}<span id="displayTotal">{{ round($Total_amount) }}</span></span>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-gradient-primary btn-lg w-100 rounded-pill shadow">Proceed to Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const basePrice = parseFloat('{{ $price }}');
    const gstRate = parseFloat('{{ $gst }}') / 100;
    let discount = 0;

    const displayBase = document.getElementById('displayBase');
    const displayGST = document.getElementById('displayGST');
    const displayDiscount = document.getElementById('displayDiscount');
    const displayTotal = document.getElementById('displayTotal');

    document.getElementById('applyPromo').addEventListener('click', function() {
        const promoInput = document.querySelector('[name="promo_code"]');
        const promo = promoInput.value.trim().toUpperCase();

        const promos = { 'BOUNCEE10': 0.10 };

        if(promos[promo]) {
            discount = basePrice * promos[promo];
            alert(`Promo applied: ${promos[promo]*100}% discount`);
        } else if(promo !== '') {
            discount = 0;
            alert('Invalid promo code');
        } else {
            discount = 0;
        }

        const gst = (basePrice - discount) * gstRate;
        const total = (basePrice - discount + gst).toFixed(2);

        displayBase.textContent = basePrice.toFixed(2);
        displayGST.textContent = gst.toFixed(2);
        displayDiscount.textContent = discount.toFixed(2);
        displayTotal.textContent = total;
    });
});
</script>

<style>
.bg-gradient-light {
    background: linear-gradient(135deg, #f0f4ff 0%, #d9e4ff 100%);
}
.btn-gradient-primary {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    border: none;
    color: #fff;
}
.btn-gradient-primary:hover {
    opacity: 0.9;
}
.modern-header {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    color: #fff;
    border-bottom-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.header-title {
    font-size: 1.9rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
    background: linear-gradient(90deg, #fff 0%, #d0e8ff 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.header-subtext {
    font-size: 1rem;
    font-weight: 400;
    opacity: 0.85;
    letter-spacing: 0.2px;
    margin-bottom: 0;
}
</style>
@endsection
