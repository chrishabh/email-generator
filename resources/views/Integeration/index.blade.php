@extends('layout.main')
@php
    // $headerData = array();
    $headerData['whichPageRequest'] = 'Integration';
@endphp
@section('main-section')
    @push('styles')
        <style>
            #custom-api-section button:focus,#ImportEmails button:focus {
                outline: none;
                background-color: #2a3898 !important;
            }
            #importCloseModal button:focus{
                outline: none;
                background-color: rgb(107 114 128 / 1) !important;
            }
            .border-animation {
            position: relative;
            z-index: 0;
            overflow: hidden;
            }
      
            .border-animation::before {
            content: '';
            position: absolute;
            z-index: -1;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(130deg, #ff00cc, #3333ff, #00ffcc, #ffcc00);
            background-size: 300% 300%;
            animation: borderMove 4s linear infinite;
            border-radius: inherit;
            filter: blur(3px);
            }
      
            @keyframes borderMove {
                0% {
                    background-position: 0% 50%;
                }
                50% {
                    background-position: 100% 50%;
                }
                100% {
                    background-position: 0% 50%;
                }
            }
      </style>
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
            <button id="openModal" class="bg-[#3F51B5] hover:bg-[#2a3898] text-white px-4 py-2 rounded">Add Integration</button>
        </div>

        <div id="integrationsList"></div>

        <div class="flex justify-between items-center mt-4">
            <div id="list-pagination">
                {{-- <label for="itemsPerPage" class="mr-2">Items per page:</label>
                <select id="itemsPerPage" class="border rounded px-2 py-1">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                </select> --}}
            </div>
            <div>
                <button id="prevPage" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded-l">
                    
                </button>
                <button id="nextPage" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded-r">
                    
                </button>
            </div>
        </div> --}}
    </div>

    <!-- Modal -->
    <div id="integrationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg md:max-w-2xl md:w-full sm:max-w-1xl">
            <h2 class="text-lg font-bold mb-4 text-xl  text-[#3F51B5]">Add a New Integration</h2>
            <div id="availableIntegrationsList" class="max-h-[70vh] sm:max-h-[50vh] md:max-h-[60vh] overflow-y-auto"></div>
            <div class="mt-4 flex justify-between items-center">
                <div id="pagination"></div>
                <button id="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>
    <!-- Import modal -->
    <div id="ImportEmails" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg md:max-w-2xl md:w-full sm:max-w-1xl">
            <h2 class="text-lg font-bold mb-4 text-xl  text-[#3F51B5]">Import List</h2>
            <div id="availableImportEmails" class="max-h-[70vh] sm:max-h-[50vh] md:max-h-[60vh] overflow-y-auto"></div>
            <div class="mt-4 flex justify-between items-center"> 
                <button id="importCloseModal" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div> 
    <div id="preloaderAgain" class="fixed inset-0 bg-white bg-opacity-90 flex items-center justify-center z-50 hidden">
        <div class="relative w-12 h-12">
            <div class="absolute w-full h-full rounded-full bg-blue-600 animate-bounce"></div>
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-10 h-1 bg-blue-300 blur-sm opacity-70 rounded-full animate-pulse"></div>
        </div>
    </div>
@endsection 


@section('specificScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  --}}

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('integration/js/index.js') }}" type="text/javascript"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notyf = new Notyf();

            @if(session('success'))
                notyf.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                notyf.error("{{ session('error') }}");
            @endif
        });
    </script>

@endsection
