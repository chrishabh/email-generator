@if(auth()->check() && auth()->user()->role == 'user' && (request()->path()=='single' || request()->path()=='bulk') ) 
    @include('layout.header1',['headerData' =>$headerData??null])
@else 
    @include('layout.header',['headerData' =>$headerData??null])
@endif

@yield('main-section')

@if(!auth()->check() || (auth()->check() && auth()->user()->role != 'user'))
    @include('layout.footer')
@endif