let fn= ()=>{
    hideAlertMessages();
    if(window.location.pathname !='/signin'){
        signupFormValidation();    
    }else{
        signinFormValidation();
    }


}

function validateField(ipElement,errorElement,validateFunction,isSignFormValidation=false){
    const value               = ipElement.value.trim();
    const errorMessage        =  (validateFunction=='validatePassword')? validateFunction(value,isSignFormValidation): validateFunction(value);
    // const errorMessage        = validateFunction(value);
    errorElement.textContent  = errorMessage
    if(errorMessage) ipElement.classList.add('validation-error')
    else ipElement.classList.remove('validation-error')

}

function debouncing(func,delay){
    let timeoutId 
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
    emailErrorMes = (value==='')?'*This Field is required.':'' || !emailRe.test(value) ? '*Invalid email address.':'';
    return emailErrorMes
}

function validatePassword(value,isSignin=false){
    let passErrorMess = '';
    let pattern         = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if(isSignin)  passErrorMess     = (value === '') ? '*This Field is required.' : '';
    else passErrorMess= (value === '') ? '*This Field is required.' : '' || value.length<8? `*Must be at least 8 characters.` : '' || !pattern.test(value) ? '*Password must include at least one lowercase letter, one uppercase letter, one number, and one special character.':'';
    return passErrorMess;
}

const validateForm = function(attrName,isSignInValidation=false){
    if(!isSignInValidation){
        validateField(getSignupFormValue(attrName.no_of_email_verification,false,true), document.getElementById('NES'), validateEmailNumber);
        validateField(getSignupFormValue(attrName.name), document.getElementById('nameError'), validateName);    
    }
    validateField(getSignupFormValue(attrName.email), document.getElementById('emailError'), validateEmail);
    validateField(getSignupFormValue(attrName.password), document.getElementById('passwordError'),(value)=> validatePassword(value,isSignInValidation));
    
}


const inputValidation = function(attrName,isSignin=false){
if(!isSignin){
    getSignupFormValue(attrName.no_of_email_verification,false,true).addEventListener('change',((event)=>{
        validateField(event.target, document.getElementById('NES'),validateEmailNumber)
    }))
    getSignupFormValue(attrName.name).addEventListener('input',debouncing((event)=>{
        validateField(event.target, document.getElementById('nameError'),validateName)
    },100))
}
    getSignupFormValue(attrName.email).addEventListener('input',debouncing((event)=>{
        validateField(event.target, document.getElementById('emailError'),validateEmail)
    },100))
    getSignupFormValue(attrName.password).addEventListener('input',debouncing((event)=>{
        validateField(event.target, document.getElementById('passwordError'),(value)=> validatePassword(value,isSignin));
    },100))
}


const signupFormValidation = function(){
    // input validation
    inputValidation({'no_of_email_verification':'no_of_email_verification','name':'name','email':'email','password':'password'})
    let signUpBtn = document.getElementById('signUpBtn')
    signUpBtn.addEventListener('click',(event)=>{
        event.preventDefault()
        validateForm({'no_of_email_verification':'no_of_email_verification','name':'name','email':'email','password':'password'});
        const hasErrors =document.querySelectorAll('.validation-error').length>0
        if(!hasErrors){
            const form = document.getElementById('signUpForm');
            form.submit();
        }
    })
}
const signinFormValidation = function(){

    // input validation
    inputValidation({'email':'emailL','password':'passwordL'},true)
    let signBtn = document.getElementById('signIn')
    signBtn.addEventListener('click',(event)=>{
        event.preventDefault()
        validateForm({'email':'emailL','password':'passwordL'},true);
        const hasErrors =document.querySelectorAll('.validation-error').length>0
        if(!hasErrors){
            const form = document.getElementById('signinForm');
            form.submit();
        }
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
    let alertBlock = document.getElementById('alertBlock')
    if(successBlock){  
        setTimeout(function() {
            successBlock.classList.add('fade-out'); 
            setTimeout(function() {
                successBlock.style.display = 'none';
            }, 500);  
        }, 5000);
    }

    if(alertBlock){  
        setTimeout(function() {
            alertBlock.classList.add('fade-out'); 
            setTimeout(function() {
                alertBlock.style.display = 'none';
            }, 500);  
        }, 5000);
    }
}
window.addEventListener('load',fn)