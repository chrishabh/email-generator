@extends('layout.main')

@section('main-section')
    @push('styles') 
        <link rel="stylesheet" href="{{ asset('api/css/style.css') }}"> 
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
         
  
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> 
        <link rel="stylesheet" href="{{ asset('api/css/notyf/notyf.min.css') }}"> 
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css"> --}}
        <link rel="stylesheet" href="{{ asset('api/css/sweetalert/sweetalert2.min.css') }}"> 
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">  --}}
        {{-- <link rel="stylesheet" href="{{ asset('api/css/materialcss/materialize.min.css') }}">  --}}
        {{-- <link href="https://cdn.jsdelivr.net/npm/materialize-css@1.0.0/dist/css/materialize.min.css" rel="stylesheet">  --}}
        <link rel="stylesheet" href="{{ asset('api/css/materialcss/materialfont.min.css') }}"> 
        {{-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> --}}
        <script src="{{ asset('docx/tailwind/script.js') }}"></script>
         

    @endpush

   <section id="custom-api-section">
        <div class="container1">
            <div class="shadow">
                <div class="top-section my-12 pt-7">
                    <div class="row items-center">
                        <div class="col-6">
                            <h1 class="text-xl font-medium">API Keys</h1>
                        </div>
                        <div class="col-6 justify-items-end d-flex justify-content-end">
                            <a class=" waves-effect waves-light btn btn-bg-bl modal-trigger hover:text-white font-normal" href="#modal1" >New API Key</a>
                            <a class=" waves-effect waves-light btn btn-bg-bl hover:text-white font-normal ml-4" href="/reference" >API Docs</a>
                        </div>
                    </div>
                </div>
                <div class="bottom-section pb-20">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 col-sm-12"> 
                            {{-- @if (empty($apiKeys))
                                <p class="text-center text-xl font-500">No API keys found.</p>
                            @else --}}
                            {{-- <table class="table table-bordered shadow-lg">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Key</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="apiKeysTableBody">
                                    @foreach($apiKeys as $apiKey)
                                    <tr>
                                        <td>{{ $apiKey['name'] }}</td>
                                        <td>
                                            <span id="key-{{ $apiKey['id'] }}">{{ $apiKey['key'] }}</span>
                                            <i id="copybutton" class="fa-regular fa-copy copy-clipboard pl-2" data-position="bottom" data-tooltip="copy" style="font-size: 18px;"   data-clipboard-text="{{$apiKey['key'] }}" onclick="copyToClipboard()" ></i>
                                        </td>
                                        <td>
                                            <span class="inline-flex items-center rounded-md bg-{{ $apiKey['status']=='Enabled' ? 'green' : 'red' }}-100 px-2 py-1 text-xs font-medium text-{{ $apiKey['status']=='Enabled' ? 'green' : 'red' }}-700 ring-1 ring-inset ring-{{ $apiKey['status']=='Enabled' ? 'green' : 'red' }}-600/20 ">
                                                {{ $apiKey['status'] }}
                                            </span>
                                        </td>
                                        <td>{{ $apiKey['created_at'] }}</td>
                                        <td>
                                            <i id="editToolTip" class="fas fa-pencil pr-2 hover:cursor-pointer" data-position="bottom" data-tooltip="edit" style="font-size:14px;"></i>
                                            <i id="regenerateToolTip" onclick="regenerate({{$apiKey['id']}})"  class="fas fa-arrows-rotate pr-2 hover:cursor-pointer" data-position="bottom" data-tooltip="regenerate"  style="font-size: 14px;font-weight:600"></i>
                                            <i id="deletedToolTip" onclick="triggerSweetAlert('Are you sure you want to delete this?',{{$apiKey['id']}})"  class="fa fa-trash d-inline hover:cursor-pointer  "  data-position="bottom" data-tooltip="deleted" style="font-size: 14px;"></i>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table> --}}
                            <div id="tableRenderSection"></div>
                            {{-- @endif   --}}
                        </div>
                    </div>
                </div>
            </div>


            <!-- New API Key Modal -->
            <div id="modal1" class="modal modal-sm custom-modal rounded-lg">
                <div class="modal-content">
                    <div class="row mx-0 align-items-center">
                        <!-- API Key Heading with Icons on the Right -->
                        <div class="col-6">
                            <h4 class="left-align text-lg font-medium">Generate API Key   
                        </div>
                        <div class="col-6">
                            <a href="#!" class="modal-close right">
                            <i class="material-icons text-3xl">close</i>
                            </a>
                            <!-- Save Icon (Check Circle) -->
                            <a  class="right hover:cursor-pointer" onclick="saveApiKey(event)">
                            <i class="material-icons text-3xl bg-color px-3">check_circle</i>
                            </a>
                        </div>
                    </div>  
                    <div class="input-field">
                        <div class="flex items-center justify-between">
                          <!-- Input Field and Label -->
                          <div class="w-full relative">
                            <input id="apiKey" type="text"  class="validate w-full" onblur="validateInput()" oninput="hideError()" />
                            <label for="apiKey" class="absolute text-gray-500">API Key Name</label>
                          </div>
                      
                          <!-- Error Icon -->
                          <span id="error-icon" class="hidden text-red-600 ml-2 flex-shrink-0">
                            <i class="material-icons">error</i>
                          </span>
                        </div>
                        
                        <!-- Error Message -->
                        <span id="error-message" class="hidden text-red-600 text-sm mt-1 block">
                          Please enter name
                        </span>
                    </div>
                      
                </div>   
            </div>
        </div>
   </section>
@endsection


@section('specificScript')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 


    <script src="https://cdn.jsdelivr.net/npm/materialize-css@1.0.0/dist/js/materialize.min.js"></script> 
    <script src="https://cdn.tailwindcss.com"></script> 
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> 
    <script src="{{ asset('api/js/script.js') }}" type="text/javascript"></script>

@endsection