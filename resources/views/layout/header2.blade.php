<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>Bouncify API Docs</title> --}}
    @stack('title')

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/bouncee-logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('docx/css/style.css') }}">
    <script src="{{ asset('docx/tailwind/script.js') }}"></script>
</head>
<body>
    <!-- Header -->
    <section id="documentation-section">
        <nav class="navbar navbar-light bg-light px-3 py-3 docx-navbar flex flex-row justify-between ">
            {{-- <a class="navbar-brand fw-bold" href="#">@Bouncify</a> --}}
            <a href="/" class="logo navbar-brand fw-bold">
                <img  class="w-[40%]" src="{{ asset('/assets/logo.png') }}" alt="">
            </a>
            {{-- <a class="btn btn-primary rounded-md text-white bg-blue-500 shadow-lg  py-1 px-4" href="#">Log In</a> --}}
        </nav>
        @include('docx.sidebar')
        <main class="flex-1 ml-64">
            <div class="max-w-4xl mx-auto py-12 px-8">
                @yield('content')
            </div>
        </main>
    </section>
</body>
</html>
