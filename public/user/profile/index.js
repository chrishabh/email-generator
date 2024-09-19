function enableFormFields(card) {
    const fields = card.querySelectorAll('input, select, textarea');
    const editIcon = card.querySelector('.edit-icon');
    const buttons = card.querySelectorAll('.updated-buttons');
    
    // Remove disabled attribute from form fields
    fields.forEach(field => field.removeAttribute('disabled'));

    // Hide the edit icon
    editIcon.style.display = 'none';

    // Show the buttons
    buttons.forEach(button => {
        button.classList.remove('hide-btn');
        button.classList.add('show-btn');
    });
}

function cancelEdit(card,isCancelButtonHit=false) {
    const fields = card.querySelectorAll('input, select, textarea');
    const editIcon = card.querySelector('.edit-icon');
    const buttons = card.querySelectorAll('.updated-buttons');

    // Add disabled attribute back to form fields
    fields.forEach(field =>{
        if(field.id=='current_password'){
            if(field.classList.contains('input-field-error'))  field.classList.remove('input-field-error')
            field.value=''
            const currentPassword = document.getElementById('current-password-error')
            if(currentPassword){
                currentPassword.style.display='none'
            }

        }
        if(field.id=='new_password'){
            field.value=''
            if(field.classList.contains('input-field-error'))  field.classList.remove('input-field-error')
            const newPassword = document.getElementById('new-password-error')
            if(newPassword){
                newPassword.style.display='none'
            }

        }
        if(field.id=='confirm_password'){
            field.value=''
            if(field.classList.contains('input-field-error'))  field.classList.remove('input-field-error')
            const confirmPassword = document.getElementById('confirm-password-error')
            if(confirmPassword){
                confirmPassword.style.display='none'
            }

        }
        field.setAttribute('disabled', 'disabled')
    });

    if(isCancelButtonHit){
        fields.forEach(field => {
            if (field.dataset.originalValue !== undefined) {
                field.value = field.dataset.originalValue;
            }
        });
    }
     

    // Show the edit icon
    editIcon.style.display = 'block';

    // Hide the buttons
    buttons.forEach(button =>{
        button.classList.add('hide-btn');
        button.classList.remove('show-btn');
    });
}

function init(){
    // Add event listeners to all edit icons
    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const card = icon.closest('.card');
            console.log(card);
            enableFormFields(card);
        });
    });

    // Add event listener to mobile event
    document.getElementById('mobile').addEventListener('keypress', function (e) {
        // Only allow digits (0-9) to be entered
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });

    // Add event listeners to all cancel buttons
    document.querySelectorAll('.btn-cancel').forEach(button => {
        button.addEventListener('click', function() {
            const card = button.closest('.card');
            // let form = this.closest('form');
            cancelEdit(card,true);
            
        });
    });
    
    // Update Personal Info
    const personalInfoForm = document.getElementById('personal_info_form');
    if(personalInfoForm){
        personalInfoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData    =   new FormData(this); 
            const loader    =   document.getElementById('personal-info-loader'); 
            const fieldName =   ['mobile','dob','gender'];
            const url       =   '/profile/update';
            const tokenName =   'personal-info-csrf-token';
            fetchApiRequest(personalInfoForm,formData,url,loader,tokenName,fieldName)
        });
    }

    // work experience Info
    const workExperienceForm = document.getElementById('work_experience_info_form');
    if(workExperienceForm){
        workExperienceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData    =   new FormData(this); 
            const loader    =   document.getElementById('work-experience-info-loader'); 
            const fieldName =   ['work_experience'];
            const url       =   '/profile/work-experience/update';
            const tokenName =   'work-experience-info-csrf-token';
            fetchApiRequest(workExperienceForm,formData,url,loader,tokenName,fieldName)
        });
    }

    // password update
    const passwordUpdateForm = document.getElementById('password_update_form');
    if(passwordUpdateForm){
        passwordUpdateForm.addEventListener('submit', function(e) {  
           if (!checkPasswordsMatch()) {
                e.preventDefault(); // Prevent form submission if passwords don't match
            }else{
                e.preventDefault();
                let formData    =   new FormData(this); 
                const loader    =   document.getElementById('password-info-loader'); 
                const fieldName =   ['current_password','new_password','confirm_password'];
                const url       =   '/profile/password/update';
                const tokenName =   'work-experience-info-csrf-token';
                fetchApiRequest(passwordUpdateForm,formData,url,loader,tokenName,fieldName)
            }
        });
    }

    const newPassword             = document.getElementById("new_password");
    const confirmPassword         = document.getElementById("confirm_password"); 
    const currentPassword         = document.getElementById("current_password"); 
    // Real-time validation when typing
    confirmPassword.addEventListener("input", checkPasswordsMatch);
    newPassword.addEventListener("input", checkPasswordsMatch); 
    currentPassword.addEventListener("input", checkPasswordsMatch); 





    // Add event listener to toggle password visibility
    document.querySelectorAll('.password-toggle-icon').forEach(function(toggleIcon) {
        toggleIcon.addEventListener('click', function() {
            const target        = this.getAttribute('data-target'); // Get the target field ID
            const passwordField = document.getElementById(target);
            const icon          = this;
            
            // Check if the input field is disabled
            if (passwordField.disabled) {
                return; // Do nothing if the field is disabled
            }
    
            // Toggle between text and password field types
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    

}

// Function to check if passwords match
function checkPasswordsMatch() {
    const newPasswordError     = document.getElementById("new-password-error");
    const confirmPasswordError = document.getElementById("confirm-password-error"); 
    const newPassword          = document.getElementById("new_password");
    const confirmPassword      = document.getElementById("confirm_password");
    const currentPassword      = document.getElementById("current_password");
    const currentPasswordError = document.getElementById("current-password-error");

    const newPasswordValue     = newPassword.value;
    const confirmPasswordValue = confirmPassword.value;
    const currentPasswordValue = currentPassword.value;

    // Reset error states
    newPassword.classList.remove('input-field-error');
    confirmPassword.classList.remove('input-field-error');
    currentPassword.classList.remove('input-field-error');
    currentPasswordError.style.display = 'none';
    newPasswordError.style.display = 'none';
    confirmPasswordError.style.display = 'none';

    let valid = true;

    // Validate current password
    if (!currentPasswordValue) {
        currentPassword.classList.add('input-field-error');
        currentPasswordError.textContent = '*This field is Required!';
        currentPasswordError.style.display = 'block';
        valid = false;
    }

    // Validate new password
    if (newPasswordValue) {
        const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        let text = (newPasswordValue.length < 8) ? 'Must be at least 8 characters' :
                   !pattern.test(newPasswordValue) ? '*Password must include at least one lowercase letter, one uppercase letter, one number, and one special character.' : '';
        if (text) {
            newPassword.classList.add('input-field-error');
            newPasswordError.textContent = text;
            newPasswordError.style.display = 'block';
            valid = false;
        }
    } else {
        newPassword.classList.add('input-field-error');
        newPasswordError.textContent = '*This field is Required!';
        newPasswordError.style.display = 'block';
        valid = false;
    }

    // Validate confirm password
    if (confirmPasswordValue) {
        if (newPasswordValue !== confirmPasswordValue) {
            confirmPassword.classList.add('input-field-error');
            confirmPasswordError.textContent = '*Passwords do not match!';
            confirmPasswordError.style.display = 'block';
            valid = false;
        }
    } else {
        confirmPassword.classList.add('input-field-error');
        confirmPasswordError.textContent = '*This field is Required!';
        confirmPasswordError.style.display = 'block';
        valid = false;
    }

    return valid;
}



function fetchApiRequest(formId,formData,url,loader,tokenName,fieldsName){

    loader.style.display = 'flex'; // Show loader
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN':  document.querySelector(`meta[name="${tokenName}"]`).getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        loader.style.display = 'none';
        if(data.success) {
            if(fieldsName.length==3 && fieldsName[0]!='current_password'){ //personal information form
                const mobileField   = document.getElementById(fieldsName[0]);
                const dobField      = document.getElementById(fieldsName[1]);
                const genderField   = document.getElementById(fieldsName[2]);
                
                // Update form field values
                mobileField.value  = data.data.mobile_number;
                dobField.value     = data.data.date_of_birth;
                genderField.value  = data.data.gender;

                // Update the data-original-value attributes
                mobileField.setAttribute('data-original-value', data.data.mobile_number);
                dobField.setAttribute('data-original-value', data.data.date_of_birth);
                genderField.setAttribute('data-original-value', data.data.gender);
            }
            if(fieldsName.length==1){ //work experience information form
                const workExperience   = document.getElementById(fieldsName[0]); 
                
                // Update form field values
                workExperience.value  = data.data.work_experience_description;
                // Update the data-original-value attributes
                workExperience.setAttribute('data-original-value', data.data.work_experience_description);
            }

            Swal.fire({
                icon: 'success',
                'text': data.message,
                showConfirmButton: true
            }).then((result) => {
                    if(result.isConfirmed) {         
                        const button = formId.querySelector('.btn-cancel');
                        const card = button.closest('.card');
                        cancelEdit(card);
                    }
                });
        }
        else if(data.success==false){
            Swal.fire('Error', data.message||'Something went wrong!', 'error');
        }
    })
    .catch(error => {
        loader.style.display = 'none'; 
        console.error(error);
        Swal.fire('Error', 'Failed to update the profile.', 'error');
    });
}




document.addEventListener('DOMContentLoaded',init)