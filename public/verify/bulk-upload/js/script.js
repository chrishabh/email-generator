function updateProgress(percentage) {
    // Validate the percentage value
    if (percentage < 0 || percentage > 100) {
        console.error('Percentage must be between 0 and 100');
        return;
    }

    // Convert percentage to degrees (360 degrees)
    const progressValue = percentage * 3.6;
    
    // Update the CSS variable for progress
    document.getElementById('progress-mask-full').style.setProperty('--progress', `${progressValue}deg`);
    document.getElementById('percentage-text').textContent = `${percentage}%`;
}



// $('.uploader').filepond({
//     allowMultiple: true,
// });


// $(function(){
  
//     // First register any plugins
//     FilePond.registerPlugin(
//         FilePondPluginImagePreview,
//         FilePondPluginImageExifOrientation,
//         FilePondPluginFileValidateSize,
//         FilePondPluginImageEdit
//       );
      
//     // // Turn input element into a pond
//     $('.my-pond').filepond();

//     // // Set allowMultiple property to true
//     // $('.my-pond').filepond('allowMultiple', true);
  
//     // // Listen for addfile event
//     $('.my-pond').on('FilePond:addfile', function(e) {
//         console.log('file added event', e);
//     });

//     // // Manually add a file using the addfile method
//     // $('.my-pond').first().filepond('addFile', 'index.html').then(function(file){
//     //   console.log('file added', file);
//     // });
  
//   });

// $('.uploader').filepond({
//     allowRevert: false,
//     acceptedFileTypes: ['text/csv', 'application/vnd.ms-excel','text/plain','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
//     server: {
//         process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
//             // fieldName is the name of the input field
//             // file is the actual file object to send
//             const formData = new FormData();
//             formData.append(fieldName, file, file.name);

//             const request = new XMLHttpRequest();
//             request.open('POST', '/devcom/uploader/');

//             // Should call the progress method to update the progress to 100% before calling load
//             // Setting computable to false switches the loading indicator to infinite mode
//             request.upload.onprogress = (e) => {
//                 progress(e.lengthComputable, e.loaded, e.total);
//             };

//             // Should call the load method when done and pass the returned server file id
//             // this server file id is then used later on when reverting or restoring a file
//             // so your server knows which file to return without exposing that info to the client
//             request.onload = function () {
//                 if (request.status >= 200 && request.status < 300) {
//                     // the load method accepts either a string (id) or an object
//                     // console.log(request.response);
//                     load(request.responseText);

//                     $(".ver-list").prepend(request.response);
//                    // $( ".verif-item:first" ).click();

//                     setTimeout(function(){ $('.uploader').first().filepond('removeFile'); }, 2000);
//                     //

//                 } else {
//                     // Can call the error method if something is wrong, should exit after
//                     error('oh no');
//                 }
//             };

//             request.send(formData);

//             // Should expose an abort method so the request can be cancelled
//             return {
//                 abort: () => {
//                     // This function is entered if the user has tapped the cancel button
//                     request.abort();

//                     // Let FilePond know the request has been cancelled
//                     abort();
//                 },
//             };
//         },
//     },
// });

// $('#uploadbtn').on('click', function() {
//     $('.filepond--browser').trigger('click');
// });







$(document).ready(function() {
    // Register FilePond plugins
    $('#alertBox').hide();
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize,
        FilePondPluginImageEdit
    );

    // Turn input element into a pond
    $('.my-pond').filepond({
        server: {
            process: {
                url: '/upload',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                onload: (response) => {
                    try {
                        const responseObj = JSON.parse(response) 
                        if(responseObj.error){
                            console.log((responseObj.error));
                            showError(responseObj.error);
                        }else{
                            $('#alertContent').text(responseObj.success); 
                                $('#alertBox').css({
                                    'background-color': '#28a745', // Green background color
                                    'color': '#fff', // White text color
                                    'border-color': '#28a745' // Green border color
                                }).show();
                            setTimeout(function() {
                                $('#alertBox').hide();  
                            }, 10000);
                             
                        }
                             
                    } catch (error) {
                        console.log((error));
                        showError(error);
                    }
                    // console.log('File uploaded successfully:', response);
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
        // allowMultiple: true,
        labelIdle: 'Drag & Drop your files or <span class="filepond--label-action">Browse</span>',
        
    });

    // Trigger file input click when custom button is clicked
    $('#custom-button').on('click', function() {
        $('.my-pond').click();
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