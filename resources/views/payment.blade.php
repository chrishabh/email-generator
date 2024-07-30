<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <form action="/handle-payment" method="POST">
        @csrf
        <script
            src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="{{ config('razorpay.key') }}"
            data-amount="10000" 
            data-currency="INR"
            data-order_id="order_OeZYKcXCjgxCtp"
            data-buttontext="Pay with Razorpay"
            data-name="Your Company Name"
            data-description="Test Transaction"
            data-prefill.name="Gaurav Kumar"
            data-prefill.email="gaurav.kumar@example.com"
            data-theme.color="#F37254"
        ></script>
    </form>
</body>
</html>
