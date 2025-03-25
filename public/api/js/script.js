

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
      const response = await axios.post(`/Api/${key}/destroy`,{key});
      const {success,message}  = response.data;
      if(!success) throw new Error(message)
        fetchData('api-keys',1,'API Key Deleted Successfully!')
    } catch (error) {
      $('#preloader').fadeOut();
      console.error('Error creating API key:', error)
      errorNotyf.error(error.message)
    }
  } 

  window.regenerate = async(key)=>{
    try {
      $('#preloader').fadeIn();
      const response = await axios.post(`/Api/${key}/regenerate`,{key});
      const {success,message,newKey}  = response.data;
      if(!success) throw new Error(message)
      fetchData('api-keys',1,'API Key Regenerated Successfully!')
    } catch (error) {
      $('#preloader').fadeOut(); 
      console.error('Error creating API key:', error)
      errorNotyf.error(error.message) 
    }
  }

  window.fetchApiKeys = (isApiForSaveKey=false,text='')=>{
    const apiKeysTableBody = document.getElementById("apiKeysTableBody"); 
    // $('#preloader').fadeIn();
    axios.get('/Api/api-keys')
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
              <i id="copybutton" class="fa-regular fa-copy copy-clipboard  pl-2" style="font-size: 15px;"data-clipboard-text="${key.key}" onclick="copyToClipboard()"></i>
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
      axios.post('/Api/store', {name})
      .then((response) =>{
        const data = response.data
        modalInstances['modal1'].close()
        if(data.success){
          fetchData('api-keys',1,'API Key Generated Successfully!') 
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



  window.fetchPage = async(pageNumber)=>{
    try {
      await fetchData('api-keys',pageNumber)
    } catch (error) {
      console.error('Error fetching API keys:', error) 
      successNotyf.error(error.message)
    } 
  }
}


document.addEventListener('DOMContentLoaded',()=>{
  init()
  fetchData('api-keys',1)
}) 


const fetchData= async(url,page,message=null)=>{ 
  try { 
    $('#preloader').fadeIn();
    const response = await axios.get(`/Api/${url}?page=${page}`) 
    const data     = await response.data;
    if(!data.success) throw new Error(response.error)
    if(data.success) {
      const html = appendHtml(data);
      const htmlsection =document.getElementById('tableRenderSection')
      htmlsection.innerHTML=html
    }
    $('#preloader').fadeOut();
    if(message){
      successNotyf.success(message)
    }
  } catch (error) {
    console.error('Error fetching API keys:', error)
    $('#preloader').fadeOut();
    successNotyf.error(error.message)
  }
  
}

const appendHtml = (data) =>{ 

  let html='';
  if(!data.keys.data || data.keys.data.length === 0) {
    html=`<p class="text-center text-xl font-500">No API keys found.</p>`;
    return html;
  }
  else {
    html= `<table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Key</th>
        <th>Status</th>
        <th>Created</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="apiKeysTableBody">`;
    data.keys.data.forEach(key => {
      html+= `
        <tr>
          <td>${key.name}</td>
          <td>
            <span id="key-${key.id}">${key.key}</span>
            <i id="copybutton" class="fa-regular fa-copy copy-clipboard  pl-2" style="font-size: 15px;"data-clipboard-text="${key.key}" onclick="copyToClipboard()"></i>
          </td>
          <td> 
            <span class="inline-flex items-center rounded-md bg-${key.status =='Enabled' ? 'green' : 'red' }-100 px-2 py-1 text-xs font-medium text-${ key.status=='Enabled' ? 'green' : 'red' }-700 ring-1 ring-inset ring-${key.status=='Enabled' ? 'green' : 'red' }}-600/20 ">${ key.status }</span>
          </td>
          <td>${key.created_at}</td>
          <td>
            <i id="regenerateToolTip" onclick="regenerate(${key.id})" class="fas fa-arrows-rotate pr-2 hover:cursor-pointer" data-position="bottom" data-tooltip="regenerate"  style="font-size: 14px;font-weight:600"></i>
            <i  id="deletedToolTip" onclick="triggerSweetAlert('Are you sure you want to delete this?',${key.id})"  class="fa fa-trash d-inline hover:cursor-pointer  "  data-position="bottom" data-tooltip="deleted" style="font-size: 14px;"></i>
          </td>
        </tr>`;
      });
    
    html+=`
    </tbody>
  </table>`;
  let totalPages = Math.ceil(data.keys.total / data.keys.perPage);
  html += `<div class="pagination-controls">`;
  html += generatePagination(data.keys.currentPage, totalPages);
  html += `</div></div>`; 
  }
  return html;
      
}

function generatePagination(currentPage, totalPages) {
  let paginationHtml = '';

  if (totalPages <= 1) return ''; // No pagination if only one page

  // Add the first page
  paginationHtml += `<button onclick="fetchPage(1)" class="${currentPage === 1 ? 'active' : ''}">1</button>`;

  // Add ellipsis if there's a gap after the first page
  if (currentPage > 3) {
      paginationHtml += `<span>...</span>`;
  }

  // Add pages around the current page
  for (let page = Math.max(2, currentPage - 2); page <= Math.min(totalPages - 1, currentPage + 2); page++) {
      paginationHtml += `<button onclick="fetchPage(${page})" class="${page === currentPage ? 'active' : ''}">${page}</button>`;
  }

  // Add ellipsis if there's a gap before the last page
  if (currentPage < totalPages - 2) {
      paginationHtml += `<span>...</span>`;
  }

  // Add the last page
  paginationHtml += `<button onclick="fetchPage(${totalPages})" class="${currentPage === totalPages ? 'active' : ''}">${totalPages}</button>`;

  return paginationHtml;
}
