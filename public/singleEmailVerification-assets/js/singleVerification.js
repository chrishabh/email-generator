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

}

window.addEventListener('load',fn)