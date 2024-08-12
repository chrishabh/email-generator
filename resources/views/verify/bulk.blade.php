@extends('layout.main')

@section('main-section')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('verify/bulk-upload/css/style.css') }}">
        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
        <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
        <script src="{{ asset('verify/bulk-upload/js/script.js') }}" type="text/javascript"></script>
    @endpush
    <section class="bulk-upload" id="upload--bulk">
        <div class="container1">
            <div class="row flex-row-reverse">
                <div class="col-md-8  layout-sidebar--content">
                    <div class="form-search">
                        <i class="fa-thin fa-magnifying-glass"></i>
                        <input type="email" placeholder="Search your completed list" id="filenameInput" autocomplete="off">
                        <button class="btn-submit">Search</button>
                    </div>
                    <div class="upload-file--list">
                        <div class="info-line flex" id="list_57678">
                            <div class="info-line--left col-md-5">
                                <div class="img-cont">
                                    <img src="{{ asset('assets/doc.svg') }}" alt="">
                                </div>
                                <div class="title-wrap">
                                    <div class="title"><a href="javascript:void(0)">bulkUploadData.xlsx</a></div>
                                    <div class="meta"><span class="txt-green">0 Emails </span>- 8/6/24, 11:58 AM</div>
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
                    </div>
                </div>
                <div class="col-md-4 layout-sidebar--left">
                    <br>
                    <div class="curent--aside">
                        <div class="upload-aside--widget">
                            <h4 class="widget-title">UPLOAD Your file ( csv , txt ,xls , xlsx)</h4>
                            <form action="javascript:void(0)" class="form-upload">
                                <div class="filepond--root uploader filepond--hopper">
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
                                </div>
                                <a href="javascript:void(0)" class="import-btn btn" id="uploadbtn">Import list</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
