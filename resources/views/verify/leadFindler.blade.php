@extends('layout.main')
 
@section('main-section')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('verify/single/css/style.css') }}">     
        <link rel="shortcut icon" href="assets/bouncee-logo.png" type="image/png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> 
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
        <script src="{{ asset('verify/single/script.js') }}" type="text/javascript"></script> 
          
    @endpush
    
    <section class="single-area" id="single--verification">
        <div id="leadFinder">
            <div class="container1">
                <div class="first-top-lead">
                    <div class="single-card">
                        <div class="top-section-lead">
                            <div class="row mx-0">
                                <div class="col-md-2 col-sm-4">
                                    <i class="fa-solid fa-user-plus"></i>
                                </div>
                                <div class="col-md-10 col-sm-8">
                                    <h2 class="top-head">Find Someone's Email Address</h2>
                                    <div>Find someone's email address by validating possible email addresses created by the permutation of first name, last name, and company address.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mx-0"> 
                            <div class="col-md-12 col-sm-12"> 
                                <form class="lead-finder-form" id="lead-form">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            @csrf
                                            <label for="inputEmail4">First Name</label>
                                            {{-- <input type="email" class="form-control" id="inputEmail4" placeholder="Email">  --}}
                                            <input type="text" class="box-shadow form-control mt-3 {{$errors->has('first_name') ? 'validation-block-error':''}}" placeholder="Enter first name" name="first_name" value={{old('first_name')}} >
                                            <span class="{{ $errors->has('first_name') ? 'validation-span-error':''}}" id="fNameError">
                                                @error('first_name')
                                                    {{ $message }}  
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Last Name</label>
                                            <input type="text" class=" box-shadow form-control mt-3  {{$errors->has('last_name') ? 'validation-block-error':''}}" placeholder="Enter last name" name="last_name" value="{{old('last_name')}}" >
                                            <span class="{{ $errors->has('last_name') ? 'validation-span-error':''}}" id="lNameError">
                                                @error('last_name')
                                                    {{ $message }}  
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Domain Address</label>
                                            <input type="text" class="box-shadow form-control mt-3  {{$errors->has('domain') ? 'validation-block-error':''}}" placeholder="Enter domain name" name="domain"  value="{{old('domain')}}" >
                                            <span class="{{ $errors->has('domain') ? 'validation-span-error':''}}" id="domainError">
                                                @error('domain')
                                                    {{ $message }}  
                                                @enderror
                                            </span>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                      <div class="form-check">
                                        <input class="form-check-input mt-0" type="checkbox" id="gridCheck">
                                        <label class="form-check-label" for="gridCheck"> Pause the validation process when the first valid email address is found.
                                        </label>
                                      </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Sign in</button>
                                </form> 
                            </div>
                        </div>
                    </div>
                </div>
               
                 
            </div> 
        </div>
            
    </section> 
    <section class="mt-25"></section>
 
@endsection




            {{-- <div class="row mx-0">
                <div class="col-md-6 col-sm-12 grid-col">
                    <div class="single-card">
                        <div class="card-title card-row">
                            <i class="fa-light fa-envelope"></i>
                            <div class="title-content">
                                <h2 class="title-head">Single Check</h2>
                                <p>Perform single email verification with deep analysis result.</p>
                            </div>
                        </div>
                        <h3 class="single-card-head">Enter Details to get the correct Email</h3>
                        <form  method="POST" accept-charset="utf-8" class="form-inner" id='signinForm' action="{{ route('single') }}">
                            @csrf
                            <input type="text" class="emailCheck hover-border mt-25 {{$errors->has('first_name') ? 'validation-block-error':''}}" placeholder="Enter first name" name="first_name" value={{old('first_name')}} >
                            <span class="{{ $errors->has('first_name') ? 'validation-span-error':''}}" id="fNameError">
                                @error('first_name')
                                    {{ $message }}  
                                @enderror
                            </span>
                            <input type="text" class="emailCheck hover-border {{$errors->has('last_name') ? 'validation-block-error':''}}" placeholder="Enter last name" name="last_name" value="{{old('last_name')}}" >
                            <span class="{{ $errors->has('last_name') ? 'validation-span-error':''}}" id="lNameError">
                                @error('last_name')
                                    {{ $message }}  
                                @enderror
                            </span>
                            <input type="text" class="emailCheck hover-border {{$errors->has('domain') ? 'validation-block-error':''}}" placeholder="Enter domain name" name="domain"  value="{{old('domain')}}" >
                            <span class="{{ $errors->has('domain') ? 'validation-span-error':''}}" id="domainError">
                                @error('domain')
                                    {{ $message }}  
                                @enderror
                            </span>
                            {{-- <span class="Buttonloader"></span> --}}
                            {{-- <button class="btn submit-verification-btn"  id="CheckButon">Check  </button>
                        </form>
                    </div>  
                </div>
                <div class="col-md-6 col-sm-12 grid-col">
                    <div class="single-card">
                        <h3 class="right-card-head">Analysis Results</h3>
                        <div class="outer-layer">
                            <div class="inner-layer">
                                @if (session('validEmails'))
                                    @foreach (session('validEmails') as $email)
                                        <div class="correct-email">
                                            <div class="col-md-8"> 
                                                <h2> <i class="fa-light fa-check"></i>  {{$email}}</h2>
                                            </div>
                                            <div class="col-md-2 col-offset-2"> 
                                                <div class="credit-info--tag bg-success-valid">Valid </div>
                                            </div> 
                                        </div>  
                                    @endforeach
                                    @else
                                    <div class="no-content-parent">
                                        <div class="analLoader" id="AnalLoader"> </div> 
                                        <img class="no-content" id="analImage" src="{{ asset('verify/single/image/1.png') }}" alt="no content" srcset="">
                                    </div> 
                                @endif
                            </div>
                             
                        </div>
                        
                    </div>  
                </div>

            </div>  --}}