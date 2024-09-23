function init(){

}

async function renderHtml(event,elem){
    event.preventDefault();
    if(elem.id=='setting'){
        const data = await getSettingHtml('render-setting/','setting-page-token')
        console.log(data);
        renderSettingHtmlPage(data)

    }
}

function renderSettingHtmlPage(data){
    let settingDOM = document.getElementById('setting-section') 
    let html       = '';
    if(!data || !Array.isArray(data) || data.length === 0){

        html+='<h1>No Data Found </h1>';

    }
    else{
        html = `<h1 class="user-heading">Users Table</h1>
        <table class="table table-hover table-bordered">
            <thead class="table-head">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Available Credits</th>
                    <th scope="col">Reset Password</th>
                    <th scope="col">delete User Ac</th>
                </tr>
            </thead>
            <tbody>`;
            data.forEach((user, index) => {
               html+= `<tr>
                    <th scope="row">${index+1}</th>
                    <td>${user.name}</td>
                    <td> ${user.email}</td>
                    <td>${user.credits !== null ? user.credits + " credits" : 'No Credits'}</td>
                    <td class="text-center"><i class="fas fa-key fa-redo-alt"></i></td>
                    <td class="text-center"><i class="fa-solid fa-trash"></td>
                </tr>`;
            })
        html+=`</tbody>
            </table>`
    }
   // Set the innerHTML instead of textContent to render HTML
   settingDOM.innerHTML = html;
}

async function getSettingHtml(routeURL,tokenName){
    try {
        const response = await fetch(`/settings/${routeURL}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector(`meta[name="${tokenName}"]`).getAttribute('content')
            }
        });

        const data = await response.json();
        if (data.success) {
            return data.data;
            // const isHtmlInserted = getHtmlStructure(data.result);
            // if  (isHtmlInserted) {
            //     await getEmailStatusVerification(); // Ensure this function is also async
            // }
        }
        if(!data.success){
            throw new Error(data.message)
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops..',
            text: error,  // Error message from response
          });
    } finally {
        // fullScreenLoader.style.display = 'none'; // Hide loader after request completion
    }
}

function fetchGetRequest(){

}

function fetchPostRequest(){

}



window.addEventListener('DOMContentLoaded',init)