@extends('layout.main')

@section('main-section')
@push('styles')

@endpush
<!-- <div class="flex"> -->
<section id="payment-history">
    <link rel="stylesheet" href="css/payment-history.css">

    <style>
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

        .no-records {
            display: block;
            /* Hidden initially */
            text-align: center;
            margin-top: 10px;
        }
    </style>
    <div class="container ">
        <div class="content-box">


            <div class="row ">
                <div class="col-sm-12">
                    <div class="element-wrapper">
                        <div class="section-header text-center">
                            <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Payment History</h2>

                            <div class="shape wow fadeInDown" data-wow-delay="0.5s"></div>
                        </div>
                        <div class="element-box-tp " style="cursor: default;">
                            <div class="table-responsive">
                                <table class="table table-padded">
                                    <thead>
                                        <tr>
                                            <th>
                                                Order Number
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Order Status
                                            </th>
                                            <th>
                                                Price
                                            </th>
                                            <th>
                                                Created At
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php

                                        if(!empty($headerData)){
                                        $data = $headerData['paymentData'];
                                        }
                                        @endphp
                                        @foreach($data as $k=>$value)
                                        <tr>
                                            <td class="nowrap">
                                                <span>{{$value['order_id']}}</span>
                                            </td>
                                            <td>
                                                <span class="bold">{{$value['prefill_name']}}</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span class="text-success">{{$value['status']}}</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>$ {{$value['amount']}}</span>
                                            </td>
                                            <td class="cell-with-media">
                                                <span>{{$value['created_at']}}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                @if(empty($data))
                                <div class="no-records" id="noRecords">
                                    <img src="{{ asset('assets/No-record-found.gif') }}" alt="no content" srcset="">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- </div> -->

@endsection