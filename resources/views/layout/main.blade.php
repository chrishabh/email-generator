
@if(auth()->check() && (auth()->user()->role == 'user' || auth()->user()->role == 'admin') && (request()->path()=='single' || request()->path()=='bulk' || request()->path()=='pricing') ) 
    @include('layout.header1',['headerData' =>$headerData??null])
@else 
    @include('layout.header',['headerData' =>$headerData??null])
@endif

@yield('main-section')

@if((auth()->check()))
    @include('layout.auth-footer')
@else
    @include('layout.footer')
@endif