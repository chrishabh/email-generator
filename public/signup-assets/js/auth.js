let fn= ()=>{
    hideAlertMessages();
    signupFormValidation();

}

function validateField(ipElement,errorElement,validateFunction){
    const value               = ipElement.value.trim();
    const errorMessage        = validateFunction(value);
    errorElement.textContent  = errorMessage
    if(errorMessage) ipElement.classList.add('validation-error')
    else ipElement.classList.remove('validation-error')

}

function debouncing(func,delay){
    let timeoutId
    debugger
    return function(...args){
        clearTimeout(timeoutId)
        timeoutId = setTimeout(()=>func(...args),delay)
    }


}

function validateEmailNumber(value){
    return value=='' ? '*This Field is required.':''; 
}

function validateName(value){
    return value=='' ? '*This Field is required.':'';
}

function validateEmail(value){
    const emailRe     = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let emailErrorMes = '';
    emailErrorMes     = (value==='')?'*This Field is required.':'' || !emailRe.test(value) ? '*Invalid email address.':'';
    return emailErrorMes
}

function validatePassword(value){
    let passErrorMess = '';
    let pattern         = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    passErrorMess     = (value === '') ? '*This Field is required.' : '' || value.length<8? `*Must be at least 8 characters.` : '' || !pattern.test(value) ? '*Password must include at least one lowercase letter, one uppercase letter, one number, and one special character.':'';
    return passErrorMess;
}

const validateForm = function(){
    validateField(getSignupFormValue('no_of_email_verification',false,true), document.getElementById('NES'), validateEmailNumber);
    validateField(getSignupFormValue('name'), document.getElementById('nameError'), validateName);
    validateField(getSignupFormValue('email'), document.getElementById('emailError'), validateEmail);
    validateField(getSignupFormValue('password'), document.getElementById('passwordError'), validatePassword);
    
}


const inputValidation = function(){
    getSignupFormValue('no_of_email_verification',false,true).addEventListener('change',((event)=>{
        validateField(event.target, document.getElementById('NES'),validateEmailNumber)
    }))
    getSignupFormValue('name').addEventListener('input',debouncing((event)=>{
        validateField(event.target, document.getElementById('nameError'),validateName)
    },100))
    getSignupFormValue('email').addEventListener('input',debouncing((event)=>{
        validateField(event.target, document.getElementById('emailError'),validateEmail)
    },100))
    getSignupFormValue('password').addEventListener('input',debouncing((event)=>{
        validateField(event.target, document.getElementById('passwordError'), validatePassword);
    },100))
}


const signupFormValidation = function(){

    // input validation
    inputValidation()
    let signUpBtn = document.getElementById('signUpBtn')
    signUpBtn.addEventListener('click',(event)=>{
        event.preventDefault()
        validateForm();
        const hasErrors =document.querySelectorAll('.validation-error').length>0
        if(!hasErrors){
            const form = document.getElementById('signUpForm');
            form.submit();
        }

    //     const nES            = document.getElementById('NES');
    //     const nameError      = document.getElementById('nameError')
    //     const emailError     = document.getElementById('emailError')
    //     const passwordError  = document.getElementById('passwordError')

    //     const NBEV       = getSignupFormValue('no_of_email_verification',true,true);
    //     const NBE        = getSignupFormValue('no_of_email_verification',false,true);  
    //     const nameV      = getSignupFormValue('name',true);
    //     const name       = getSignupFormValue('name');
    //     const emailV     = getSignupFormValue('email',true);
    //     const email      = getSignupFormValue('email');
    //     const passwordV  = getSignupFormValue('password',true);
    //     const password  = getSignupFormValue('password');
    //     let errorMessage ='';
    //     let nameErrorM   = '';
    //     let emailM       = '';
    //     let passwordM    = '';

    //     if(NBEV==''){
    //         errorMessage= '*This Field is required.'
    //     }

    //     nES.textContent= errorMessage
    //     if(errorMessage)
    //         NBE.classList.add('validation-error');
    //     else NBE.classList.remove('validation-error');

    //     if(nameV==''){
    //         nameErrorM = '*This Field is required.'
    //     }

    //     nameError.textContent = nameErrorM
    //     if(nameErrorM)  name.classList.add('validation-error')
    //     else name.classList.remove('validation-error')

    //     // email validation code
    //     const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    //     if(emailV==''){
    //         emailM = '*This Field is required.';
    //     }else if(!re.test(emailV))
    //         emailM = '*Invalid email address.';    

    //     emailError.textContent = emailM
    //     if(emailM)  email.classList.add('validation-error')
    //     else email.classList.remove('validation-error')

    //     //password validation
    //     if(passwordV=='') passwordM = '*This Field is required.';
         
    //     // Check if there are any validation errors
    //     const hasErrors = errorMessage || nameErrorM || emailM || passwordM;

    // // Submit the form if no errors
    // if (!hasErrors) {
    //     // Assuming you have a form with the id 'signUpForm'
    //     const form = document.getElementById('signUpForm');
    //     form.submit();
    // }
    })
}

const getSignupFormValue=function(fieldName,isValue=false,isSelect=false){
    const selectElement =document.querySelector(`${isSelect ?'select':'input'}[name="${fieldName}"]`);
    if (selectElement) {
        if(isValue){ 
            return selectElement.value.trim();
        }  
        else return selectElement;
    } else {
        return null;
    }

}
const hideAlertMessages=function(){
    let successBlock = document.getElementById('successBlock')
    if(successBlock){
        // setTimeout(() => {
        //     alert.classList.add('fade-out');
        //     successBlock.style.display= 'none';
        // }, 5000);


        setTimeout(function() {
            successBlock.classList.add('fade-out'); 
            setTimeout(function() {
                successBlock.style.display = 'none';
            }, 500);  
        }, 5000);
    }
}
window.addEventListener('load',fn)