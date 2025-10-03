@extends('layout.main')
 
@section('main-section')
    @push('styles')
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
    @endpush
   <!-- <div class="flex"> -->
   <x-auth-pricing /> 
   <!-- </div> -->
   @include('support')
@endsection