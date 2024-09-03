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
});

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
    if(totalValidEmail<1){ 
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
        return response.blob(); // Expect the response to be a Blob
    })
    .then(blob => {
        if (blob) {
            // Create a link element to trigger the download
            const a = document.createElement('a');
            const url = window.URL.createObjectURL(blob);
            a.href = url;
            a.download = 'dobounce.csv'; 
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

function startVerification(event,fileId){
    event.preventDefault();
    const progressInterval = simulateProgress();
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
             
        if(data.data[0].verificationStatus=='verified'){
            // let divElement = document.getElementById(`list_${fileId}`)
            clearInterval(progressInterval);
            updateProgress(100);
            window.location.reload(); 
        }else{
            console.log(data.data[0]);   
        }
    }).catch(error=>{
        console.log('Error of verification',error)
    })
}



function disableDownloadButton(){
    const icons = document.querySelectorAll('.download-icon');
        icons.forEach(icon => {
            const dataAttributeValue = icon.getAttribute('data-valid');
            if(dataAttributeValue<1){
                icon.style.cursor = 'not-allowed'; 
                icon.style.filter = 'grayscale(100%)';
                icon.style.opacity = '0.5'; 
            }
          });
         
}