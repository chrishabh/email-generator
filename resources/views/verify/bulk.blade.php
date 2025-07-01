@extends('layout.main')

@section('main-section')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('verify/bulk-upload/css/style.css') }}">
        <link rel="shortcut icon" href="assets/bouncee-logo.png" type="image/png">
        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            

        <!-- Load FilePond Core -->
        {{-- <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script> --}}
        <script src="{{ asset('filepond/filepond.min.js') }}" type="text/javascript"></script>

        <!-- Load FilePond Plugins -->
        <script src="{{ asset('filepond/filepnd-image-preview.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('filepond/filepond-plugin-image-exif-orientation.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('filepond/filepond-plugin-file-validate-size.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('filepond/filepond-plugin-image-edit.min.js') }}" type="text/javascript"></script>



        {{-- <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script> --}}
        {{-- <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script> --}}
        {{-- <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script> --}}
        {{-- <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js"></script> --}}

        <!-- jQuery FilePond (only if needed) -->
        {{-- <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script> --}}
        <script src="{{ asset('filepond/filepond.jquery.js') }}" type="text/javascript"></script>

        <!-- FilePond Styles -->
        <link rel="stylesheet" href="{{ asset('filepond/css/filepond.css') }}">
        <link rel="stylesheet" href="{{ asset('filepond/css/filepond-plugin-image-preview.css') }}">
        {{-- <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"> --}}
        {{-- <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"> --}}
        {{-- <link href="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.css" rel="stylesheet"> --}}
        {{-- <link href="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.css" rel="stylesheet"> --}}
        {{-- <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet"> --}}


        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> 
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
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
                    @if (isset($fileData) && !empty($fileData))
                        <div class="form-search">
                            <i class="fa-thin fa-magnifying-glass"></i>
                                <meta name="search-csrf-token" content="{{ csrf_token() }}">
                                <input type="text" name="searchContent" placeholder="Search your completed list"  />
                                <img onclick="cancelFilter(this,event)" class="position-absolute cross-image" id="crossImage" src="{{asset('verify/bulk-upload/cross.svg')}}" alt="cancel"/>
                                <button class="btn-submit" id="searchButton">Search</button>
                        </div>
                    @endif
                    <div class="upload-file--list">

                        @if (isset($fileData) && !empty($fileData))
                            @php
                                $statusArray = array();
                            @endphp
                            @foreach ($fileData as $key=>$value ) 
                                @if ($value['verificationStatus']=='verified')
                                    <div class="info-line flex" id="list_{{$value['fileId']}}" data-attribute="{{$value['fileId']}}">
                                        <div class="info-line--left col-md-5">
                                            <div class="img-cont">
                                            @php
                                                $image='';
                                                if($value['is_tools_integerate_email']=='1'){
                                                    $image = $value['iconURL'];
                                                }else{
                                                    $image =  asset('assets/doc.svg');
                                                }
                                            @endphp
                                            <img src="{{ $image }}" alt="">
                                            </div>
                                            <div class="title-wrap">
                                                <div class="title"><a href="javascript:void(0)"> {{ $value['fileName']}}</a></div>
                                                <div class="meta"><span class="txt-green"> {{ $value['total']}} Emails </span>- {{$value['created_at']}}</div>
                                            </div>
                                        </div>
                                      <div class="info-line--right row col-md-5 align-items-center ">
                                            <div class="row mx-0">
                                                @php
                                                    $uniqueStatuses = collect($value['verifyStatusData'])->pluck('apiStatus')->unique();
                                                    $colClass = $uniqueStatuses->count() === 1 ? 'col-12' : 'col-md-6';
                                                @endphp
                                                @foreach ($value['verifyStatusData'] as $k=>$v)
                                                    @php
                                                        $statusOfVer = $v['apiStatus'];
                                                        array_push($statusArray,  $statusOfVer);
                                                        $color='';
                                                        if(strtolower($statusOfVer) =='deliverable') $color='#28A745';
                                                        else if(strtolower($statusOfVer) =='undeliverable') $color='#DC3545';
                                                        else if(strtolower($statusOfVer) =='unknown') $color='#FFC107';
                                                        else if(strtolower($statusOfVer) =='accept all') $color='#6C757D';
                                                    @endphp
                                                    <div class="{{$colClass}}  stat-col pb-3">
                                                        <span class="text text-capitalize">{{$v['apiStatus']}}</span> 
                                                        <div class=" val" style="color:{{$color}}">{{$v['total_count']}}</div>
                                                    </div>
                                                @endforeach 

                                            </div> 
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                            <i class="fa-solid fa-download download-icon" data-status="{{json_encode($statusArray)}}" data-valid="{{$value['isDownloadFileLocation']}}" onclick="downloadCsvFile(event,{{$value['fileId']}},{{$value['isDownloadFileLocation']}},{{$value['is_tools_integerate_email']}},'{{$value['toolName']}}',this)"></i>
                                        </div> 
                                    </div>    
                                @endif
                                @if ($value['verificationStatus']=='pending')
                                <div class="info-line flex" id="list_{{$value['fileId']}}" data-attribute="{{$value['fileId']}}">
                                    <div class="info-line--left col-md-5">
                                        <div class="img-cont">
                                            @php
                                                $image='';
                                                if($value['is_tools_integerate_email']=='1'){
                                                    $image = $value['iconURL'];
                                                }else{
                                                    $image =  asset('assets/doc.svg');
                                                }
                                            @endphp
                                            <img src="{{ $image }}" alt="">
                                        </div>
                                        <div class="title-wrap">
                                            <div class="title"><a href="javascript:void(0)"> {{ $value['fileName']}}</a></div>
                                            <div class="meta"><span class="txt-green"> {{ $value['total']}} Emails </span>- {{$value['created_at']}}</div>
                                        </div>
                                    </div>
                                    <div class="info-line--right row col-md-7 col-offset-1 align-items-center ">
                                        <div class="col-md-7">
                                            {{-- <span class="time-tag"><span class="icon-clock"></span>start to processing</span> --}}
                                            <div id="progress-card" class="progress-card">
                                                {{-- <h3>Verification Progress</h3> --}}
                                                <div class="progress-container">
                                                    <div id="progress-bar" class="progress-bar"></div>
                                                </div>
                                                <span id="progress-text">0 / {{ $value['total']}} emails verified</span>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-2">
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
                                            </div> --}}
                                            {{-- <button class="btn btn-primary mt-4" onclick="updateProgress(75)">Set Progress to 75%</button> --}}
                                        {{-- </div> --}}
                                        <div class="col-md-5">
                                            <meta name="verification-csrf-token" content="{{ csrf_token() }}">
                                            <a href="" class="startverification-btn" onclick="startVerification(event,this,{{$value['fileId']}})">Start Verification</a>
                                        </div>
        
                                    </div>
                                </div> 
                                @endif
                        @endforeach
                        @else
                            <h1 class="no-data-found">No Data Found!!</h1>
                        @endif
                         
                         
                    </div>
                </div>
                <div class="col-md-4 layout-sidebar--left">
                    <br>
                    <div class="curent--aside">
                        <div class="upload-aside--widget">
                            <h4 class="widget-title">Upload Your File (CSV, TXT, XLSX, XLS)</h4>
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
                                <a href="javascript:void(0)" class="import-btn" id="uploadbtn">Import list</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <!-- Modal -->
    <div id="popupModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Verification Result</h2>
            <button onclick="closePopup()" class="text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
            </div>

            <!-- Tabs -->
            <div class="flex border-b" id="tabs">
            <button id="downloadTab" class="flex-1 py-2 px-4 text-center hover:bg-gray-100 font-medium text-blue-600 border-b-2 border-blue-600">
                <i class="fas fa-download mr-1"></i> Download CSV
            </button>
            <button id="uploadTab" class="flex-1 py-2 px-4 text-center hover:bg-gray-100 font-medium text-gray-600">
                <i class="fas fa-cloud-upload-alt mr-1"></i> Update Mailchimp
            </button>
            </div>

            <!-- Dynamic Content Area -->
            <div class="p-4 p-4 flex flex-col" id="popupContent">
            <!-- Content will be injected dynamically here -->
            </div>
        </div>
    </div>

    <meta name="import-csrf-token" content="{{ csrf_token() }}">


@endsection

@section('specificScript')
        <script src="{{ asset('integration/css/tailwind/script.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script> 
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            window.notyf = new Notyf();
            @if(session('success'))
                notyf.success("{{ session('success') }}");
            @endif
            @if(session('error'))
                notyf.error("{{ session('error') }}");
            @endif
        });
    </script>
@endsection