
@if(request()->path()=='single' || request()->path()=='bulk' || request()->path()=='pricing' ||request()->path()=='lead-finder'||request()->path()=='profile' ||request()->path()=='payment-history' ||request()->path()=='settings')
    @include('layout.header1',['headerData' =>$headerData??null])
@else 
    @include('layout.header',['headerData' =>$headerData??null])
@endif

@yield('main-section')

@if(request()->path()!='settings')
    @if((auth()->check()))
        @include('layout.auth-footer')
    @else
        @include('layout.footer')
    @endif
@endif