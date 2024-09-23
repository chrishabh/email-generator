function init(){

}

async function renderHtml(event,elem){
    event.preventDefault();
    if(elem.id=='setting'){
        $('#preloader').fadeIn();
        const response      = await fetchGetRequest('render-setting/','setting-page-token')
        const data          = response.data;
        const totalUsers    = response.total;
        const perPage       = response.perPage;
        const currentPage   = parseInt(response.currentPage);

        let html             = renderSettingHtmlPage(data, totalUsers, perPage, currentPage);
        let settingDOM       = document.getElementById('setting-section');
        settingDOM.innerHTML = html;
        $('#preloader').fadeOut();

    }
}

function renderSettingHtmlPage(data, totalUsers, perPage, currentPage){
    let html       = ''  
    if (!data || !Array.isArray(data) || data.length === 0) {
        html += '<div class="setting-main-class"><h1 class="no-data-found">No Data Found</h1></div>';
    } 
    else{
        html = `<div class="setting-main-class">
        <h1 class="user-heading">Users Table</h1>
        <table class="table table-hover table-bordered">
            <thead class="table-head">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Available Credits</th>
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
                    <td class="text-center"><i class="fa-solid fa-trash"></td>
                </tr>`;
            })
        html+=`</tbody>
            </table>`;
        
        // Pagination controls
        let totalPages = Math.ceil(totalUsers / perPage);
        html += `<div class="pagination-controls">`;
        html += generatePagination(currentPage, totalPages);
        html += `</div></div>`;
    }
   // Set the innerHTML instead of textContent to render HTML
   return html
}
function generatePagination(currentPage, totalPages) {
    let paginationHtml = '';

    if (totalPages <= 1) return ''; // No pagination if only one page

    paginationHtml += `<button onclick="fetchPage(1)" class="${currentPage === 1 ? 'active' : ''}">1</button>`;

    if (currentPage > 3) {
        paginationHtml += `<span>...</span>`;
    }

    // Show current page and up to 2 pages before/after current page
    for (let page = Math.max(2, currentPage - 2); page <= Math.min(totalPages - 1, currentPage + 2); page++) {
        paginationHtml += `<button onclick="fetchPage(${page})" class="${page === currentPage ? 'active' : ''}">${page}</button>`;
    }

    if (currentPage < totalPages - 2) {
        paginationHtml += `<span>...</span>`;
    }

    paginationHtml += `<button onclick="fetchPage(${totalPages})" class="${currentPage === totalPages ? 'active' : ''}">${totalPages}</button>`;

    return paginationHtml;
}

async function fetchPage(pageNumber) {
    $('#preloader').fadeIn();
    const response      = await fetchGetRequest(`render-setting?page=${pageNumber}`, 'setting-page-token'); 
    const data          = response.data;
    const totalUsers    = response.total;
    const perPage       = response.perPage;
    const currentPage   = parseInt(response.currentPage);
    
    let html = renderSettingHtmlPage(data, totalUsers, perPage, currentPage);
    let settingDOM = document.getElementById('setting-section');
    settingDOM.innerHTML = html;
    $('#preloader').fadeOut();
}


async function fetchGetRequest(routeURL,tokenName){
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
            return data;
        }
        if(!data.success){
            throw new Error(data.message)
        }
    } catch (error) {
        $('#preloader').fadeOut();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops..',
            text: error,  // Error message from response
        });
    }
}


function fetchPostRequest(){

}



window.addEventListener('DOMContentLoaded',init)