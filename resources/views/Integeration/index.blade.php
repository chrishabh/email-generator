@extends('layout.main')
@php
    // $headerData = array();
    $headerData['whichPageRequest'] = 'integrationPage';
@endphp
@section('main-section')
    @push('styles')
        {{-- <link rel="stylesheet" href="{{ asset('api/css/style.css') }}">  --}}

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('api/css/notyf/notyf.min.css') }}">
        <link rel="stylesheet" href="{{ asset('api/css/sweetalert/sweetalert2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('api/css/materialcss/materialize.min.css') }}">
        <link rel="stylesheet" href="{{ asset('api/css/materialcss/materialfont.min.css') }}">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script src="{{ asset('integration/css/tailwind/script.js') }}"></script>
    @endpush
 
    <div id="custom-api-section" class="container1 mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Connected Integrations</h1>
            <button id="openModal" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Add Integration</button>
        </div>

        <div id="integrationsList" class="bg-white rounded-lg shadow p-4"></div>

        <div class="flex justify-between items-center mt-4">
            <div>
                <label for="itemsPerPage" class="mr-2">Items per page:</label>
                <select id="itemsPerPage" class="border rounded px-2 py-1">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                </select>
            </div>
            <div>
                <button id="prevPage" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded-l">
                    ←
                </button>
                <button id="nextPage" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded-r">
                    →
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="integrationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg md:max-w-2xl md:w-full sm:max-w-1xl">
            <h2 class="text-lg font-bold mb-4 text-xl text-blue-400">Add a New Integration</h2>
            <div id="availableIntegrationsList" class="max-h-[70vh] sm:max-h-[50vh] md:max-h-[60vh] overflow-y-auto"></div>
            <div class="mt-4 flex justify-between items-center">
                <div id="pagination"></div>
                <button id="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>
@endsection 


@section('specificScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  --}}

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('integration/js/index.js') }}" type="text/javascript"></script>
@endsection
