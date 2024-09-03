let init =function(){
    const value =parseInt(document.getElementById('creditPoint').innerText,10)
    if(value<=0) 
        disableFormField()
    else  inputValidation({'last_name':'last_name','first_name':'first_name','domain':'domain'})

    document.getElementById('CheckButon').addEventListener('click', function(e) {
        e.preventDefault();
        const value =parseInt(document.getElementById('creditPoint').innerText,10)
        if(value<=0){
            triggerSweetAlert('You cannot verify any emails as you currently have zero credits. To proceed with email verification, you need to purchase credits. Do you want to proceed?')
        }else{
            e.preventDefault()
            validateForm({'last_name':'last_name','first_name':'first_name','domain':'domain'});
            const hasErrors =document.querySelectorAll('.validation-error').length>0
            if(!hasErrors){
                 triggerSweetAlert('Are you sure to want to check the details?',true)
                // const form = document.getElementById('signinForm');
                // form.submit();
            }
        }
    });
}


function inputValidation(attrName){
    getFormValue(attrName.first_name).addEventListener('input',debouncing((event)=>{
        validateField(event.target, document.getElementById('fNameError'),validateName)
    },100))

    getFormValue(attrName.last_name).addEventListener('input',debouncing((event)=>{
        validateField(event.target, document.getElementById('lNameError'),validateName)
    },100))
    getFormValue(attrName.domain).addEventListener('input',debouncing((event)=>{
        validateField(event.target, document.getElementById('domainError'),validateName);
    },100))
}
function debouncing(func,delay){
    let timeoutId 
    return function(...args){
        clearTimeout(timeoutId)
        timeoutId = setTimeout(()=>func(...args),delay)
    }


}
function validateForm(attrName){
    validateField(getFormValue(attrName.first_name), document.getElementById('fNameError'), validateName);
    validateField(getFormValue(attrName.last_name), document.getElementById('lNameError'),validateName);
    validateField(getFormValue(attrName.domain), document.getElementById('domainError'),validateName);
    
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

function validateName(value){
    return value=='' ? '*This Field is required.':'';
}

function  triggerSweetAlert(text,isForm=false){
        Swal.fire({
        title: text,
        showCancelButton: true,
        cancelButtonText:'No',
        confirmButtonText: "Yes", 
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if(!isForm)
            disableFormField()
        if (result.isConfirmed) {
            console.log('SDDD');
            
            if(isForm){
                const form = document.getElementById('signinForm');
                disableFormField() 
                document.getElementById('analImage').style.display   = 'none';
                document.getElementById('AnalLoader').style.visibility='visible'; 
                form.submit();
            }
            // window.location.href='/'
        }
    });
}

function disableFormField(){
    let attrName= {'last_name':'last_name','first_name':'first_name','domain':'domain'};
    getFormValue(attrName.first_name).setAttribute('disabled',true);
    getFormValue(attrName.first_name).classList.remove('hover-border')
    getFormValue(attrName.last_name).setAttribute('disabled',true);
    getFormValue(attrName.last_name).classList.remove('hover-border')
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

document.addEventListener('DOMContentLoaded',init)