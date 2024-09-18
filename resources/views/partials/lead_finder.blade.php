@if (isset($fileData) && !empty($fileData))
    @foreach ($fileData as $key => $value)
        @if ($value['verificationStatus'] == 'verified')
            <div class="info-line flex" id="list_{{ $value['fileId'] }}" data-attribute="{{ $value['fileId'] }}">
                <div class="info-line--left col-md-5">
                    <div class="img-cont">
                        <img src="{{ asset('assets/doc.svg') }}" alt="">
                    </div>
                    <div class="title-wrap">
                        <div class="title"><a href="javascript:void(0)">{{ $value['fileName'] }}</a></div>
                        <div class="meta"><span class="txt-green">{{ $value['total'] }} Emails</span> - {{ $value['created_at'] }}</div>
                    </div>
                </div>
                <div class="info-line--right row col-md-6 col-offset-1 align-items-center ">
                    <div class="col-md-4 stat-col">
                        Valid Emails
                        <div class="text-green val">{{ $value['totalValidEmail'] }}</div>
                    </div>
                    <div class="col-md-5 stat-col">
                        Invalid Emails
                        <div class="text-red val">{{ $value['totalInvalidEmail'] }}</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <i class="fa-solid fa-download download-icon" onclick="downloadCsvFile(event, {{ $value['fileId'] }}, {{ $value['isDownloadFileLocation'] }}, this)"></i>
                    </div>
                </div>
            </div>
        @endif
        @if ($value['verificationStatus'] == 'pending')
            <div class="info-line flex" id="list_{{ $value['fileId'] }}" data-attribute="{{ $value['fileId'] }}">
                <div class="info-line--left col-md-5">
                    <div class="img-cont">
                        <img src="{{ asset('assets/doc.svg') }}" alt="">
                    </div>
                    <div class="title-wrap">
                        <div class="title"><a href="javascript:void(0)">{{ $value['fileName'] }}</a></div>
                        <div class="meta"><span class="txt-green">{{ $value['total'] }} Emails</span> - {{ $value['created_at'] }}</div>
                    </div>
                </div>
                <div class="info-line--right row col-md-7 col-offset-1 align-items-center">
                    <div class="col-md-5">
                        <span class="time-tag"><span class="icon-clock"></span>start to processing</span>
                    </div>
                    <div class="col-md-2">
                        <div class="neumorphic-progress-circle">
                            <div class="circle">
                                <div class="mask full">
                                    <div class="fill"></div>
                                </div>
                                <div class="mask half">
                                    <div class="fill"></div>
                                </div>
                                <div class="inside-circle">
                                    <span class="percentage">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <a href="" class="startverification-btn" onclick="startVerification(event, this, {{ $value['fileId'] }})">Start Verification</a>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@else
        <h1 class="no-data-found">No Data Found!!</h1>
@endif
