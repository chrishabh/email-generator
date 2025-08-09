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
    const importCloseModalButton = document.getElementById('importCloseModal');
    const fullScreenLoader = document.getElementById('fullScreenLoader');



    const moosendApiModal          = document.getElementById('moosendApiModal');
    const moosendApiKeyInput       = document.getElementById('moosendApiKeyInput');
    const apiUrlInput              = document.getElementById('moosendApiKeyInputURL');
    const moosendConnectButton     = document.getElementById('moosendConnectButton');
    const moosendCancelButton      = document.getElementById('moosendCancelButton');
    const moosendCloseModalButton  = document.getElementById('moosendCloseModalButton'); // New close button

    const notyf = new Notyf();
    let currentPage = 1;
    let currentModalPage = 1;
    let lastModalPage = lastPage = 1;
    let itemsPerPage = 20;

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
                integrationsList.classList.add(...['bg-white','rounded-lg','shadow','p-4']);
                integrationsList.innerHTML = `<p class="text-gray-500 text-center">Click on 'Add Integration' to add a new integration...</p>`;
                requestAnimationFrame(() => {
                    setTimeout(() => $('#preloader').fadeOut(), 50);
                });
                return;
            }
            integrationsList.classList.add(...['bg-white','rounded-lg','shadow','p-4']);
            lastPage = data.lastPage || 1;
            data.data.forEach(integration => {
                const card            = document.createElement('div');
                let OpenModalImport = null;

                if(integration.tool.name.toLowerCase() === 'web engage'){ 
                    OpenModalImport = `ImportData('${integration.tool.name}','${integration.mc_user_id}','${integration.mc_dc}','${integration.id}','webengage')`
                }else{
                    let safeName = '';
                    if(integration.tool.name.toLowerCase() === 'aweber')
                        safeName     = integration.name.replace(/'/g, "\\'"); 
                    else safeName     = integration.name
                   OpenModalImport =  `openModalForImport('${integration.tool.name}','${integration.tool.icon_url}','${safeName}','${integration.mc_user_id}','${integration.mc_dc}','${integration.id}')`
                }
                const dropdownId = `dropdown-${integration.id}`;
                card.className = 'bg-gray-100 px-3 py-3 rounded shadow mb-4';
                card.innerHTML = ` 
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center gap-4 w-[20%]">
                            <img class="w-10" src="${integration.tool.icon_url}" />
                            <span class="font-semibold text-md text-[#3F51B5] capitalize tracking-widest">${integration.tool.name}</span>
                        </div>
    
                        <div class="text-[sm] font-700 w-[30%] truncate">${integration.name?integration.name:integration.service_name}${integration.emails ? `- ${integration.emails}` :''}</div>
                        <div class="w-[10%] text-sm uppercase text-green-800 font-semibold">${integration.status==='verified' ?'active' :' N/A '}</div>
                        <div class="w-[15%]"><button onclick="${OpenModalImport}" class="bg-[#3F51B5] hover:bg-[#2a3898] text-white px-4 py-2 rounded shadow-xl">Select</button></div>
                         
                        <div class="relative inline-block text-left w-[5%]">
                            <button id="toggleDropdown" onclick="toggleDropdownMenu('${dropdownId}')" class="text-xl px-2 py-1 hover:bg-gray-300 rounded-full">&#8942;</button>
                            <div id="${dropdownId}" class="absolute right-0 z-10 mt-2 w-[14em] origin-top-right bg-white border border-gray-200 rounded-md shadow-lg hidden">
                                <div class="py-1">
                                    <button id="removeIntegration" onclick="removeIntegration('${integration.id}', this)" class="w-full text-left px-2 py-2 text-sm text-gray-900 hover:text-gray-600">
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
        const activeIntegrations      = ['mailchimp', 'hubspot','dropbox', 'constant contact','moosend','get response','active campaign','zoho campaign','brevo','mailer lite','convertkit','benchmark','zoho campaign','campaign monitor','drip','google sheets','gist','mailgun','web engage','aweber','mailjet','intercom','clay'];
        const isModalOpenedForApiKey  = ['moosend','get response','active campaign','brevo','mailer lite','convertkit','benchmark','gist','mailgun','web engage','mailjet']
        data.data.forEach(integration => {
            const item        = document.createElement('div');
            item.className    = 'flex justify-between items-center bg-gray-100 p-2 px-3 rounded mb-3 shadow-md';
            const isActive    = activeIntegrations.includes(integration.name.toLowerCase());
            const isModalOpen = isModalOpenedForApiKey.includes(integration.name.toLowerCase());
            let onclickAction = ''
    
            if(isActive && isModalOpen && integration.name.toLowerCase()){
                onclickAction = `showModalOfInputKey('${integration.id}','${integration.name}')`;
            }
            if(isActive && integration.name.toLowerCase()==='clay'){
                onclickAction = `window.location.href='${window.location.origin}/clay'`;  
            }
            else if(isActive){
                onclickAction =`window.location.href='/mailchimp/login/${integration.id}/${integration.name}'`;
            }
            item.innerHTML = `
                <div class="flex items-center gap-4">
                    <img class="w-10" src="${integration.icon_url}" />
                    <span class="font-semibold text-md text-[#3F51B5] capitalize tracking-widest">${integration.name}</span>
                </div>
                 ${
                    isActive ?
                    `<button class="bg-[#3F51B5] hover:bg-[#2a3898] text-white px-3 py-1 rounded shadow-md" onclick="${onclickAction}">
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
            pageBtn.className = `px-3 py-1 rounded ${i === currentModalPage ? 'bg-[#3F51B5] text-white' : 'bg-gray-200 hover:bg-gray-300'}`;
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
    }

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
    importCloseModalButton.addEventListener('click', () => {
        ImportEmailsModal.classList.add('hidden');
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

    // nextPageButton.addEventListener('click', () => {
    //     currentPage++;
    //     renderIntegrations();
    // });


    window.openModalForImport=async (toolName,imageUrl,name,userId,mc_dc,toolId)=>{
        const normalizeToolName = (str) => str.toLowerCase().replace(/\s+/g, '_');
        const lowerToolName     = normalizeToolName(toolName);
        const configMap = {
            dropbox:{
                apiEndpoint : `/get-listing-file-dropbox?userId=${userId}&toolName=${toolName}&toolId=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image : '/integration/integerated-icon/google_sheet.svg',
                itemRenderer :(item,image) =>{
                    const fileSizeKB      = (item.size / 1024).toFixed(2); // Size in KB
                    const fileSizeDisplay = fileSizeKB > 1024 ? `${(fileSizeKB / 1024).toFixed(2)} MB` : `${fileSizeKB} KB`;
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <img class="w-7" src="${image}" />
                            <h2 class="text-[15px] font-bold">${item.name}</h2>
                            <span class="block text-sm text-gray-600">Size: ${fileSizeDisplay}</span>
                            <button onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.id}','${item.name}','${item.path_display}')" class="bg-[#3F51B5] hover:bg-[#2a3898] text-white px-4 py-2 rounded shadow-xl">Import</button>
                        </div>`
                }
            },
            google_sheets:{
                apiEndpoint : `/get-listing-file-dropbox?userId=${userId}&toolName=${toolName}&toolId=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image : '/integration/integerated-icon/google_sheet.svg',
                itemRenderer :(item,image) =>{
                    // const fileSizeKB      = (item.size / 1024).toFixed(2); // Size in KB
                    // const fileSizeDisplay = fileSizeKB > 1024 ? `${(fileSizeKB / 1024).toFixed(2)} MB` : `${fileSizeKB} KB`;
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/5 pr-2"> 
                                <img class="w-7" src="${image}" />
                            </div> 
                            <div class="w-1/2 pr-2"> 
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">${item.name}</h2> 
                            </div>   
                            <div class="w-1/6 text-sm text-gray-600">
                                <button onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.id}','${item.name}','${item.path_display}')" class="bg-[#3F51B5] hover:bg-[#2a3898] text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>  
                        </div>`
                }
            },
            moosend:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.ActiveMemberCount === 0;
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/4 text-sm text-gray-600">
                                Members: ${item.ActiveMemberCount}
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${item.Name}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            },
            get_response:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${item.Name}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            },
            active_campaign:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${item.Name}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            },
            brevo:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/3 text-sm text-gray-600">
                                Subscribers Count: ${item.SubscribersCount}
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${item.Name}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            },
            mailer_lite:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/3 text-sm text-gray-600">
                                Subscribers Count: ${item.SubscribersCount}
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${item.Name}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            },
            convertkit:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${item.Name}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            },
            benchmark:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/3 text-sm text-gray-600">
                                Contact Count: ${item.SubscribersCount}
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${item.Name}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            },
            gist:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/3 text-sm text-gray-600">
                                Contact Count: ${item.SubscribersCount}
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${item.Name}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            },
            mailgun:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/3 text-sm text-gray-600">
                                Contact Count: ${item.SubscribersCount}
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${item.Name}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            }, 
            aweber:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const safeName     = item.Name.replace(/'/g, "\\'");
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/3 text-sm text-gray-600">
                                Contact Count: ${item.SubscribersCount}
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${safeName}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            }, 
            mailjet:{
                apiEndpoint : `/fetch-api-key-based-listing?toolName=${toolName}&integeration_id=${toolId}`,
                listExtractor : (res) => Array.isArray(res?.data) ? res.data : [],
                image: '/integration/integerated-icon/google_sheet.svg',
                itemRenderer: (item,image) => {
                    const isDisabled   = item.SubscribersCount === 0;
                    const safeName     = item.Name.replace(/'/g, "\\'");
                    const buttonStyle  =  isDisabled ? 'cursor-not-allowed opacity-50 pointer-events-none' : 'hover:bg-[#2a3898]';
                    return `
                        <div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                            <div class="w-1/2 pr-2">
                                <h2 class="text-[15px] font-bold truncate" title="${item.Name}">
                                    ${item.Name}
                                </h2>
                            </div>
                            <div class="w-1/3 text-sm text-gray-600">
                                Contact Count: ${item.SubscribersCount}
                            </div>
                            <div class="w-1/4 flex justify-end ${isDisabled ? 'cursor-not-allowed':''}">
                                <button ${!isDisabled ? `onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}','${item.ID}','${safeName}')"` :''} class="bg-[#3F51B5] ${buttonStyle} text-white px-4 py-2 rounded shadow-xl">Import</button>
                            </div>
                        </div>`
                }
                
            }

        }

        const toolConfig  = configMap[lowerToolName];
        $('#preloader').fadeIn()
        let result  =  toolConfig ? await makeApiHit(toolConfig.apiEndpoint) : null;
        $('#preloader').fadeOut()
        const excelImage = window.location.origin + toolConfig?.image || '/integration/integerated-icon/google_sheet.svg';
        const items      = toolConfig ? toolConfig.listExtractor(result) : [];
        let cardsHTML = '';
        cardsHTML += `<div class="bg-gray-100 px-3 py-3 rounded shadow mb-4">
                        <div class="flex items-center gap-4 pb-4 pt-2">
                            <img class="w-10" src="${imageUrl}" />
                            <div>
                                <span class="font-semibold text-md  text-[#3F51B5] capitalize tracking-widest">${toolName}</span>
                                <span class="font-semibold text-md  text-[#3F51B5] capitalize tracking-widest" title="${name}">${name!='null' ? (( name.length > 30) ? name.slice(0, 30) + '...' : name ):'' }</span>
                            </div> 
                        </div>`;
        if(items.length>0 && toolConfig?.itemRenderer) {
            items.forEach(item => {
                cardsHTML += toolConfig.itemRenderer(item, excelImage);
            })
        }else if(toolConfig && files.length === 0){
            cardsHTML+= 
            `<div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                <h2 class="text-[18px] font-bold">No Content Found!!!</h2>
            </div>`
        }else{
        cardsHTML+= 
            `<div class="flex justify-between items-center mb-2 border border-gray-500 px-3 py-2">
                <h2 class="text-[18px] font-bold">${name !== 'null' ? name : toolName}</h2>
                <button onclick="ImportData('${toolName}','${userId}','${mc_dc}','${toolId}')" class="bg-[#3F51B5] hover:bg-[#2a3898] text-white px-4 py-2 rounded shadow-xl">Import</button>
            </div>`;
        }
        cardsHTML+=`</div>`;
        availableImportEmails.innerHTML = cardsHTML;
        // availableImportEmails.innerHTML=card.outerHTML; 
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

    // document.addEventListener('click', (event) => {
    //     const openDropdown = window.currentDropdownId ? document.getElementById(window.currentDropdownId) : null;
    //     if (openDropdown && !openDropdown.contains(event.target) && !event.target.closest('button[onclick^="toggleDropdownMenu"]')) {
    //         openDropdown.classList.add('hidden');
    //         window.currentDropdownId = null;
    //     } 

    // });

    window.removeIntegration  = (id, btn) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to undo this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'red',
            cancelButtonColor: 'grey',
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true
        }).then((result) => {
        if(result.isConfirmed) { 
            $('#preloader').fadeIn()
            makeApiHit(`/remove-integration/${id}`, 'DELETE')
                .then(response => {
                    notyf.success(response.message || 'Integration removed successfully!');
                    // btn.closest('.bg-gray-100').remove(); // Remove the card
                    window.location.reload(); // Reload the page to reflect changes
                    $('#preloader').fadeOut()
                })
                .catch(error => {
                    console.error('Error removing integration:', error);
                    notyf.error(error.response?.data?.error || 'Failed to remove integration');
                    $('#preloader').fadeOut()
                });
        }
        const dropdown = document.getElementById('dropdown-'.id);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        } 
    })
    }


    
    window.ImportData = async (toolName, userId,mc_dc,toolId,$fileId=null,$fileName='',$filePath='') => {
        
        const normalizeToolName = (str) => str.toLowerCase().replace(/\s+/g, '_');
        const lowerToolName     = normalizeToolName(toolName);
        const importConfitMap = {
            dropbox: ()=> `/import-integeration-emails?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&fileId=${$fileId}&fileName=${$fileName}&filePath=${$filePath}`,
            google_sheets: ()=> `/import-integeration-emails?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&fileId=${$fileId}&fileName=${$fileName}&filePath=${$filePath}`,
            get_response: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            moosend: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            active_campaign: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            brevo: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            mailer_lite: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            convertkit: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            benchmark: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            gist: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            mailgun: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            web_engage: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            aweber: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
            mailjet: ()=> `/import-emails-based-on-api-key?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}&list_id=${$fileId}`,
        }
        
        const getUrl = importConfitMap[lowerToolName] || (()=> `/mailchimp/validate-emails?toolName=${toolName}&userId=${userId}&mc=${mc_dc}&integeration_id=${toolId}`);
        
        try {
            $('#preloader').fadeIn()
            const response = await axios.get(getUrl());
            if (!response.data.success) {
                notyf.error(response.data.error || 'Error fetching available integrations');
                $('#preloader').fadeIn()
                ImportEmailsModal.classList.add('hidden');
                 return
            }
            notyf.success(response.data.message || 'Data imported successfully!'); 
            setTimeout(() => {
                ImportEmailsModal.classList.add('hidden');
                location.href = '/bulk';  
                $('#preloader').fadeOut();
            }, 1000);
            // else if(response.data.success){
            //      $('#preloader').fadeIn()
            //     notyf.success(response.data.message || 'Data imported successfully!'); 
            //     setTimeout(() => {
            //         ImportEmailsModal.classList.add('hidden');
            //         location.href = '/bulk';  
            //     }, 2000); 
            // }      
        } catch (error) {
            $('#preloader').fadeOut()
            console.error('Error importing data:', error);
            notyf.error('Something went wrong while importing data');
            // return [];
        }
    
        console.log('Importing data for', toolName);
    
    }

    window.showModalOfInputKey = (id, name) => {
        moosendApiModal.classList.remove('hidden'); // This line opens the modal
        moosendApiKeyInput.value = '';
        moosendApiKeyInput.focus();
        moosendModalTitle.setAttribute('data-tool-id', id);
        moosendModalTitle.setAttribute('data-tool-name', name);
        moosendModalTitle.innerText = `Connect ${name}`;

        // Reset the API URL input
        const apiUrlInput = document.getElementById('moosendApiKeyInputURL');
        apiUrlInput.value = '';

        // Get the parent div of the API URL input to toggle visibility
        const apiUrlDiv = apiUrlInput.closest('div');
        // Show API URL input only for "active campaign"
        if (name.trim().toLowerCase() === 'active campaign') {
            apiUrlDiv.classList.remove('hidden');
            apiUrlInput.placeholder = 'Enter your ActiveCampaign API URL';
        }else if(name.trim().toLowerCase() === 'web engage'){
            apiUrlDiv.classList.remove('hidden');
            const apiUrlLabel     = apiUrlDiv.querySelector('label');
            apiUrlLabel.innerText = 'Licence Key:';
            apiUrlInput.placeholder = 'Enter your WebEngage License';
        }else if(name.trim().toLowerCase() === 'mailjet'){
            apiUrlDiv.classList.remove('hidden');
            const apiUrlLabel     = apiUrlDiv.querySelector('label');
            apiUrlLabel.innerText = 'API Secret Key:';
            apiUrlInput.placeholder = 'Enter your API Secret key';
        } else {
            apiUrlDiv.classList.add('hidden');
        }
    }

    moosendConnectButton.addEventListener('click', async () => {
        const apiKey   = moosendApiKeyInput.value.trim();
        const apiUrl   = apiUrlInput.value.trim();
        const toolId   = moosendModalTitle.getAttribute('data-tool-id');
        const toolName = moosendModalTitle.getAttribute('data-tool-name');
        if (!apiKey) {
            notyf.error('API Key cannot be empty.');
            return;
        } 
        if(toolName.trim().toLowerCase() === 'active campaign' || toolName.trim().toLowerCase() === 'web engage' && !apiUrl) {

            notyf.error(`API ${toolName==='web engage'? 'license':'URL'} cannot be empty for ${toolName}`); 
            return
        }
        try {
            moosendApiModal.classList.add('hidden');
            $('#preloader').fadeIn();
            let query = `/tool-connection-based-on-api-key?api_key=${encodeURIComponent(apiKey)}&tool_name=${toolName}&tool_id=${toolId}`;
            if (toolName.trim().toLowerCase() === 'active campaign' || toolName.trim().toLowerCase() === 'web engage' || toolName.trim().toLowerCase() === 'mailjet' && apiUrl) {
                query += `&api_url=${apiUrl}`;
            }
            result  = await makeApiHit(query)
            if(result.success){
                notyf.success(result.message || 'Integration added!');
                integrationModal.classList.add('hidden');
                await renderIntegrations();
            }
            // const availableToolsResponse = await axios.get(`/integrations/available?page=1&limit=100`);
            // if (availableToolsResponse.data.success && availableToolsResponse.data.data) {
            //     const moosendTool = availableToolsResponse.data.data.find(tool => tool.slug === 'mosend'); // Use 'mosend' slug from your seeder
            //     if (moosendTool) {
            //         moosendToolId = moosendTool.id;
            //     }
            // }

            // if (!moosendToolId) {
            //     notyf.error('Moosend tool configuration not found. Please refresh the page.');
            //     $('#preloader').fadeOut();
            //     return;
            // }

            // // Make an Axios POST request to your backend to save the API key
            // // This '/connect-moosend' endpoint needs to be created in your Laravel routes and controller.
            // const response = await axios.post('/connect-moosend', {
            //     api_key: apiKey,
            //     tool_id: moosendToolId,
            //     tool_name: 'mosend' // Ensure slug matches your seeder and backend logic
            // });

            // if (response.data.success) {
            //     notyf.success(response.data.message || 'Moosend connected successfully!');
            //     renderIntegrations(currentPage); // Re-render the main integrations list to show updated status
            // } else {
            //     notyf.error(response.data.error || 'Failed to connect Moosend. Please check your API key.');
            // }
        } catch (error) {
            console.error('Error connecting Moosend:', error);
            notyf.error(error.response.data.error || `An error occurred while connecting ${toolName}.`);
        } finally {
            $('#preloader').fadeOut();
        }
    });

    // Event listener for the 'Cancel' button in the Moosend API Key modal
    moosendCancelButton.addEventListener('click', () => {
        moosendApiModal.classList.add('hidden'); // Hide the modal
        moosendApiKeyInput.value = ''; // Clear the input field
    });

    // Event listener for the 'x' close button in the Moosend API Key modal
    moosendCloseModalButton.addEventListener('click', () => {
        moosendApiModal.classList.add('hidden');
        moosendApiKeyInput.value = '';
    });

    renderIntegrations();
});


window.addEventListener('load', () => {
    if(localStorage.getItem('IntegerationList'))
        localStorage.removeItem('IntegerationList');
});

function makeApiHit(url, method = 'GET', data = null) {
    return new Promise(async (resolve, reject) => {
        try {
            const response = await axios({
                method: method,
                url: url,
                data: data
            });

            if(response.data.success) {
                resolve(response.data);
            } else {
                reject(new Error(response.data.error || 'Error fetching data'));
            }
        } catch (error) {
            console.error('API Error:', error);
            reject(error);
        }
    });
}



