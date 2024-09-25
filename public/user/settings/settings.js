async function init(){
    if(window.location.pathname=='/settings'){
        $('#preloader').fadeIn(); 
        const data  = await fetchOverallCreditsData()
        renderOverallCreditsChart(data)  
        $('#preloader').fadeOut();
    }
}

async function renderHtml(event,elem){
    event.preventDefault();
    const idArray = ['setting-section', 'dashboard-section','messages-section'];

    // Hide all sections except the one selected
    idArray.forEach(id => {
        const section = document.getElementById(id);
        if (section) {
            if (elem.id === id.split('-')[0]) {
                section.style.display = 'block';  // Show the selected section
            } else {
                section.style.display = 'none';   // Hide other sections
                section.innerHTML     = '';           // Clear the content of the hidden sections
            }
        }
    });

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
    else if(elem.id=='dashboard'){
        $('#preloader').fadeIn();
        // return
        const data  = await fetchOverallCreditsData()
        $('#preloader').fadeOut();
        renderOverallCreditsChart(data)
 
    }
    else if(elem.id=='messages'){
        $('#preloader').fadeIn();
        const response      = await fetchGetRequest('render-messages/','setting-page-token')
        const data          = response.data;
        const totalUsers    = response.total;
        const perPage       = response.perPage;
        const currentPage   = parseInt(response.currentPage);

        let html             = renderSettingHtmlPage(data, totalUsers, perPage, currentPage,true);
        let settingDOM       = document.getElementById('messages-section');
        settingDOM.innerHTML = html;
        $('#preloader').fadeOut();
 
    }
}

function renderSettingHtmlPage(data, totalUsers, perPage, currentPage,isMessagePage=false){
    let html       = ''  
    if (!data || !Array.isArray(data) || data.length === 0) {
        html += '<div class="setting-main-class"><h1 class="no-data-found">No Data Found</h1></div>';
    } 
    else{
        html = `<div class="setting-main-class">
        <h1 class="user-heading">${!isMessagePage?'Users Table':'User Work Experince'}</h1>
        <table class="table table-hover table-bordered">
            <thead class="table-head">`;
            if(isMessagePage){
                html += `
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">user Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">User Experience</th>
                </tr>`;
            }else{
                html += `
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Available Credits</th>
                    <th scope="col">Delete User</th>
                </tr>`;
            }
                 
           html+= `</thead>
            <tbody>`;
            data.forEach((user, index) => {
 
                if(isMessagePage){
                    html+= `<tr>
                    <th scope="row">${index+1}</th>
                    <td>${user.userId}</td>
                    <td> ${user.name}</td>
                    <td>${user.work_experience_description}</td>
                </tr>`;
                }else{
                    html+= `<tr>
                    <th scope="row">${index+1}</th>
                    <td>${user.name}</td>
                    <td> ${user.email}</td>
                    <td>${user.credits !== null ? user.credits + " credits" : 'No Credits'}</td>
                    <td class="text-center"><i class="fa-solid fa-trash" onclick="deleteUser(${user.userId},${isMessagePage})" style="cursor: pointer;"></i></td>
                </tr>`;
                }
            })
        html+=`</tbody>
            </table>`;
        
        // Pagination controls
        let totalPages = Math.ceil(totalUsers / perPage);
        html += `<div class="pagination-controls">`;
        html += generatePagination(currentPage, totalPages,isMessagePage);
        html += `</div></div>`;
    }
   // Set the innerHTML instead of textContent to render HTML
   return html
}
 

function generatePagination(currentPage, totalPages,isMessagePage=false) {
    let paginationHtml = '';

    if (totalPages <= 1) return ''; // No pagination if only one page

    paginationHtml += `<button onclick="fetchPage(1,${isMessagePage})" class="${currentPage === 1 ? 'active' : ''}">1</button>`;

    if (currentPage > 3) {
        paginationHtml += `<span>...</span>`;
    }

    // Show current page and up to 2 pages before/after current page
    for (let page = Math.max(2, currentPage - 2); page <= Math.min(totalPages - 1, currentPage + 2); page++) {
        paginationHtml += `<button onclick="fetchPage(${page},${isMessagePage})" class="${page === currentPage ? 'active' : ''}">${page}</button>`;
    }

    if (currentPage < totalPages - 2) {
        paginationHtml += `<span>...</span>`;
    }

    paginationHtml += `<button onclick="fetchPage(${totalPages},${isMessagePage})" class="${currentPage === totalPages ? 'active' : ''}">${totalPages}</button>`;

    return paginationHtml;
}

async function fetchPage(pageNumber,isMessagePage) {
    $('#preloader').fadeIn();
    let response      = await fetchGetRequest(`render-setting?page=${pageNumber}`, 'setting-page-token'); 
    if(isMessagePage)  
        response      = await fetchGetRequest(`render-messages/?page=${pageNumber}`, 'setting-page-token'); 
    
    const data          = response.data;
    const totalUsers    = response.total;
    const perPage       = response.perPage;
    const currentPage   = parseInt(response.currentPage);
    
    let html = renderSettingHtmlPage(data, totalUsers, perPage, currentPage);
    
    let settingDOM = document.getElementById('setting-section');
    if(isMessagePage)
        settingDOM = document.getElementById('messages-section');

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
            title: 'Oops...',
            text: error.message || 'Something went wrong!'
        });
    }
}


function fetchPostRequest(){

}

async function deleteUser(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                $('#preloader').fadeIn();
                const response = await fetch(`/settings/delete-user/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(`meta[name="delete-button-token"]`).getAttribute('content')
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    $('#preloader').fadeOut();
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The user has been deleted successfully.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Reload the user list or fetch the current page again after deletion
                    fetchPage(1); // Reload the page or fetch current users
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                $('#preloader').fadeOut();
                console.error('Error deleting user:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message || 'Something went wrong!'
                });
            }
        }
    });
}

async function fetchOverallCreditsData() {
    try {
        const response = await fetch(`/settings/overall-credits-report`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        if (data.success) {
            return data.data;
        }
        if(!data.success){
            throw new Error(data.message)
        }
    } catch (error) {
        $('#preloader').fadeOut();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message || 'Something went wrong!'
        });
    }
}

// Render the overall credits chart
function renderOverallCreditsChart(data) {
    console.log(data);
    let html = `<div class="row mx-0">       
        <div class="col-md-6" id="left-section-of-chart">
            <h1>Total Credit Score</h1>
            <h2 style="text-align: left;padding: 20px;font-weight: bold;">Customer Available Balance : ${data.availableCredits} </h2>
            <h2 style="text-align: left;padding-left: 20px;font-weight: bold;">Cutomer Used Balance :  ${data.usedCredits}</h2>
            <canvas id="overallCreditsChart" width="400" height="400"></canvas>
        </div>
        <div class="col-md-6" id="right-section-of-chart">
            <h1>Total Available Credit Score of API</h1>
            <h2 style="text-align: center;padding: 20px;font-weight: bold;">API Available Credits :  ${data.adminCreditsTotal}</h2>
            <canvas id="overallCreditsAdminChart" width="400" height="400"></canvas>
        </div>
    </div>`;
    document.getElementById('dashboard-section').innerHTML=html;
    const ctx = document.getElementById('overallCreditsChart').getContext('2d');
    const ctx1 = document.getElementById('overallCreditsAdminChart').getContext('2d');
    
    const chartData = {
        labels: ['Available Credits', 'Used Credits'],
        datasets: [{
            label: 'Overall Credits Report',
            data: [data.availableCredits, data.usedCredits],
            backgroundColor: [
                'rgba(63, 81, 181, 0.2)',  // Available credits
                'rgba(255, 99, 132, 0.2)'   // Used credits
            ],
            borderColor: [
                'rgba(63, 81, 181, 1)',    // Available credits
                'rgba(255, 99, 132, 1)'     // Used credits
            ],
            borderWidth: 2 
        }]
    };
    const chartData2 = {
        labels: ['Available API Credits'],
        datasets: [{
            label: 'Overall Credits',
            data: [data.adminCreditsTotal],
            backgroundColor: [
                'rgba(63, 81, 181, 0.2)',  // Light blue shade with transparency
            ],
            borderColor: [
                'rgba(63, 81, 181, 1)',    // Solid blue shade for the border
            ],
            borderWidth: 2 
        }]
    };

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false, // Allows for custom height/width
        // aspectRatio: 2, // Width to height ratio
        elements: {
            arc: {
                borderWidth: 2,  // Increase border width for the arcs
            }
        },plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    boxWidth: 20,
                    padding: 15,
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)', // Stylish tooltip background
                titleColor: '#fff',
                bodyColor: '#fff',
            }
        },
        scales: {
            y: {
                display: true, // Hide y-axis
                beginAtZero: true
            }
        },
        animations: {
            tension: {
                duration: 100,
                easing: 'linear',
                from: 1,
                to: 0,
                loop: true
            }
        }
    };
    if (ctx.chartInstance) {
        ctx.chartInstance.destroy();
    }
    if (ctx1.chartInstance) {
        ctx1.chartInstance.destroy();
    }

    new Chart(ctx, {
        type: 'doughnut',  // You can use 'pie', 'bar', 'doughnut', etc.
        data: chartData,
        options: chartOptions
    });
    new Chart(ctx1, {
        type: 'pie',  // You can use 'pie', 'bar', 'doughnut', etc.
        data: chartData2,
        options: chartOptions
    });
}


window.addEventListener('DOMContentLoaded',init)