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
 

    <section id="profile-page">
        <div class="container1 mt-5">
            <div class="row">
                <!-- Left Column: Profile Image and Personal Information -->
                <div class="col-lg-5 col-md-12 d-flex flex-column">
                    <!-- Profile Image Section -->
                    <div class="card mb-4 custom-boxshadow flex-grow-1 card-bg-grey">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Picture <span class="mb-0 edit-icon float-right"><i class="fas fa-edit"></i></span></h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="position-relative">
                                <div class="avatar">
                                    <span class="avatar-text">AT</span>
                                </div>
                                <div class="font-weight-bold pt-3">ADitya Tyagi</div>
                                <div class="edit-avatar-overlay">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('profile_image').click();">Edit Avatar</button>
                                </div>
                            </div>
                            <form method="POST" action="/profile/image/update" enctype="multipart/form-data" style="display: none;">
                                <input type="hidden" name="_token" value="your_csrf_token_here">
                                <input type="file" id="profile_image" name="profile_image" class="form-control-file" onchange="this.form.submit();">
                            </form>
                        </div>
                    </div>
    
                    <!-- Personal Information Section -->
                    <div class="card custom-boxshadow flex-grow-1 card-bg-grey">
                        <div class="card-header">
                            <h5 class="mb-0">Personal Information <span class="mb-0 edit-icon float-right"><i class="fas fa-edit"></i></span></h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/profile/update">
                                <input type="hidden" name="_token" value="your_csrf_token_here">
                                <div class="mb-3 row mx-0 align-items-center justify-content-start">
                                    <label for="name" class="col-md-3 col-form-label pr-1">Name</label>
                                    <div class="col-md-9">
                                        <input type="text" id="name" name="name" class="form-control mb-0" value="John Doe" required disabled>
                                    </div>
                                </div>
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="mobile" class="col-md-3 col-form-label pr-1">Mobile Number</label>
                                    <div class="col-md-9">
                                        <input type="text" id="mobile" name="mobile" class="form-control mb-0" value="1234567890" required disabled>
                                    </div>
                                </div>
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="dob" class="col-md-3 col-form-label pr-1">Date of Birth</label>
                                    <div class="col-md-9">
                                        <input type="date" id="dob" name="dob" class="form-control mb-0" value="1990-01-01" required disabled>
                                    </div>
                                </div>
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="gender" class="col-md-3 col-form-label pr-1">Gender</label>
                                    <div class="col-md-9">
                                        <select id="gender" name="gender" class="form-select form-control  w-100 mb-0" required disabled>
                                            <option value="male" selected>Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
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
                            <h5 class="mb-0">Work Experience <span class="mb-0 edit-icon float-right"><i class="fas fa-edit"></i></span></h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/profile/work/update">
                                <input type="hidden" name="_token" value="your_csrf_token_here">
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="work_experience" class="col-md-3 col-form-label pr-1">Describe your work experience</label>
                                    <div class="col-md-9">
                                        <textarea id="work_experience" name="work_experience" class="form-control" rows="4" disabled>5 years in software development</textarea>
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
                            <form method="POST" action="/profile/password/update">
                                <input type="hidden" name="_token" value="your_csrf_token_here">
                                <div class="mb-3  row mx-0 align-items-center">
                                    <label for="current_password" class="col-md-3 col-form-label pr-1">Current Password</label>
                                    <div class="col-md-9">
                                        <input type="password" id="current_password" name="current_password" class="form-control" required disabled>
                                    </div>
                                </div>
                                <div class="mb-3  row mx-0 align-items-center">
                                    <label for="new_password" class="col-md-3 col-form-label pr-1">New Password</label>
                                    <div class="col-md-9"> 
                                        <input type="password" id="new_password" name="new_password" class="form-control" required disabled>
                                    </div> 
                                </div>
                                <div class="mb-3 row mx-0 align-items-center">
                                    <label for="confirm_password" class="col-md-3 col-form-label pr-1">Confirm New Password</label>
                                    <div class="col-md-9"> 
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required disabled>
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
