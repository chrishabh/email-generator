let fn= ()=>{ 
    let currentUrl    =  window.location.pathname;
    let navLinks      =  document.querySelectorAll('.navbar-nav .nav-link')
    navLinks.forEach(function(link) {
        if (link.getAttribute('href') === currentUrl) {
            link.parentElement.classList.add('active');
        } else {
            link.parentElement.classList.remove('active');
        }
    });

    let url= ['/single-verification','/bulk-verification']
    if(url.includes(currentUrl)){
        const anchor = document.querySelector('a[href="#hero-area"]');
        if(anchor){
            anchor.addEventListener('click',()=>{
                window.location.href='/';
            })
        }
    }

}

window.addEventListener('load',fn)