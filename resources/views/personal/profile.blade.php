@extends('layout.main')
 
@section('main-section')
@push('title')
    <title> Profle | Bouncee</title>
@endpush
    @push('styles')
        <link rel="stylesheet" href="{{ asset('user/profile/style.css') }}">   
        <link rel="shortcut icon" href="assets/bouncee-logo.png" type="image/png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> 
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
        <script src="{{ asset('user/profile/index.js') }}" type="text/javascript"></script> 
        
    @endpush
 
@php
    if(!empty($userData))
        $userData = $userData->toArray(); 
    
    // Check if the name contains at least two parts
    $name ='';
    if(!empty($userData) && $userData['name']){
        $fullName = $userData['name'];
        $nameParts = explode(' ', $fullName);
    if (count($nameParts) < 2) {
        $firstName = $nameParts[0];
        $name      = strtoupper($firstName); 
    }else{
        $firstName = $nameParts[0];
        $lastName = $nameParts[count($nameParts) - 1]; 
        // Get the first and last capital letters
        $firstCapitalLetter = strtoupper($firstName[0]);
        $lastCapitalLetter = strtoupper($lastName[0]); 
        $name              = $firstCapitalLetter.''.$lastCapitalLetter;
    }
    }
     

@endphp
    <section id="profile-page">
        <div class="container1 mt-5">
            <div class="row">
                <!-- Left Column: Profile Image and Personal Information -->
                <div class="col-lg-5 col-md-12 d-flex flex-column">
                    <!-- Profile Image Section -->
                    <div class="card mb-4 custom-boxshadow flex-grow-1 card-bg-grey">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Picture <span class="mb-0 edit-icon float-right">
                                {{-- <i class="fas fa-edit"></i></span> --}}
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="position-relative">
                                <div class="avatar">
                                    <span class="avatar-text">{{$name}}</span>
                                </div>
                                <div class="font-weight-bold pt-3 text-capitalize">{{$userData['name']}}</div>
                                {{-- <div class="edit-avatar-overlay">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('profile_image').click();">Edit Avatar</button>
                                </div> --}}
                            </div>
                            {{-- <form method="POST" action="/profile/image/update" enctype="multipart/form-data" style="display: none;">
                                <input type="hidden" name="_token" value="your_csrf_token_here">
                                <input type="file" id="profile_image" name="profile_image" class="form-control-file" onchange="this.form.submit();">
                            </form> --}}
                        </div>
                    </div>
    
                    <!-- Personal Information Section -->
                    <div class="card custom-boxshadow flex-grow-1 card-bg-grey">
                        <div class="card-header">
                            <h5 class="mb-0">Personal Information <span class="mb-0 edit-icon float-right"><i class="fas fa-edit"></i></span></h5>
                        </div>
                        <div class="card-body">
                            <div class="loader-overlay " id="personal-info-loader" style="display: none">
                                <div class="spinner"></div>
                            </div>
                            <form method="POST" id="personal_info_form" >
                                <meta name="personal-info-csrf-token" content="{{ csrf_token() }}">
                                {{-- <div class="mb-3 row mx-0 align-items-center justify-content-start">
                                    <label for="name" class="col-md-3 col-form-label pr-1">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" id="name" name="name" class="form-control mb-0" value="John Doe" required disabled>
                                    </div>
                                </div> --}}
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="mobile" class="col-md-3 col-form-label pr-1">Mobile Number</label>
                                    <div class="col-md-9">
                                        <input type="tel" pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number" id="mobile" name="mobile" class="form-control mb-0" value="{{ old('mobile',$userData['mobile_number'])}}" required disabled data-original-value="{{ old('dob', $userData['mobile_number']) }}">
                                    </div>
                                </div>
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="dob" class="col-md-3 col-form-label pr-1">Date of Birth</label>
                                    <div class="col-md-9">
                                        <input type="date" id="dob" name="dob" class="form-control mb-0"  value="{{old('dob', $userData['date_of_birth'])}}"   disabled data-original-value="{{ old('dob', $userData['date_of_birth']) }}">
                                    </div>
                                </div>
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="gender" class="col-md-3 col-form-label pr-1">Gender</label>
                                    <div class="col-md-9">
                                        <select id="gender" name="gender" class="form-select form-control w-100 mb-0" required disabled data-original-value="{{ old('gender', $userData['gender']) }}">
                                            <option value="" disabled {{ old('gender', $userData['gender']) === '' || $userData['gender'] === null ? 'selected' : '' }}>Please Select</option>
                                            <option value="male" {{ old('gender', $userData['gender']) === 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $userData['gender']) === 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $userData['gender']) === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex p-inline-2 hide-btn updated-buttons">
                                    <button type="submit" class="btn bg-with-grad mr-2 custom-boxshadow ">Save Changes</button>
                                    <button type="button" class="btn btn-cancel custom-boxshadow ">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    
                <!-- Right Column: Work Experience, Biography, and Update Password -->
                <div class="col-lg-7 col-md-12  d-flex flex-column">
                    <!-- Work Experience Section -->
                    <div class="card mb-4 custom-boxshadow flex-grow-1 card-bg-grey">
                        <div class="card-header">
                            <h5 class="mb-0">User Experience<span class="mb-0 edit-icon float-right"><i class="fas fa-edit"></i></span></h5>
                        </div>
                        <div class="card-body">
                            <div class="loader-overlay " id="work-experience-info-loader" style="display: none">
                                <div class="spinner"></div>
                            </div>
                            <form method="POST"  id="work_experience_info_form">
                                <meta name="work-experience-info-csrf-token" content="{{ csrf_token() }}">
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="work_experience" class="col-md-3 col-form-label pr-1">Describe your experience:</label>
                                    <div class="col-md-9">
                                        <textarea id="work_experience" name="work_experience" class="form-control" rows="4" disabled  data-original-value="{{ old('work_experience', $userData['work_experience_description']) }}">{{old('work_experience',$userData['work_experience_description'])}}</textarea>
                                    </div>
                                </div>
                                <div class="d-flex p-inline-2 hide-btn updated-buttons">
                                    <button type="submit" class="btn bg-with-grad mr-2 custom-boxshadow">Update Experience</button>
                                    <button type="button" class="btn btn-cancel custom-boxshadow ">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
    
                    <!-- Update Password Section -->
                    <div class="card  custom-boxshadow flex-grow-1 card-bg-grey">
                        <div class="card-header">
                            <h5 class="mb-0">Change Password <span class="mb-0 edit-icon float-right"><i class="fas fa-edit"></i></span></h5>
                        </div>
                        <div class="card-body">
                            <div class="loader-overlay " id="password-info-loader" style="display: none">
                                <div class="spinner"></div>
                            </div>
                            <form method="POST" id="password_update_form" > 
                                <meta name="password-info-csrf-token" content="{{ csrf_token() }}">
                                 <!-- Current Password Field -->
                                <div class="mb-3  row mx-0 align-items-center">
                                    <label for="current_password" class="col-md-3 col-form-label pr-1">Current Password</label>
                                    {{-- <div class="col-md-9">
                                        <input type="password" id="current_password" name="current_password" class="form-control  mb-0" disabled>
                                        <small id="current-password-error" class="form-text text-danger password-error" style="display: none;">*This field is required</small>
                                    </div> --}}
                                    <div class="col-md-9 position-relative">
                                        <input type="password" id="current_password" name="current_password" class="form-control mb-0" disabled>
                                        <i class="fas fa-eye password-toggle-icon" data-target="current_password"></i>
                                        <small id="current-password-error" class="form-text text-danger password-error" style="display: none;">*This field is required</small>
                                    </div>
                                </div>

                                <!-- New Password Field -->
                                <div class="mb-3  row mx-0 align-items-center">
                                    <label for="new_password" class="col-md-3 col-form-label pr-1">New Password</label>
                                    {{-- <div class="col-md-9"> 
                                        <input type="password" id="new_password" name="new_password" class="form-control  mb-0" disabled placeholder="Type your password (e.g., Passw0rd!)">
                                        <small id="new-password-error" class="form-text text-danger password-error" style="display: none;">*Passwords do not match!</small>
                                    </div>  --}}
                                    <div class="col-md-9 position-relative">
                                        <input type="password" id="new_password" name="new_password" class="form-control mb-0" disabled placeholder="Type your password (e.g., Passw0rd!)">
                                        <i class="fas fa-eye password-toggle-icon" data-target="new_password"></i>
                                        <small id="new-password-error" class="form-text text-danger password-error" style="display: none;">*Passwords do not match!</small>
                                    </div> 
                                </div>

                                <!-- Confirm Password Field -->
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="confirm_password" class="col-md-3 col-form-label pr-1">Confirm New Password</label>
                                    {{-- <div class="col-md-9"> 
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control  mb-0" disabled>
                                        <small id="confirm-password-error" class="form-text text-danger password-error" style="display: none;">*Passwords do not match!</small>
                                    </div> --}}
                                    
                                    <div class="col-md-9 position-relative">
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control mb-0" disabled placeholder="Type your password (e.g., Passw0rd!)">
                                        <i class="fas fa-eye password-toggle-icon" data-target="confirm_password"></i>
                                        <small id="confirm-password-error" class="form-text text-danger password-error" style="display: none;">*Passwords do not match!</small>
                                    </div> 
                                </div>
                                <div class="d-flex p-inline-2 hide-btn updated-buttons">
                                    <button type="submit" class="btn bg-with-grad mr-2 custom-boxshadow ">Update Password</button>
                                    <button type="button" class="btn btn-cancel custom-boxshadow ">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection
