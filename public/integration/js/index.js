document.addEventListener('DOMContentLoaded', () => {
    const integrationsList = document.getElementById('integrationsList');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const integrationModal = document.getElementById('integrationModal');
    const ImportEmailsModal    = document.getElementById('ImportEmails');
    const availableIntegrationsList = document.getElementById('availableIntegrationsList');
    const availableImportEmails     = document.getElementById('availableImportEmails');
    const openModalButton = document.getElementById('openModal');
    const closeModalButton = document.getElementById('closeModal');
    const fullScreenLoader = document.getElementById('fullScreenLoader');

    const notyf = new Notyf();
    let currentPage = 1;
    let currentModalPage = 1;
    let lastModalPage = lastPage = 1;
    let itemsPerPage = 10;

    const fetchIntegrations = async () => {
        try {
            const response = await axios.get(`/integrations?page=${currentPage}&limit=${itemsPerPage}`);
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
            console.error('Error fetching integrations:', error);
            notyf.error('Error fetching integrations');
            return [];
        }
    };

    const renderIntegrations = async () => {
        const preloader = document.getElementById("preloaderAgain");
        preloader.classList.remove("hidden");
        // Force DOM repaint
        await new Promise(requestAnimationFrame); // Give it time to display
        await new Promise(resolve => setTimeout(resolve, 50)); // Slight delay

        try {
            const data = await fetchIntegrations();
            integrationsList.innerHTML = ''; 
            if (!data || !data.data || data.data.length === 0) {
                integrationsList.innerHTML = '<p class="text-gray-500">No integrations found.</p>';
                requestAnimationFrame(() => {
                    setTimeout(() => $('#preloader').fadeOut(), 50);
                });
                return;
            }
            integrationsList.classList.add(...['bg-white','rounded-lg','shadow','p-4']);
            lastPage = data.lastPage || 1;
            data.data.forEach(integration => {
                const card       = document.createElement('div');
                const dropdownId = `dropdown-${integration.id}`;
                card.className = 'bg-gray-100 px-3 py-3 rounded shadow mb-4';
                card.innerHTML = ` 
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center gap-4">
                            <img class="w-10" src="${integration.tool.icon_url}" />
                            <span class="font-semibold text-md text-[#3F51B5] capitalize tracking-widest">${integration.tool.name}</span>
                        </div>
    
                        <h2 class="text-[13px] font-700">${integration.name}-${integration.emails}</h2>
                        <h2 class="text-[13px] font-bold uppercase">${integration.status==='verified' ?'<span class="text-green-800 font-semibold">active</span>' :'<span class="text-red-800 font-semibold"> N/A </span>'}</h2>
                        <button onclick="openModalForImport('${integration.tool.name}','${integration.tool.icon_url}','${integration.name}','${integration.mc_user_id}','${integration.mc_token}','${integration.mc_dc}','${integration.id}')" class="bg-[#3F51B5] hover:bg-[#2a3898] text-white px-4 py-2 rounded shadow-xl">Select</button>
                         
                            <div class="relative inline-block text-left">
                                <button onclick="toggleDropdownMenu('${dropdownId}')" class="text-xl px-2 py-1 hover:bg-gray-300 rounded-full">&#8942;</button>
                                <div id="${dropdownId}" class="absolute right-0 z-10 mt-2 w-[14em] origin-top-right bg-white border border-gray-200 rounded-md shadow-lg hidden">
                                    <div class="py-1">
                                        <button onclick="removeIntegration('${integration.id}', this)" class="w-full text-left px-2 py-2 text-sm text-gray-900 hover:text-gray-600">
                                            🔗 Remove Integration
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>
                `;
                integrationsList.appendChild(card);
            }); 

            const listPaginationDiv = document.getElementById('list-pagination');
            listPaginationDiv.innerHTML = ''; // Clear previous
        
            const controls = document.createElement('div');
            controls.className = 'flex flex-wrap gap-2 items-center';
        
            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded';
            prevBtn.innerText = '←';
            prevBtn.disabled = currentPage <= 1;
            // prevBtn.onclick = () => changeModalPage(currentModalPage - 1);
            prevBtn.setAttribute('data-page', currentPage - 1);
            controls.appendChild(prevBtn);


            // Page numbers
            for (let i = 1; i <= lastPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.innerText = i;
                pageBtn.className = `px-3 py-1 rounded ${i === currentPage ? 'bg-[#3F51B5] hover:bg-[#2a3898] text-white' : 'bg-gray-200 hover:bg-gray-300'}`;
                // pageBtn.onclick = () => changeModalPage(i);
                pageBtn.setAttribute('data-page', i);
                controls.appendChild(pageBtn);
            }
        
            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded';
            nextBtn.innerText = '→';
            nextBtn.disabled = currentPage >= lastPage;
            // nextBtn.onclick = () => changeModalPage(currentModalPage + 1);
            nextBtn.setAttribute('data-page', currentPage + 1);
            controls.appendChild(nextBtn);
        
            listPaginationDiv.appendChild(controls);

            listPaginationDiv.addEventListener('click', function (e) {
                if (e.target && e.target.tagName === 'BUTTON') {
                    const page = parseInt(e.target.getAttribute('data-page'), 10);
                    if (!isNaN(page)) {
                        changeModalPage(page);
                    }
                }
            });
        } catch (error) { 
            notyf.error('Failed to load integrations');
            console.error("Error rendering integrations:", error); 
        }finally{
            preloader.classList.add("hidden");
        } 
    };

 
    function changeModalPage(page) {
        if(page >=1 && page <= lastPage){
            currentPage = page;
            $('#preloader').fadeIn()
            renderIntegrations();
            $('#preloader').fadeOut()
        }
    }

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
                    <span class="font-semibold text-md text-[#3F51B5] capitalize tracking-widest">${integration.name}</span>
                </div>
                 ${
                    isMailchimp ?
                    `<button class="bg-[#3F51B5] hover:bg-[#2a3898] text-white px-3 py-1 rounded shadow-md" onclick="window.location.href='/mailchimp/login'">
                        Select
                    </button>`
                    :`<button class="relative border-animation px-5 py-2 text-white bg-blue-600 rounded-md font-bold tracking-wider">Coming Soon </button>`
                }
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
        availableImportEmails.classList.add('hidden');
    });

    // itemsPerPageSelect.addEventListener('change', () => {
    //     itemsPerPage = parseInt(itemsPerPageSelect.value);
    //     currentPage = 1;
    //     renderIntegrations();
    // });

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


    window.openModalForImport=(toolName,imageUrl,name,userId,token,mc_dc,toolId)=>{
        const card = document.createElement('div');
        card.className = 'bg-gray-100 px-3 py-3 rounded shadow mb-4';
        card.innerHTML = ` 
                <div class="flex items-center gap-4  pb-4 pt-2">
                    <img class="w-10" src="${imageUrl}" />
                    <div>
                        <span class="font-semibold text-md  text-[#3F51B5] capitalize tracking-widest">${toolName}</span>
                        <span class="font-semibold text-md  text-[#3F51B5] capitalize tracking-widest">${name}</span>
                    </div> 
                </div>
            <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                <h2 class="text-[18px] font-bolder text-capitalize">${name}</h2>
                <button onclick="ImportData('${toolName}',${userId},'${token}','${mc_dc}','${toolId}')" class="bg-blue-400 hover:bg-blue-600 text-white px-4 py-2 rounded shadow-xl">Import</button>
            </div>
            `; 
        availableImportEmails.innerHTML=card.outerHTML; 
        ImportEmailsModal.classList.remove('hidden');
    }

    window.toggleDropdownMenu = (id)=>{
        // Close any other open dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu.id !== id) menu.classList.add('hidden');
        });

        const dropdown = document.getElementById(id);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        } 

        // Mark currently opened dropdown
        window.currentDropdownId = id;
    }

    document.addEventListener('click', (event) => {
        const openDropdown = window.currentDropdownId ? document.getElementById(window.currentDropdownId) : null;
        if (openDropdown && !openDropdown.contains(event.target) && !event.target.closest('button[onclick^="toggleDropdownMenu"]')) {
            openDropdown.classList.add('hidden');
            window.currentDropdownId = null;
        }
    });

    window.removeIntegration  = (id, btn) => {
        alert('something went wrong ....');
        console.log(btn); 
       console.log('Removing integration with ID:', id);
    }


    
    window.ImportData = async (toolName, userId, token,mc_dc,toolId) => {
        try {
            $('#preloader').fadeIn()
            const response = await axios.get(`/mailchimp/validate-emails?toolName=${toolName}&userId=${userId}&token=${token}&mc=${mc_dc}&integeration_id=${toolId}`);
            if (!response.data.success) {
                notyf.error(response.data.error || 'Error fetching available integrations');
                $('#preloader').fadeIn()
                ImportEmailsModal.classList.add('hidden');
                // return {
                //     data: [],
                //     total: 0,
                //     currentPage: 1,
                //     lastPage: 1
                // };
            }
            else if(response.data.success){
                 $('#preloader').fadeIn()
                notyf.success(response.data.message || 'Data imported successfully!'); 
                setTimeout(() => {
                    ImportEmailsModal.classList.add('hidden');
                    location.href = '/bulk';  
                }, 2000); 
            }      
        } catch (error) {
            $('#preloader').fadeIn()
            console.error('Error fetching integrations:', error);
            notyf.error('Error fetching integrations');
            // return [];
        }
    
        console.log('Importing data for', toolName);
    
    }

    renderIntegrations();
});


window.addEventListener('load', () => {
    if(localStorage.getItem('IntegerationList'))
        localStorage.removeItem('IntegerationList');
});
