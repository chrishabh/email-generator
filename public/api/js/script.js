

function copyToClipboard(){
    const clipboard = new ClipboardJS('.copy-clipboard');

    // Add success and error listeners
    clipboard.on('success', function(e) {
        console.log('Copied:', e.text);
        const notyf = new Notyf({
            duration: 3000, // Toast duration in milliseconds
            position: {
              x: 'right',
              y: 'bottom',
            },
            types: [
              {
                type: 'success',
                background: 'green',
                icon: false,
              },
            ],
            dismissible: true,
          });

          notyf.success('Key copied to clipboard');
    });

    clipboard.on('error', function(e) {
        console.error('Copy failed:', e.action);
    });

 
}

function init(){ 

  window.tooltipInstances = initializeMaterializeComponents(['deletedToolTip','editToolTip','regenerateToolTip','copybutton'], M.Tooltip);
  window.modalInstances   = initializeMaterializeComponents(['modal1'],M.Modal)

   function initializeMaterializeComponents(modalIds,MaterializeComponent){
    const instances ={}
    modalIds.forEach ((id)=>{
      const elem = document.getElementById(id)
      if(elem){
        instances[id] = MaterializeComponent.init(elem);
      }
    }) 
    return instances;
  }
  
  window.errorNotyf = new Notyf({
    duration: 8000, // Toast duration in milliseconds
    position: {
      x: 'right',
      y: 'bottom',
    },
    types: [
      {
        // type: 'success',
        background: 'red',
        icon: false,
      },
    ],
    dismissible: true,
  });


  window.successNotyf = new Notyf({
    duration: 5000, // Toast duration in milliseconds
    position: {
      x: 'right',
      y: 'bottom',
    },
    types: [
      {
        type: 'success',
        background: 'green',
        icon: false,
      },
    ],
    dismissible: true,
  });

  window.deleteKey =async (key)=>{
    try {
      const response = await axios.post(`/customApi/${key}/destroy`,{key});
      const {success,message}  = response.data;
      if(!success) throw new Error(message)
        fetchApiKeys(true,'keys deleted!')
    } catch (error) {
      $('#preloader').fadeOut();
      console.error('Error creating API key:', error)
      errorNotyf.error(error.message)
    }
  } 

  window.regenerate = async(key)=>{
    try {
      $('#preloader').fadeIn();
      const response = await axios.post(`/customApi/${key}/regenerate`,{key});
      const {success,message,newKey}  = response.data;
      if(!success) throw new Error(message)
      fetchApiKeys(true,'key Regenerated!!')
    } catch (error) {
      $('#preloader').fadeOut(); 
      console.error('Error creating API key:', error)
      errorNotyf.error(error.message) 
    }
  }

  window.fetchApiKeys = (isApiForSaveKey=false,text='')=>{
    const apiKeysTableBody = document.getElementById("apiKeysTableBody"); 
    // $('#preloader').fadeIn();
    axios.get('/customApi/api-keys')
    .then(response => {
      const data = response.data;
      if(!data.success) throw new Error(response.error)
      apiKeysTableBody.innerHTML = '';
      data.keys.forEach(key => {
        const row = `
          <tr>
            <td>${key.name}</td>
            <td>
              <span id="key-${key.id}">${key.key}</span>
              <i id="copybutton" class="fa-regular fa-copy copy-clipboard  pl-2" style="font-size: 18px;"data-clipboard-text="${key.key}" onclick="copyToClipboard()"></i>
            </td>
            <td> 
              <span class="inline-flex items-center rounded-md bg-${key.status =='Enabled' ? 'green' : 'red' }-100 px-2 py-1 text-xs font-medium text-${ key.status=='Enabled' ? 'green' : 'red' }-700 ring-1 ring-inset ring-${key.status=='Enabled' ? 'green' : 'red' }}-600/20 ">${ key.status }</span>
            </td>
            <td>${key.created_at}</td>
            <td>
              <i id="editToolTip" class="fas fa-pencil pr-2 hover:cursor-pointer" data-position="bottom" data-tooltip="edit" style="font-size:14px;"></i>
              <i id="regenerateToolTip" onclick="regenerate(${key.id})" class="fas fa-arrows-rotate pr-2 hover:cursor-pointer" data-position="bottom" data-tooltip="regenerate"  style="font-size: 14px;font-weight:600"></i>
              <i  id="deletedToolTip" onclick="triggerSweetAlert('Are you sure you want to delete this?',${key.id})"  class="fa fa-trash d-inline hover:cursor-pointer  "  data-position="bottom" data-tooltip="deleted" style="font-size: 14px;"></i>
            </td>
          </tr>`;
          apiKeysTableBody.insertAdjacentHTML('beforeend', row);
        });
  
        $('#preloader').fadeOut();
        if(isApiForSaveKey){
          successNotyf.success(text)
        }
  
      }).catch(error => console.error('Error fetching API keys:', error));
  
  }
    
  window.saveApiKey = (e) =>{
    e.preventDefault();
    var apiKey       = document.getElementById('apiKey');
    const errorIcon  = document.getElementById("error-icon");
    var errorMessage = document.getElementById('error-message');
  
    if(apiKey.value.trim() === ""){
      apiKey.classList.add("error");
      errorIcon.classList.remove("hidden");
      errorMessage.classList.remove("hidden");
    } else {
      apiKey.classList.remove("error");
      errorIcon.classList.add("hidden");
      errorMessage.classList.add("hidden");
      let name =apiKey.value
      $('#preloader').fadeIn();
      axios.post('/customApi/store', {name})
      .then((response) =>{
        const data = response.data
        modalInstances['modal1'].close()
        if(data.success){
          fetchApiKeys(true,'key generated!!')
        }else{
          $('#preloader').fadeOut();
          throw new Error(data.error)
        }
      } )
      .catch(error => {
        console.error('Error creating API key:', error)
        errorNotyf.error(error.message)
        modalInstances['modal1'].open()
      })
    }
  }

  window.hideError = ()=>{
    const input = document.getElementById("apiKey");
    const errorIcon = document.getElementById("error-icon");
    const errorMessage = document.getElementById("error-message");
    input.classList.remove("error");
    errorIcon.classList.add("hidden");
    errorMessage.classList.add("hidden");
  }

  window.validateInput = ()=>{
    const input = document.getElementById("apiKey");
    const errorIcon = document.getElementById("error-icon");
    const errorMessage = document.getElementById("error-message");

    if (input.value.trim() === "") {
      input.classList.add("error");
      errorIcon.classList.remove("hidden");
      errorMessage.classList.remove("hidden");
    } else {
      input.classList.remove("error");
      errorIcon.classList.add("hidden");
      errorMessage.classList.add("hidden");
    }
  }

  window.validationAddOrRemove = (apiKey,errorMessage) =>{
    if (apiKey.value.trim() === '') {
      apiKey.classList.add('border-red-500');
      errorMessage.classList.remove('hidden');
    } else {
      apiKey.classList.remove('border-red-500');
      errorMessage.classList.add('hidden');
    }
  }


  window.triggerSweetAlert = (text,key)=>{
    Swal.fire({
      title: text,
      customClass: {
        popup: 'my-custom-popup'
      },
      showCancelButton: true,
      cancelButtonText:'No',
      confirmButtonText: "Yes", 
  }).then((result) => {
      if (result.isConfirmed) {
        $('#preloader').fadeIn();
        deleteKey(key);
      }
  });
  }
}


document.addEventListener('DOMContentLoaded',init)


