function updateProgress(percentage) {
    // Validate the percentage value
    if (percentage < 0 || percentage > 100) {
        console.error('Percentage must be between 0 and 100');
        return;
    }

    // Convert percentage to degrees (360 degrees)
    const progressValue = percentage * 3.6;
    
    // Update the CSS variable for progress
    let maskFull=document.getElementById('progress-mask-full');
    maskFull.style.setProperty('--progress', `${progressValue}deg`);

    let progressText = document.getElementById('percentage-text');
    progressText.textContent = `${percentage}%`;
}
 
 
function fetchFileStatus(){
    // $.ajax({
    //     url: '/check-file-status',
    //     method: 'GET',
    //     success: function(response) {
    //         console.log(response)
    //     },
    //     error: function(xhr, status, error) {
    //         console.error('Error fetching file status:', error);
    //     }
    // });
}






$(document).ready(function() {
    // Register FilePond plugins
    $('#alertBox').hide();
    // Execute the function every 1 minute
    disableDownloadButton();

    // Initial fetch on page load
    fetchFileStatus();
    // updateProgress(50);
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize,
        FilePondPluginImageEdit
    );

    // Turn input element into a pond
    // $('.my-pond').filepond({
    //     server: {
    //         process: {
    //             url: '/upload',
    //             method: 'POST',
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             onload: (response) => {
    //                 try {
    //                     const responseObj = JSON.parse(response) 
    //                     if(responseObj.error){
    //                         console.log((responseObj.error));
    //                         showError(responseObj.error);
    //                     }else{
    //                         $('#alertContent').text(responseObj.success); 
    //                             $('#alertBox').css({
    //                                 'background-color': '#28a745', // Green background color
    //                                 'color': '#fff', // White text color
    //                                 'border-color': '#28a745' // Green border color
    //                             }).show();
    //                         setTimeout(function() {
    //                             $('#alertBox').hide();  
    //                         }, 10000);
                             
    //                     }
                             
    //                 } catch (error) {
    //                     console.log((error));
    //                     showError(error);
    //                 }
    //                 // console.log('File uploaded successfully:', response);
    //             },
    //             onerror: (response) => {
    //                 showError('Error: ' + (response.error || 'An unknown error occurred'));
    //                 console.error('Error uploading file:', response);
    //             }
    //         },
    //         load: {
    //             url: '/load',
    //             method: 'GET',
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         }
    //     },
    //     // allowMultiple: true,
    //     labelIdle: 'Drag & Drop your files or <span class="filepond--label-action">Browse</span>',
        
    // });

    const pond = FilePond.create(document.querySelector('.my-pond'), {
        server: {
            process: {
                url: '/upload',
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                onload: (response) => {
                    try {
                        const responseObj = JSON.parse(response);
                        if (responseObj.error) {
                            console.log(responseObj.error);
                            showError(responseObj.error);
                        } else {
                            $('#alertContent').text(responseObj.success);
                            $('#alertBox').css({
                                'background-color': '#28a745',
                                'color': '#fff',
                                'border-color': '#28a745'
                            }).show();
                            setTimeout(function() {
                                $('#alertBox').hide();
                                window.location.reload();
                            }, 10000);
                        }
                    } catch (error) {
                        console.log(error);
                        showError(error);
                    }
                },
                onerror: (response) => {
                    showError('Error: ' + (response.error || 'An unknown error occurred'));
                    console.error('Error uploading file:', response);
                }
            },
            load: {
                url: '/load',
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }
        },
        labelIdle: 'Drag & Drop your files or <span class="filepond--label-action">Browse</span>'
    });
    const value =parseInt(document.getElementById('creditPoint').innerText,10)
    if(value<=0) {
        pond.setOptions({
            allowBrowse: false,
            allowDrop: false,
            allowPaste: false
        });
        $('#uploadbtn').prop('disabled', true);
    }

    // Trigger file input click when custom button is clicked
    $('#uploadbtn').on('click', function() {
        pond.browse();
    });


    let searchButton =document.getElementById('searchButton');
    if(searchButton)
        searchButton.addEventListener('click',searchFormData)
});


function searchFormData(event){
    event.preventDefault(); 
    const inputElem = document.querySelector('input[name="searchContent"]');
    const cancel = document.getElementById('crossImage');
    const inputElemValue = inputElem.value;

    if (inputElemValue != "") {
        cancel.style.display = 'block';
        inputElem.disabled = true;
        this.disabled = true;
        applyDisabledStyles(this);
        fetchRequest(inputElemValue);

    } else {
        cancel.style.display = 'none';
    }

}

function fetchRequest(inputElemValue,isReset=false){
    $('#preloader').fadeIn();
        // AJAX call 
    let body={
        searchContent:inputElemValue,
        ...(isReset && {isReset:true})
    }
    fetch("/bulk", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="search-csrf-token"]').getAttribute('content')
            },
            body:JSON.stringify(body)   
    })
    .then(response => response.json())
    .then(data => {
        // Populate the data inside the upload-file--list div
        const uploadFileListDiv = document.querySelector('.upload-file--list');
        uploadFileListDiv.innerHTML = data.html;  
        $('#preloader').fadeOut();
    })
    .catch(error => {
        $('#preloader').fadeOut();
        console.error('Error:', error);
    })
}
function cancelFilter(elem,event){
    event.preventDefault()
    const inputElem      = document.querySelector(`input[name="searchContent"]`)
    const searchButton   =  document.getElementById('searchButton')
    const inputElemValue = inputElem.value 
    if(inputElemValue!=""){
        inputElem.value=''
        inputElem.disabled=false
        fetchRequest('',true);
        elem.style.display='none'
        searchButton.disabled=false
        resetStyles(searchButton) 
    }
}



// Function to apply disabled styles
function applyDisabledStyles(button) {
     
    button.classList.add('filter-search-disable-button')
}

// Function to reset to initial styles
function resetStyles(button) {
    button.classList.remove('filter-search-disable-button')
}

function showError(message) {
    $('#alertContent').text(message); 
    $('#alertBox').css({
        'background-color': '#dc3545', // Green background color
        'color': '#fff', // White text color
        'border-color': '#dc3545' // Green border color
    }).show();
    setTimeout(function() {
        $('#alertBox').hide();  
        window.location.reload();
    }, 10000);
                             
}


function downloadCsvFile(event, fileid,totalValidEmail,c) {
    event.preventDefault();
    if(totalValidEmail==0){ 
        c.style.cursor = 'not-allowed'; 
        c.style.filter = 'grayscale(100%)';
        c.style.opacity = '0.5';
        return;
    }
    fetch('/export-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ fileId: fileid }) // Send the fileId as JSON
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        filename = response.headers.get('filename');
        return response.blob(); // Expect the response to be a Blob
    })
    .then(blob => {
        if (blob) {
            // Create a link element to trigger the download
            const a = document.createElement('a');
            const url = window.URL.createObjectURL(blob);
            a.href = url;
            a.download = filename; 
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url); // Clean up the URL object
        } else {
            console.error('Response is not a Blob:', blob);
        }
    })
    .catch(error => {
        console.error('An error occurred:', error.message);
    });

}


function simulateProgress() {
    // let value = 0;
    let progressValue = 0;
    const interval = setInterval(() => {
        if (progressValue < 95) { // Simulate progress to 95%
            progressValue += Math.ceil(Math.random() * 5); // Increment progress randomly
            updateProgress(progressValue);
        } else {
            clearInterval(interval); // Stop progress simulation
        }
    }, 500);
    
    return interval;
}

function startVerification(event,element,fileId){
    event.preventDefault()
    if(element.getAttribute('data-disabled')=='true') return;

    element.setAttribute('data-disabled',true);
    element.textContent           = 'Verifying...';
    element.style.backgroundColor = '#d1d1d1';
    element.style.color           = '#ffffff';
    element.style.cursor          = 'not-allowed'; 
    // const progressInterval        = simulateProgress();

    // Start polling the verification status immediately without waiting for the API response
    const intervalId  = pollVerificationStatus(fileId, element); // This is called right away to start polling in parallel

    fetch('/start-verification',{
        method:'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="verification-csrf-token"]').attr('content'), 
        },
        body:JSON.stringify({fileId}) 
    }).then(response=>{
        if(!response.ok) throw new Error('Network response was not ok ' + response.statusText)
        return response.json(); 
             
    }).then(data=>{
        if(data.success==false){
            clearInterval(intervalId); 
            throw new Error('Verification failed:'+ data.message);
        }
        // if(data.data[0].verificationStatus=='verified'){
        //     // let divElement = document.getElementById(`list_${fileId}`)
        //     // clearInterval(progressInterval);
        //     // updateProgress(100);
        //     window.location.reload(); 

            
        // }else{
        //     console.log(data.data[0]);   
        // }

        // Start polling the verification status immediately after triggering the start-verification API
        console.log("Verification started:", data);
        // We don't need to call pollVerificationStatus() here again since it started earlier
    }).catch(error=>{
        console.error('Error starting verification:',error.message);
        resetButton(element);
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
    })
     
}
function pollVerificationStatus(fileId, element) {
    const pollingInterval = 30000; // Poll every 2 seconds
    const intervalId = setInterval(() => {
        fetch('/check-verification-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="verification-csrf-token"]').attr('content'),
            },
            body: JSON.stringify({ fileId })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok ' + response.statusText);
            return response.json();
        })
        .then(data => {
            
            if (!data.success) {
                // console.error('Verification failed:', data.message);
                // clearInterval(intervalId); // Stop polling on error
                // resetButton(element);
                // Swal.fire({
                //     icon: 'error',
                //     title: 'Verification Error',
                //     text: data.message || 'Verification failed!',
                // });
                throw new Error('Verification failed:', data.message);
                // return;
            }
            const totalEmails = data.data[0].total_emails;
            const verifiedEmails = data.data[0].total_verified_emails;

            // Update progress bar
            updateProgressBar(verifiedEmails, totalEmails);

            // If all emails are verified, stop polling
            if (verifiedEmails >= totalEmails) {
                clearInterval(intervalId);
                window.location.reload(); // Optionally reload the page when done
            }
        })
        .catch(error => {
            console.error('Error checking verification status:', error.message);
            clearInterval(intervalId); // Stop polling on error
            resetButton(element);
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
        });
    }, pollingInterval);

    return intervalId;
}
// Function to update progress bar
function updateProgressBar(verifiedEmails, totalEmails) {
    const progressBar        = document.getElementById('progress-bar');
    const progressText       = document.getElementById('progress-text'); 
    const progressPercent    = (verifiedEmails / totalEmails) * 100;
    progressBar.style.width  = progressPercent + '%';
    progressText.textContent = `${verifiedEmails} / ${totalEmails} emails verified`;
}

// Function to reset the button in case of error
function resetButton(element) {
    element.setAttribute('data-disabled', false);
    element.textContent = 'Start Verification'; // Reset button text
    element.style.backgroundColor = ''; // Reset background color
    element.style.color = ''; // Reset text color
    element.style.cursor = ''; // Reset cursor
    

}


function disableDownloadButton(){
    const icons = document.querySelectorAll('.download-icon');
        icons.forEach(icon => {
            const dataAttributeValue = icon.getAttribute('data-valid');
            if(dataAttributeValue==0){
                icon.style.cursor = 'not-allowed'; 
                icon.style.filter = 'grayscale(100%)';
                icon.style.opacity = '0.5'; 
            }
          });
         
}