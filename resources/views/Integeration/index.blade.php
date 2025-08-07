@extends('layout.main')
@php
    // $headerData = array();
    $headerData['whichPageRequest'] = 'Integration';
@endphp
@section('main-section')
    @push('styles')
        <style>
            #custom-api-section button:focus:not(#toggleDropdown):not(#removeIntegration),
            #ImportEmails button:focus:not(#toggleDropdown):not(#removeIntegration) {
                outline: none;
                background-color: #2a3898 !important;
            }
            #removeIntegration:focus {
            background-color: #f0f0f0 !important;
            outline: none;
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
      
            #moosendApiKeyInput,#moosendApiKeyInputURL {
                height: 2rem;
                max-width: 100%;
                width: 95%;
                font-size: 14px;
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        </div>
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

<!-- Moosend API Key Modal -->
    <div id="moosendApiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" role="dialog" aria-modal="true" aria-labelledby="moosendModalTitle">
        <div class="relative w-full max-w-xl mx-auto bg-white rounded-lg shadow-lg p-4">
            <!-- Modal Header -->
            <h2 id="moosendModalTitle" class="text-xl font-medium mb-4 text-gray-800">
                Connect Moosend
            </h2>

            <!-- Close Button -->
            <button type="button" id="moosendCloseModalButton" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-3xl leading-none" aria-label="Close modal">
                &times;
            </button>

            <!-- Input -->
            <div class="mb-4">
                <label for="moosendApiKeyInput"
                    class="block text-gray-700 text-sm font-bold mb-2">
                    API Key:
                </label>
                <input type="text" id="moosendApiKeyInput" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Enter your Moosend API Key">
            </div>
            <div class="mb-4 hidden">
                <label for="moosendApiKeyInputURL"
                    class="block text-gray-700 text-sm font-bold mb-2">
                    API URL:
                </label>
                <input type="text" id="moosendApiKeyInputURL" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Enter your API Url">
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
                <button type="button" id="moosendConnectButton"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-normal py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Connect
                </button>
                <button type="button" id="moosendCancelButton"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-normal py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Cancel
                </button>
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
