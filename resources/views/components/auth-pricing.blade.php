<section id="pricing" class="section-padding">
<link rel="stylesheet" href="css/auth-pricing.css">

    <style>

        body {
            font-family: Arial, sans-serif;
        }

        .pricing-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pricing-table th,
        .pricing-table td {
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

        .starter-heading {
            background-color: #f79b00;
        }

        .starter {
            background-color: #f79b00;
            color: #0000FF;
            text-decoration: underline;
            cursor: pointer;
        }

        .basic {
            background-color: #d64d26;
            color: white;
        }

        .standard {
            background-color: #00a9b5;
            color: white;
        }

        .premium {
            background-color: #76923c;
            color: white;
        }

        .credit {
            background-color: #76923c;
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
        }



        
    </style>
    <div class="container ">
        <div class="content-box">


            <div class="row ">
                <div class="col-sm-12">
                    <div class="element-wrapper">
                    <div class="section-header text-center">
            <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Plans & Pricing</h2>
            <h6 class="auth-pricing-sub-header wow fadeInDown" data-wow-delay="0.4s" style="margin-top: 0.2em;">Try first, decide later, No credit card
                required!</h6>
            <div class="shape wow fadeInDown" data-wow-delay="0.5s"></div>
        </div>
                        <div class="element-box-tp " style="cursor: default;">
                            <div class="table-responsive">
                                <table class="table table-padded">
                                    <thead>
                                        <tr>
                                            <th>
                                                Package
                                            </th>
                                            <th>
                                                Credits
                                            </th>
                                            <th>
                                                Price per Verification
                                            </th>
                                            <th>
                                                Price (USD $)
                                            </th>
                                            <th>
                                                Buy
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="nowrap">
                                                <span>1</span>
                                            </td>
                                            <td>
                                                <span class="bold">2,000</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.0025</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$5</span>
                                            </td>
                                            <td class="bolder nowrap"> 
                                                <form action="{{ route('create.Order') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="input_value" id="input_value"  value="5">
                                                    <button  type="submit" class="badge badge-primary p-2" style="font-weight:bold" >BUY</button>
                                                </form></td>
                                        </tr>
                                        <tr>
                                            <td class="nowrap">
                                                <span>2</span>
                                            </td>
                                            <td>
                                                <span class="bold">5,000</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.0018</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$9</span>
                                            </td>
                                            <td class="bolder nowrap"> <form action="{{ route('create.Order') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="input_value" id="input_value"  value="9">
                                                    <button  type="submit" class="badge badge-primary p-2" style="font-weight:bold" >BUY</button>
                                                </form></td>
                                        </tr>
                                        <tr>
                                            <td class="nowrap">
                                                <span>3</span>
                                            </td>
                                            <td>
                                                <span class="bold">10,000</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.0014</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$14</span>
                                            </td>
                                            <td class="bolder nowrap">
                                            <form action="{{ route('create.Order') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="input_value" id="input_value"  value="14">
                                                    <button  type="submit" class="badge badge-primary p-2" style="font-weight:bold" >BUY</button>
                                                </form></td>
                                        </tr>
                                        <tr>
                                            <td class="nowrap">
                                                <span>4</span>
                                            </td>
                                            <td>
                                                <span class="bold">25,000</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.00112</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$28</span>
                                            </td>
                                            <td class="bolder nowrap"> <form action="{{ route('create.Order') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="input_value" id="input_value"  value="28">
                                                    <button  type="submit" class="badge badge-primary p-2" style="font-weight:bold" >BUY</button>
                                                </form></td>
                                        </tr>
                                        <tr>
                                            <td class="nowrap">
                                                <span>5</span>
                                            </td>
                                            <td>
                                                <span class="bold">50,000</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.009</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$45</span>
                                            </td>
                                            <td class="bolder nowrap">
                                            <form action="{{ route('create.Order') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="input_value" id="input_value"  value="45">
                                                    <button  type="submit" class="badge badge-primary p-2" style="font-weight:bold" >BUY</button>
                                                </form></td>
                                        </tr>
                                        <tr>
                                            <td class="nowrap">
                                                <span>6</span>
                                            </td>
                                            <td>
                                                <span class="bold">100K</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.00075</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$75</span>
                                            </td>
                                            <td class="bolder nowrap">
                                            <form action="{{ route('create.Order') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="input_value" id="input_value"  value="75">
                                                    <button  type="submit" class="badge badge-primary p-2" style="font-weight:bold" >BUY</button>
                                                </form></td>
                                        </tr>
                                        <tr>
                                            <td class="nowrap">
                                                <span>7</span>
                                            </td>
                                            <td>
                                                <span class="bold">200K</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.000625</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$125</span>
                                            </td>
                                            <td class="bolder nowrap">
                                            <form action="{{ route('create.Order') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="input_value" id="input_value"  value="125">
                                                    <button  type="submit" class="badge badge-primary p-2" style="font-weight:bold" >BUY</button>
                                                </form></td>
                                        </tr>
                                        <tr>
                                            <td class=" nowrap">
                                                    <span>8</span></td>
                                            <td>
                                                <span class="bold">500K</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.0005</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$250</span>
                                            </td>
                                            <td class="bolder nowrap">
                                            <form action="{{ route('create.Order') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="input_value" id="input_value"  value="250">
                                                    <button  type="submit" class="badge badge-primary p-2" style="font-weight:bold" >BUY</button>
                                                </form></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>9</span>
                                            </td>
                                            <td>
                                                <span class="bold">1M</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.00045</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$450</span>
                                            </td>
                                            <td class="bolder nowrap">
                                            <form action="{{ route('create.Order') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="input_value" id="input_value"  value="450">
                                                    <button  type="submit" class="badge badge-primary p-2" style="font-weight:bold" >BUY</button>
                                                </form></td>
                                        </tr>
                                        <!-- <tr class="hasAPI">
                                            <td class="nowrap">
                                                <span>9</span>
                                            </td>
                                            <td>
                                                <span class="bold">2,000,000</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.0004</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$800</span>
                                            </td>
                                            <td class="bolder nowrap"><a class="badge badge-primary p-2" href="#buy" onclick="Package('<i class=\'osiconing os-icon os-icon-delivery-box-2\'></i>2,000,000 Verifications - $800');YekPay('9');CryptoPay('9');PayPro('9');PerfectMoney('9');">BUY</a></td>
                                        </tr>
                                        <tr class="hasAPI">
                                            <td class="nowrap">
                                                <span>10</span>
                                            </td>
                                            <td>
                                                <span class="bold">5,000,000</span><span class="smaller lighter">Verifications</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ 0.0003</span><span class="smaller lighter">Per Verification</span>
                                            </td>
                                            <td class="bolder nowrap">
                                                <span class="text-success">$1,500</span>
                                            </td>
                                            <td class="bolder nowrap"><a class="badge badge-primary p-2" href="#buy" onclick="Package('<i class=\'osiconing os-icon os-icon-delivery-box-2\'></i>5,000,000 Verifications - $1500');YekPay('10');CryptoPay('10');PayPro('10');PerfectMoney('10');">BUY</a></td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>