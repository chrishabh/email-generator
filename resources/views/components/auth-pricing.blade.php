<section id="pricing" class="section-padding">
<style>
        body {
            font-family: Arial, sans-serif;
        }
        .pricing-table {
            width: 100%;
            border-collapse: collapse;
        }
        .pricing-table th, .pricing-table td {
            padding: 10px;
            text-align: center;
        }
        .pricing-table th {
            /* background-color: #333; */
            color: #fff;
        }
        .pricing-table td {
            border: 1px solid #ccc;
        }
        .starter-heading { background-color: #f79b00; }
        .starter { background-color: #f79b00; color: #0000FF; text-decoration: underline;  cursor: pointer;  }
        .basic { background-color: #d64d26; color: white; }
        .standard { background-color: #00a9b5; color: white; }
        .premium { background-color: #76923c; color: white; }
        .credit { background-color: #76923c; color: white; }
        .check { color: green; font-size: 20px; }
        .cross { color: red; font-size: 20px; }
        .price { font-size: 24px; font-weight: bold; }
        .verification { font-size: 24px; font-weight: bold; }
    </style>
<div class="container">
        <div class="section-header text-center">
            <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Plans & Pricing</h2>
            <h6 class="auth-pricing-sub-header wow fadeInDown" data-wow-delay="0.4s" style="margin-top: 0.2em;">Try first, decide later, No credit card
                required!</h6>
            <div class="shape wow fadeInDown" data-wow-delay="0.5s"></div>
        </div>
        <table class="pricing-table wow fadeInDown" data-wow-delay="1.2s">
            <thead>
                <tr class="wow fadeInDown" data-wow-delay="1.4s">
                    <th class="verification credit">Verifications Credits</th>
                    <th class="price starter-heading">bouncee</th>
                    <th class="price basic">NeverBounce</th>
                    <th class="price standard">Zero bounce</th>
                </tr>
            </thead>
            <tbody>
                <tr class="wow fadeInDown" data-wow-delay="1.5s">
                    <td class="verification credit">2,000 Verifications</td>
                    <td class="price starter" onclick="document.getElementById('form-5').submit();"> <form action="{{ route('create.Order') }}" method="POST" id="form-5">
                        @csrf
                        <input type="hidden" name="input_value" id="input_value" value="5">
                        $5.00
                    </form></td>
                    </form>
                    <td class="price basic">$10.00</td>
                    <td class="price standard">$14.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.6s">
                    <td class="verification credit">5,000 Verifications</td>
                    <td class="price starter" onclick="document.getElementById('form-9').submit();"> <form action="{{ route('create.Order') }}" method="POST" id="form-9">
                        @csrf
                        <input type="hidden" name="input_value" id="input_value" value="9">
                        $9.00
                    </form></td>
                    <td class="price basic">$40.00</td>
                    <td class="price standard">$45.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.7s">
                    <td class="verification credit">10,000 Verifications</td>
                    <td class="price starter" onclick="document.getElementById('form-14').submit();"> <form action="{{ route('create.Order') }}" method="POST" id="form-14">
                        @csrf
                        <input type="hidden" name="input_value" id="input_value" value="14">
                        $14.00
                    </form></td>
                    <td class="price basic">$50.00</td>
                    <td class="price standard">$80.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.8s">
                    <td class="verification credit">25,000 Verifications</td>
                    <td class="price starter" onclick="document.getElementById('form-28').submit();"> <form action="{{ route('create.Order') }}" method="POST" id="form-28">
                        @csrf
                        <input type="hidden" name="input_value" id="input_value" value="28">
                        $28.00
                    </form></td>
                    <td class="price basic">$125.00</td>
                    <td class="price standard">$190.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.9s">
                    <td class="verification credit">50,000 Verifications</td>
                    <td class="price starter" onclick="document.getElementById('form-45').submit();"> <form action="{{ route('create.Order') }}" method="POST" id="form-45">
                        @csrf
                        <input type="hidden" name="input_value" id="input_value" value="45">
                        $45.00
                    </form></td>
                    <td class="price basic">$250.00</td>
                    <td class="price standard">$375.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.10s">
                    <td class="verification credit">100K Verifications</td>
                    <td class="price starter" onclick="document.getElementById('form-75').submit();"> <form action="{{ route('create.Order') }}" method="POST" id="form-75">
                        @csrf
                        <input type="hidden" name="input_value" id="input_value" value="75">
                        $75.00
                    </form></td>
                    <td class="price basic">$400.00</td>
                    <td class="price standard">$425.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.11s">
                    <td class="verification credit">200K Verifications</td>
                    <td class="price starter" onclick="document.getElementById('form-125').submit();"> <form action="{{ route('create.Order') }}" method="POST" id="form-125">
                        @csrf
                        <input type="hidden" name="input_value" id="input_value" value="125">
                        $125.00
                    </form></td>
                    <td class="price basic">$800.00</td>
                    <td class="price standard">$850.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.12s">
                    <td class="verification credit">500K Verifications</td>
                    <td class="price starter" onclick="document.getElementById('form-250').submit();"> <form action="{{ route('create.Order') }}" method="POST" id="form-250">
                        @csrf
                        <input type="hidden" name="input_value" id="input_value" value="250">
                        $250.00
                    </form></td>
                    <td class="price basic">$1,500.00</td>
                    <td class="price standard">$1,800.00</td>
                </tr>
                <tr class="wow fadeInDown" data-wow-delay="1.13s">
                    <td class="verification credit">1M Verifications</td>
                    <td class="price starter" onclick="document.getElementById('form-450').submit();"> <form action="{{ route('create.Order') }}" method="POST" id="form-450">
                        @csrf
                        <input type="hidden" name="input_value" id="input_value" value="450">
                        $450.00
                    </form></td>
                    <td class="price basic">$3,000.00</td>
                    <td class="price standard">$2,750.00</td>
                </tr>
            </tbody>
        </table>

    </div>

</section>
