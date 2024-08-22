@extends('layout.main')

@section('main-section')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('verify/bulk-upload/css/style.css') }}">
        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
        <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>


        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet">
    


        
        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    
  <script src="{{ asset('verify/bulk-upload/js/script.js') }}" type="text/javascript"></script>
    @endpush
    <section class="bulk-upload" id="upload--bulk">
        <div class="container1">
            <div class="row flex-row-reverse">
                <div class="col-md-8  layout-sidebar--content">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alertBox"> 
                        <span id="alertContent"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="form-search">
                        <i class="fa-thin fa-magnifying-glass"></i>
                        <input type="email" placeholder="Search your completed list" id="filenameInput" autocomplete="off">
                        <button class="btn-submit">Search</button>
                    </div>
                    <div class="upload-file--list">

                        @if (isset($fileData) && !empty($fileData))
                            @foreach ($fileData as $key=>$value )
                                @if ($value['verificationStatus']=='verified')
                                    <div class="info-line flex" id="list_{{$value['fileId']}}" data-attribute="{{$value['fileId']}}">
                                        <div class="info-line--left col-md-5">
                                            <div class="img-cont">
                                                <img src="{{ asset('assets/doc.svg') }}" alt="">
                                            </div>
                                            <div class="title-wrap">
                                                <div class="title"><a href="javascript:void(0)"> {{ $value['fileName']}}</a></div>
                                                <div class="meta"><span class="txt-green"> {{ $value['total']}} Emails </span>- {{$value['created_at']}}</div>
                                            </div>
                                        </div>
                                        <div class="info-line--right row col-md-6 col-offset-1 align-items-center ">
                                            <div class="col-md-4 stat-col">
                                                Valid Emails
                                                <div class="text-green val">{{$value['totalValidEmail']}}</div>
                                            </div>
                                            <div class="col-md-5 stat-col">
                                                Invalid Emails
                                                <div class="text-red val">{{$value['totalInvalidEmail']}}</div>
 
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                                <i class="fa-solid fa-download download-icon" onclick="downloadCsvFile(event,{{$value['fileId']}})"></i>
                                            </div>
            
            
                                        </div>
                                    </div>    
                                @endif
                                <div class="info-line flex" id="list_{{$value['fileId']}}" data-attribute="{{$value['fileId']}}">
                                    <div class="info-line--left col-md-5">
                                        <div class="img-cont">
                                            <img src="{{ asset('assets/doc.svg') }}" alt="">
                                        </div>
                                        <div class="title-wrap">
                                            <div class="title"><a href="javascript:void(0)"> {{ $value['fileName']}}</a></div>
                                            <div class="meta"><span class="txt-green"> {{ $value['total']}} Emails </span>- {{$value['created_at']}}</div>
                                        </div>
                                    </div>
                                    <div class="info-line--right row col-md-6 col-offset-1 align-items-center ">
                                        <div class="col-md-5">
                                            <span class="time-tag"><span class="icon-clock"></span>few minutes</span>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="neumorphic-progress-circle">
                                                <div class="circle">
                                                    <div class="mask full" id="progress-mask-full">
                                                        <div class="fill"></div>
                                                    </div>
                                                    <div class="mask half" id="progress-mask-half">
                                                        <div class="fill"></div>
                                                    </div>
                                                    <div class="inside-circle">
                                                        <span class="percentage" id="percentage-text">0%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <button class="btn btn-primary mt-4" onclick="updateProgress(75)">Set Progress to 75%</button> --}}
                                        </div>
                                        <div class="col-md-5">
                                            <a href="" class="btn orange transparent pen">On Progress</a>
                                        </div>
        
        
                                    </div>
                                </div>
                        @endforeach
                        @endif
                         
                         
                    </div>
                </div>
                <div class="col-md-4 layout-sidebar--left">
                    <br>
                    <div class="curent--aside">
                        <div class="upload-aside--widget">
                            <h4 class="widget-title">UPLOAD Your file ( csv)</h4>
                            <form action="javascript:void(0)" class="form-upload">
                                {{-- <div class="filepond--root uploader filepond--hopper">
                                    <input class="filepond--browser" type="file" id="filepond--browser-kxvrqlbna"
                                        aria-controls="filepond--assistant-kxvrqlbna"
                                        aria-labelledby="filepond--drop-label-kxvrqlbna" name="filepond">
                                    <div class="filepond--drop-label">
                                        <label for="filepond--browser-kxvrqlbna" class="filepond--drop-label-text"
                                            id="filepond--drop-label-kxvrqlbna" aria-hidden="true">Drag &amp; Drop your
                                            files or
                                            <span class="filepond--label-action" tabindex="0">Browse</span>
                                        </label>
                                    </div>
                                </div> --}}

                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <input type="file" class="my-pond" name="filepond"/>
                                <a href="javascript:void(0)" class="import-btn btn" id="uploadbtn">Import list</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
