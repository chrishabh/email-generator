let init =function(){
    const value =parseInt(document.getElementById('creditPoint').innerText,10)
    const url = window.location.pathname.split('/')[1];
    if(value<=0) {
        disableFormField(url)
    }  
    else {
        if(url==='lead-finder') inputValidation({'last_name':'last_name','first_name':'first_name','domain':'domain'},url)
        else inputValidation({'domain':'domain'},url)
    }
    document.getElementById('CheckButon').addEventListener('click', function(e) {
        e.preventDefault();
        const value =parseInt(document.getElementById('creditPoint').innerText,10)
        if(value<=0){
            triggerSweetAlert('You cannot verify any emails as you currently have zero credits. To proceed with email verification, you need to purchase credits. Do you want to proceed?')
        }else{
            e.preventDefault()
            if(url=='lead-finder') validateForm({'last_name':'last_name','first_name':'first_name','domain':'domain'},url)
            else validateForm({'domain':'domain'},url);

            const hasErrors =document.querySelectorAll('.validation-error').length>0
            if(!hasErrors){
               let msg =  (url==='lead-finder') ? 'Are you sure to want to find the email address?':'Are you sure to want to check the details?'
                 triggerSweetAlert(msg,true,url)
                // const form = document.getElementById('signinForm');
                // form.submit();
            }
        }
    });
}


function inputValidation(attrName ,url){
    console.log(url);
    if(url=='lead-finder'){
        getFormValue(attrName.first_name).addEventListener('input',debouncing((event)=>{
            validateField(event.target, document.getElementById('fNameError'),validateName)
        },100))
    
        getFormValue(attrName.last_name).addEventListener('input',debouncing((event)=>{
            validateField(event.target, document.getElementById('lNameError'),validateName)
        },100))

        getFormValue(attrName.domain).addEventListener('input',debouncing((event)=>{
            validateField(event.target, document.getElementById('domainError'),validateRequired);
        },100))
    }else{
        getFormValue(attrName.domain).addEventListener('input',debouncing((event)=>{
            validateField(event.target, document.getElementById('domainError'),validateEmail);
        },100))
        
    }
}
function debouncing(func,delay){
    let timeoutId 
    return function(...args){
        clearTimeout(timeoutId)
        timeoutId = setTimeout(()=>func(...args),delay)
    }


}
function validateForm(attrName,url){
    if(url==='lead-finder'){
        validateField(getFormValue(attrName.first_name), document.getElementById('fNameError'), validateName);
        validateField(getFormValue(attrName.last_name), document.getElementById('lNameError'),validateName); 
        validateField(getFormValue(attrName.domain), document.getElementById('domainError'),validateRequired);
    }else{  
        validateField(getFormValue(attrName.domain), document.getElementById('domainError'),validateEmail);
    }
    
}

function validateField(ipElement,errorElement,validateFunction){
    const value               = ipElement.value.trim();
    const errorMessage        = validateFunction(value); 
    errorElement.textContent  = errorMessage
    if(errorMessage) {
        ipElement.classList.add('validation-block-error')
        errorElement.classList.add('validation-span-error')
    } 
    else {
        ipElement.classList.remove('validation-block-error')
        errorElement.classList.remove('validation-span-error')
    } 
}

function validateRequired(value){ 
    let emailErrorMes = '';
    emailErrorMes = (value==='')?'*This Field is required.':'';
    return emailErrorMes
}

function validateName(value){
    let emailErrorMes = '';
    emailErrorMes = (value==='')?'*This Field is required.':'' || value.length<3 ? '*Must be at least 3 characters.':'';
    return emailErrorMes
}

function validateEmail(value){
    // return value=='' ? '*This Field is required.':'';
    const emailRe     = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let emailErrorMes = '';
    emailErrorMes = (value==='')?'*This Field is required.':'' || !emailRe.test(value) ? '*Invalid email address.':'';
    return emailErrorMes
}

function  triggerSweetAlert(text,isForm=false,url=''){
        Swal.fire({
        title: text,
        showCancelButton: true,
        cancelButtonText:'No',
        confirmButtonText: "Yes", 
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        // if(!isForm)
        //     disableFormField()
        if (result.isConfirmed) {
                       
            if(isForm){
                if(url=='lead-finder'){
                    const form = document.getElementById('lead-form');
                    form.submit(); 

                }else{
                    const form = document.getElementById('signinForm');
                    document.getElementById('analImage').style.display   = 'none';
                    document.getElementById('AnalLoader').style.visibility='visible'; 
                    form.submit();
                }
                
                disableFormField(url)
            }
            // window.location.href='/'
        }
    });
}

function disableFormField(url){
    // let attrName= {'last_name':'last_name','first_name':'first_name','domain':'domain'};
    let attrName= (url==='lead-finder')? {'last_name':'last_name','first_name':'first_name','domain':'domain'} : {'domain':'domain'};
     
    if((url==='lead-finder')){
        getFormValue(attrName.first_name).setAttribute('disabled',true);
        getFormValue(attrName.first_name).classList.remove('hover-border')
        getFormValue(attrName.last_name).setAttribute('disabled',true);
        getFormValue(attrName.last_name).classList.remove('hover-border')
    }
     
    getFormValue(attrName.domain).setAttribute('disabled',true);
    getFormValue(attrName.domain).classList.remove('hover-border')
    document.getElementById('CheckButon').setAttribute('disabled',true);
    Object.assign(document.getElementById('CheckButon').style,{
        border: '1px solid #999999',
        backgroundColor: '#cccccc',
        cursor: 'not-allowed',
    })

}


const getFormValue=function(fieldName,isValue=false){
    const selectElement =document.querySelector(`input[name="${fieldName}"]`);
    if (selectElement) {
        if(isValue){ 
            return selectElement.value.trim();
        }  
        else return selectElement;
    } else {
        return null;
    }

}



function copyContent(event){
    const copyText = document.getElementById("copiedEmail");
    const copyButton = document.getElementById("copyButton");
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(copyText.value).then(() => {
        // Change button text and style
        copyButton.textContent = "Copied!";
        copyButton.classList.add("copied");

        // Revert button text after a delay
        setTimeout(() => {
            copyButton.textContent = "COPY";
            copyButton.classList.remove("copied");
        }, 2000); // 2-second delay before reverting
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}

document.addEventListener('DOMContentLoaded',init)