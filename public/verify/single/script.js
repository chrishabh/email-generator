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
    document.getElementById('CheckButon').addEventListener('click', async function(e) {
        e.preventDefault();
        const value =parseInt(document.getElementById('creditPoint').innerText,10)
        if(value<=0){
            triggerSweetAlert('You cannot verify any emails as you currently have zero credits. To proceed with email verification, you need to purchase credits. Do you want to proceed?')
        }else{
            e.preventDefault()
            if(url=='lead-finder') validateForm({'last_name':'last_name','first_name':'first_name','domain':'domain'},url)
            else validateForm({'domain':'domain'},url);

            const hasErrors =document.querySelectorAll('.validation-span-error').length>0
            if(!hasErrors){
               let msg =  (url==='lead-finder') ? 'Are you sure to want to find the email address?':'Are you sure to want to check the details?'
                //  triggerSweetAlert(msg,true,url)
                // const form = document.getElementById('signinForm');
                // form.submit();

                if(url=='lead-finder'){
                    // const fullScreenLoader = document.getElementById('loaderLeadFinder')
                    // fullScreenLoader.style.display = 'flex';
                    $('#preloader').fadeIn();
                    const form = document.getElementById('lead-form');
                    form.submit();
                    // await fetchApiResponse({'last_name':'last_name','first_name':'first_name','domain':'domain'});

                }else{
                    const form = document.getElementById('signinForm');
                    // Select all elements with the class 'correct-email'
                    let correctEmailElements = document.querySelectorAll('.correct-email');

                    // Loop through each element and remove it
                    correctEmailElements.forEach(element => {
                        element.remove();
                    });
                    let elem   = document.getElementById('analImage')
                    if(elem)
                        elem.style.display='none';
                    let form1 = document.getElementById('AnalLoader');
                    if(form1)
                        form1.style.display='block'; 
                    form.submit();
                }
                
                // disableFormField(url)
            }
        }
    });

    let data = document.querySelector('[data-table]');
    if(data && data!=null ){
        let tableData  = data.dataset.table;
        getVerificationEmailStatus(JSON.parse(tableData));   
    }


}

async function fetchApiResponse(attrName) {
    const body = {
        'first_name': getFormValue(attrName.first_name, true),
        'last_name': getFormValue(attrName.last_name, true),
        'domain': getFormValue(attrName.domain, true),
        'stopValidationCheckbox': getFormValue('stopValidationCheckbox', true)
    };

    const fullScreenLoader = document.getElementById('loaderLeadFinder');
    fullScreenLoader.style.display = 'flex'; // Show loader before the request starts

    try {
        const response = await fetch("/lead-finder", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="lead-csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(body)
        });

        const data = await response.json();
        if (data.result) {
            const isHtmlInserted = getHtmlStructure(data.result);
            if (isHtmlInserted) {
                await getEmailStatusVerification(); // Ensure this function is also async
            }
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        fullScreenLoader.style.display = 'none'; // Hide loader after request completion
    }
}


async function getEmailStatusVerification() {
    let data = document.querySelector('[data-table]').dataset.table;
    data = JSON.parse(data);

    if (data != null && data != '') {
        let fileId = data.id;
        let isValidationPause = data.isValidationPause;
        let body = { fileId, isValidationPause };

        // Loop over each email log and handle the requests sequentially
        for (const elem of data.lead_finder_p_c_email_logs) {
            body.emailId = elem.id;
            try {
                const response = await fetch("/email-verification", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(body)
                });

                // Parse response
                const responseData = await response.json();

                // If the result is true, log the data
                if (responseData.result) {
                    console.log(responseData);
                } else {
                    throw new Error('Verification failed');
                }
            } catch (error) {
                // Handle the error and stop the process
                console.error('Error:', error.messaage);
                break; // Stop further fetch requests if one fails
            }
        }
    }
}

async function getVerificationEmailStatus(data){
    if (data != null && data != '') {
        let fileId             = data.id;
        let isValidationPause  = data.isValidationPause;
        let body               = { fileId, isValidationPause };
        let dataAbort          = false;
        let status             =  '';

        // Loop over each email log and handle the requests sequentially
        for (const elem of data.lead_finder_p_c_email_logs) {
            body.emailId = id= elem.id;
            try {
                if(dataAbort && status =='valid'){
                    hideLoaderAndAppendHtml(id, 'aborted',dataAbort);
                }else{
                    const response = await fetch("/email-verification", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="table-content-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(body)
                    });
    
                    // Parse response
                    const responseData = await response.json();
    
                    // If the result is true, log the data
                    if(responseData){
                        if (responseData.result) {
                            const data  =  responseData.result;
                            dataAbort   =  data.isAbortAll;
                            status      =  data.status;
                            creditPoint =  data.creditPoint;
                            hideLoaderAndAppendHtml(id,status,dataAbort);
                            document.getElementById('creditPoint').innerHTML = creditPoint
                        } 
                        else if(responseData.error){
                            throw new Error(responseData.error);
                        }
                        else {
                            throw new Error('Verification failed');
                        }
                    }
                }
            } catch (error) {
                console.error('Error:', error.messaage);
                // Handle the error and stop the process
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message || 'Something went wrong!',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the window when OK is clicked
                        window.location.reload();
                    }
                });
                
                break; // Stop further fetch requests if one fails
            }
        }
    } 
}

function hideLoaderAndAppendHtml(id,status,dataAbort){
    let elment      =  document.getElementById(`emailId-${id}`)
    let btnElem      =  document.getElementById(`copyButton-${id}`)
    let badgeClass  = '';
    let badgeImgSrc = '';
    
    if(elment){
        if (status === 'invalid') {
            badgeClass = 'badge-danger';
            badgeImgSrc = 'invalid.svg';
            btnElem.setAttribute('disabled',true); 
            btnElem.classList.add('disable-btn')
        } else if (status === 'valid') {
            badgeClass = 'badge-success';
            badgeImgSrc = 'valid.svg';
        } else if (status === 'aborted') {
            badgeClass = 'badge-dark';
            badgeImgSrc = 'aborted.svg';
            btnElem.setAttribute('disabled',true);
            btnElem.classList.add('disable-btn')
        }
        let assetPath = document.querySelector('meta[name="asset-base-url"]').getAttribute('content');
        let imagePath =  `${assetPath}verify/single/image/${badgeImgSrc}`;
        let html =`
        <div class="badge ${badgeClass} lead-badges">
            <img class="no-content" src="${imagePath}" alt="no content">
            <span class="font-weight-normal text-uppercase">${status}</span>
        </div>`; 
        elment.innerHTML = html;
    }

}
function getHtmlStructure(data){

    let html =''; 
    let originalData =data;
    if(data && data!=null ){
        data = data.lead_finder_p_c_email_logs;
    }

    if(data && data.length){
        html += `
        <div class="second-lead-card single-card mt-4">
            <div class="row mx-0">
                <div class="col-md-12 col-sm-12">
                    <h2 class="lead-heading--requsted font-weight-bold">Result</h2>
                    <div class="table-wrapper">
                        <table class="table table-hover box-shadow-custom" data-table=${JSON.stringify(originalData)}>
                            <thead>
                                <tr class="bg-grad">
                                    <th scope="col">ROW</th>
                                    <th scope="col">EMAIL ADDRESS</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody class="table-bordered scrollable-tbody">
    `;
    // Loop through the data and build table rows
    data.forEach((item, index) => {
        // Determine the badge class and image based on status
        let badgeClass = '';
        let badgeImgSrc = '';
        if (item.status === 'invalid') {
            badgeClass = 'badge-danger';
            badgeImgSrc = 'invalid.svg';
        } else if (item.status === 'valid') {
            badgeClass = 'badge-success';
            badgeImgSrc = 'valid.svg';
        } else if (item.status === 'aborted') {
            badgeClass = 'badge-dark';
            badgeImgSrc = 'aborted.svg';
        }

        // Append the row HTML
        html += `
            <tr>
                <th scope="row">${index + 1}.</th>
                <td id="copiedEmail">${item.email}</td>
                <td id="emailId-${item.id}">
                    ${(item.status!=null)?
                        `<div class="badge ${badgeClass} lead-badges">
                            <img class="no-content" src="verify/single/image/${badgeImgSrc}" alt="no content">
                            <span class="font-weight-normal text-uppercase">${item.status}</span>
                        </div>` :
                        `<div id="spinner"></div> `
                    }
                </td>
                <td>
                    <button type="button" class="btn copy-btn" onclick="copyContent(this, '${item.email}')">COPY</button>
                </td>
            </tr>
        `;
    });

    // Close the table and other HTML elements
    html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `;
    }
    // Insert the generated HTML into the target container
    let isHtmlInserted =false
    if(html) isHtmlInserted =true;
    else isHtmlInserted =false
    document.getElementById('table-content-wrapper').innerHTML = html;
    return isHtmlInserted;
}

function inputValidation(attrName ,url){
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
                    const fullScreenLoader = document.getElementById('loaderLeadFinder')
                    fullScreenLoader.style.display = 'flex';
                    const form = document.getElementById('lead-form');
                    form.submit();

                }else{
                    const form = document.getElementById('signinForm');
                    // Select all elements with the class 'correct-email'
                    let correctEmailElements = document.querySelectorAll('.correct-email');

                    // Loop through each element and remove it
                    correctEmailElements.forEach(element => {
                        element.remove();
                    });
                    let elem   = document.getElementById('analImage')
                    if(elem)
                        elem.style.display='none';
                    let form1 = document.getElementById('AnalLoader');
                    if(form1)
                        form1.style.display='block'; 
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



function copyContent(elem,event,email){
 
    event.preventDefault();
    if(element.getAttribute('data-disabled')=='true') return;

    const tempTextarea          = document.createElement("textarea");
    tempTextarea.value          = email;
    tempTextarea.style.position = "absolute"; // Position it off-screen
    tempTextarea.style.left     = "-9999px"; // Make sure it's out of the visible area

    document.body.appendChild(tempTextarea); // Append to the document
    tempTextarea.select(); //
    // copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(email).then(() => {
        // Change button text and style
        elem.textContent = "Copied!";
        elem.classList.add("copied");

        // Revert button text after a delay
        setTimeout(() => {
            elem.textContent = "COPY";
            elem.classList.remove("copied");
        }, 2000); // 2-second delay before reverting
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}


 
document.addEventListener('DOMContentLoaded',init)