@extends('layout.main')

@section('main-section')
    @push('title')
    <title> Lead Finder | Bouncee</title>
    @endpush
    @push('styles')
        <link rel="stylesheet" href="{{ asset('verify/single/css/style.css') }}">
        <link rel="shortcut icon" href="assets/bouncee-logo.png" type="image/png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('verify/single/script.js') }}" type="text/javascript"></script> 
    @endpush

    <section class="single-area lead-finder" id="single--verification">
        <div id="leadFinder">
            <div class="container1">
                <div class="first-top-lead">
                    <div class="single-card">
                        <div class="top-section-lead">
                            <div class="row mx-0">
                                <div class="col-md-1 col-sm-1">
                                    <i class="fa-solid fa-user-plus"></i>
                                </div>
                                <div class="col-md-11 col-sm-11">
                                    <h2 class="top-head">Find Someone's Email Address</h2>
                                    <div class="top-para">Find someone's email address by validating possible email
                                        addresses created by the permutation of first name, last name, and company address.
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mx-0">
                            <div class="col-md-12 col-sm-12">
                                <h2 class="lead-heading--requsted">Requested Information</h2>
                                <form class="lead-finder-form" id="lead-form" method="POST" action="{{ route('lead-finder') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            @csrf
                                            <label for="inputEmail4">First Name</label>
                                            {{-- <input type="email" class="form-control" id="inputEmail4" placeholder="Email">  --}}
                                            <input type="text"
                                                class="box-shadow form-control mt-3 mb-2 {{ $errors->has('first_name') ? 'validation-block-error' : '' }}"
                                                placeholder="Enter first name" name="first_name"
                                                value={{ old('first_name') }}>
                                            <span class="{{ $errors->has('first_name') ? 'validation-span-error' : '' }}"
                                                id="fNameError">
                                                @error('first_name')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Last Name</label>
                                            <input type="text"
                                                class=" box-shadow form-control mt-3 mb-2 {{ $errors->has('last_name') ? 'validation-block-error' : '' }}"
                                                placeholder="Enter last name" name="last_name"
                                                value="{{ old('last_name') }}">
                                            <span class="{{ $errors->has('last_name') ? 'validation-span-error' : '' }}"
                                                id="lNameError">
                                                @error('last_name')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputPassword4">Domain Address</label>
                                            <input type="text"
                                                class="box-shadow form-control mt-3 mb-2 {{ $errors->has('domain') ? 'validation-block-error' : '' }}"
                                                placeholder="Enter domain name" name="domain" value="{{ old('domain') }}">
                                            <span class="{{ $errors->has('domain') ? 'validation-span-error' : '' }}"
                                                id="domainError">
                                                @error('domain')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group checkbox--finder">
                                        <div class="form-check">
                                            <input type="hidden" name="stopValidationCheckbox" value="0" />
                                            <input class="form-check-input mt-0" type="checkbox" id="gridCheck" name="stopValidationCheckbox" value="1" 
                                            {{ old('stopValidationCheckbox') ? 'checked' : '' }} />
                                            <label class="form-check-label" for="gridCheck"> Pause the validation process
                                                when the first valid email address is found.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="border-bottom-line"></div>
                                    <button type="submit" class="btn btn-primary lead-finder-btn" id="CheckButon">Find Email
                                        Address</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @php               
                    $data   = session('validEmails');
                    $sno    = 1;
                    if(!empty($data)){
                        $data = $data['lead_finder_p_c_email_logs'];
                    }
                @endphp
                @if(!empty($data))
                    <div class="second-lead-card single-card mt-4">
                        <div class="row mx-0">
                            <div class="col-md-12 col-sm-12">
                                <h2 class="lead-heading--requsted font-weight-bold">Result</h2>
                                <div class="table-wrapper">
                                    <table class="table table-hover box-shadow-custom">
                                        <thead>
                                            <tr class="bg-grad">
                                                <th scope="col">ROW</th>
                                                <th scope="col">EMAIL ADDRESS</th>
                                                <th scope="col">STATUS</th>
                                                <th scope="col">ACTIONS</th>
                                            </tr>
                                        </thead>
                                        <tbody class=" table-bordered scrollable-tbody">
                                            @foreach($data as $k=>$value)
                                                <tr>
                                                    <th scope="row">{{$sno}}.</th>
                                                    {{-- <td>  <input type="text"  id="copiedEmail" value="{{$value['email']}}" readonly></td> --}}
                                                    <td id="copiedEmail">{{$value['email']}}</td>
                                                    <td>
                                                        @if($value['status']=='invalid')
                                                            <div class="badge badge-danger lead-badges">
                                                                <img class="no-content"
                                                                    src="{{ asset('verify/single/image/invalid.svg') }}" alt="no content"
                                                                    srcset="">
                                                                <span class="font-weight-normal text-uppercase">{{$value['status']}}</span>
                                                            </div>
                                                        @elseif ($value['status']=='valid')
                                                            <div class="badge badge-success lead-badges">
                                                                <img class="no-content"
                                                                    src="{{ asset('verify/single/image/valid.svg') }}" alt="no content"
                                                                    srcset="">
                                                                <span class="font-weight-normal text-uppercase">{{$value['status']}}</span>
                                                            </div>
                                                        @elseif ($value['status']=='aborted')
                                                            <div class="badge badge-dark lead-badges">
                                                                <img class="no-content"
                                                                    src="{{ asset('verify/single/image/aborted.svg') }}" alt="no content"
                                                                    srcset="">
                                                                <span class="font-weight-normal text-uppercase">{{$value['status']}}</span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td><button type="button" class="btn  copy-btn" id="copyButton" onclick="copyContent('{{$value['email']}}')">COPY</button></td>
                                                </tr>
                                                @php
                                                    $sno++;  
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </section>

    <div id="loaderLeadFinder" class="BallloaderLeadFinder">
        <div class="ball"></div>
        <div class="ball"></div>
        <div class="ball"></div>
      </div>
    <section class="mt-25"></section>
@endsection

 