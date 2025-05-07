document.addEventListener('DOMContentLoaded', () => {
    const integrationsList = document.getElementById('integrationsList');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const integrationModal = document.getElementById('integrationModal');
    const availableIntegrationsList = document.getElementById('availableIntegrationsList');
    const openModalButton = document.getElementById('openModal');
    const closeModalButton = document.getElementById('closeModal');
    const fullScreenLoader = document.getElementById('fullScreenLoader');

    const notyf = new Notyf();
    let currentPage = 1;
    let currentModalPage = 1;
    let lastModalPage = 1;
    let itemsPerPage = parseInt(itemsPerPageSelect.value);

    const fetchIntegrations = async () => {
        try {
            const response = await axios.get(`/integrations?page=${currentPage}&limit=${itemsPerPage}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching integrations:', error);
            notyf.error('Error fetching integrations');
            return [];
        }
    };

    const renderIntegrations = async () => {
        const data = await fetchIntegrations();
        integrationsList.innerHTML = '';

        if (data.length === 0) {
            integrationsList.innerHTML = '<p class="text-gray-500">No integrations found.</p>';
            return;
        }

        data.forEach(integration => {
            const card = document.createElement('div');
            card.className = 'bg-gray-100 p-4 rounded shadow mb-4';
            card.innerHTML = `
                <h2 class="text-lg font-semibold">${integration.name}</h2>
                <p class="text-gray-600">${integration.description}</p>
            `;
            integrationsList.appendChild(card);
        });
    };

    const fetchAvailableIntegrations = async () => {
        try {
            const response = await axios.get(`/available-tools?perPage=${itemsPerPage}&page=${currentModalPage}`);
            if (!response.data.success) {
                notyf.error(response.data.error || 'Error fetching available integrations');
                return {
                    data: [],
                    total: 0,
                    currentPage: 1,
                    lastPage: 1
                };
            }

            return response.data;
        } catch (error) {
            console.error('Error fetching available integrations:', error);
            notyf.error('Error fetching available integrations');
            return {
                data: [],
                total: 0,
                currentPage: 1,
                lastPage: 1
            };
        }
    };

    const renderAvailableIntegrations = async () => {
        try {
            const data = await fetchAvailableIntegrations();
            displayAvailableIntegrations(data);
        } catch (error) {
            console.error('Error rendering available integrations:', error);
            notyf.error('Failed to load available integrations');
        }
    };

    const displayAvailableIntegrations = (data) => {
        availableIntegrationsList.innerHTML = '';
    
        if (!data || !data.data || data.data.length === 0) {
            availableIntegrationsList.innerHTML = '<p class="text-gray-500">No available integrations.</p>';
            return;
        }
    
        lastModalPage = data.lastPage || 1;
    
        data.data.forEach(integration => {
            const item = document.createElement('div');
            item.className = 'flex justify-between items-center bg-gray-100 p-2 px-3 rounded mb-3 shadow-md';
            const isMailchimp = integration.name.toLowerCase() === 'mailchimp';
            item.innerHTML = `
                <div class="flex items-center gap-4">
                    <img class="w-10" src="${integration.icon_url}" />
                    <span class="font-semibold text-md text-blue-700/50 capitalize tracking-widest">${integration.name}</span>
                </div>
                <button class="bg-blue-400 hover:bg-blue-600 text-white px-3 py-1 rounded shadow-md"
                    ${isMailchimp ? `onclick="window.location.href='/mailchimp/login'"` : `onclick="addIntegration(${integration.id})"`}>
                    Select
                </button>
            `;
            availableIntegrationsList.appendChild(item);
        });
    
        const paginationDiv = document.getElementById('pagination');
        paginationDiv.innerHTML = ''; // Clear previous
    
        const controls = document.createElement('div');
        controls.className = 'flex flex-wrap gap-2 items-center';
    
        // Previous button
        const prevBtn = document.createElement('button');
        prevBtn.className = 'bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded';
        prevBtn.innerText = '←';
        prevBtn.disabled = currentModalPage <= 1;
        // prevBtn.onclick = () => changeModalPage(currentModalPage - 1);
        prevBtn.setAttribute('data-page', currentModalPage - 1);
        controls.appendChild(prevBtn);
    
        // Page numbers
        for (let i = 1; i <= lastModalPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.innerText = i;
            pageBtn.className = `px-3 py-1 rounded ${i === currentModalPage ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'}`;
            // pageBtn.onclick = () => changeModalPage(i);
            pageBtn.setAttribute('data-page', i);
            controls.appendChild(pageBtn);
        }
    
        // Next button
        const nextBtn = document.createElement('button');
        nextBtn.className = 'bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded';
        nextBtn.innerText = '→';
        nextBtn.disabled = currentModalPage >= lastModalPage;
        // nextBtn.onclick = () => changeModalPage(currentModalPage + 1);
        nextBtn.setAttribute('data-page', currentModalPage + 1);
        controls.appendChild(nextBtn);
    
        paginationDiv.appendChild(controls);

        paginationDiv.addEventListener('click', function (e) {
            if (e.target && e.target.tagName === 'BUTTON') {
                const page = parseInt(e.target.getAttribute('data-page'), 10);
                if (!isNaN(page)) {
                    changeModalPage(page);
                }
            }
        });
    };
    
    function changeModalPage(page) {
        if(page >=1 && page <= lastModalPage){
            currentModalPage = page;
            $('#preloader').fadeIn()
            renderAvailableIntegrations();
            $('#preloader').fadeOut()
        }
    }


    window.prevModalPage = () => {
        if (currentModalPage > 1) {
            currentModalPage--;
            renderAvailableIntegrations();
        }
    };

    window.nextModalPage = () => {
        if (currentModalPage < lastModalPage) {
            currentModalPage++;
            renderAvailableIntegrations();
        }
    };

    window.addIntegration = async (integrationId) => {
        try {
            const response = await axios.post('/api/integrations', {
                integration_id: integrationId
            });
            notyf.success(response.data.message || 'Integration added!');
            integrationModal.classList.add('hidden');
            await renderIntegrations();
        } catch (error) {
            console.error('Error adding integration:', error);
            notyf.error(error.response?.data?.error || 'Failed to add integration');
        }
    };

    openModalButton.addEventListener('click', async () => {
        currentModalPage = 1;
        $('#preloader').fadeIn()
        try {
            let cachedData = localStorage.getItem('IntegerationList');
            let data;
            if (cachedData) {
                data = JSON.parse(cachedData); 
            }
            else{
                data = await fetchAvailableIntegrations();
                if(data && data.data && data.data.length>0)
                    localStorage.setItem('IntegerationList',JSON.stringify(data));

            }   

            displayAvailableIntegrations(data);          
            integrationModal.classList.remove('hidden');

        } catch (error) {
            console.error('Error opening modal:', error);
            notyf.error('Something went wrong'+error);
        } finally {
            $('#preloader').fadeOut()
            integrationModal.classList.remove('hidden');
        }
    });
    

    closeModalButton.addEventListener('click', () => {
        integrationModal.classList.add('hidden');
    });

    itemsPerPageSelect.addEventListener('change', () => {
        itemsPerPage = parseInt(itemsPerPageSelect.value);
        currentPage = 1;
        renderIntegrations();
    });

    prevPageButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderIntegrations();
        }
    });

    nextPageButton.addEventListener('click', () => {
        currentPage++;
        renderIntegrations();
    });

    renderIntegrations();
});


window.addEventListener('load', () => {
    if(localStorage.getItem('IntegerationList'))
        localStorage.removeItem('IntegerationList');
});
