let fn =function(){
    
    let avatar= document.querySelector('.user-info .avatar') 
    if(avatar){
        avatar.addEventListener('mouseenter',showDropDown)
    } 
    window.addEventListener('mousemove',hideDropDown); 
    
    mobileMenu();
}

function showDropDown(event){
   document.getElementById('myDropdown').classList.add('show')
}
function hideDropDown(event){
    let avatar= document.querySelector('.user-info .avatar')
    let dropdowns = document.querySelectorAll('.dropdown-content');
    let IsHoverinDropDown = Array.from(dropdowns).some((dropdown)=>dropdown.contains(event.target))
    if(!IsHoverinDropDown && !avatar.contains(event.target)){
        document.getElementById('myDropdown').classList.remove('show')  
    }
}


function mobileMenu(){
    let openMobileMenuBtn = document.getElementById('openMobileMenu')
    let mobileMenu        = document.getElementById('mobileMenu');
    openMobileMenuBtn.addEventListener('click',(e)=>{
        e.preventDefault()
        mobileMenu.classList.toggle('opened')
        openMobileMenuBtn.classList.toggle('is-active')
        
    if(!mobileMenu.contains(e.target) && !openMobileMenuBtn.contains(e.target)){
        mobileMenu.classList.remove('opened')
        this.classList.remove('is-active')
    }
    })
 

    document.addEventListener('touchstart',(e)=>{
        let tar = e.target;
        if(!mobileMenu.contains(tar) && !openMobileMenuBtn.contains(tar)){
            mobileMenu.classList.remove('opened')
            this.classList.remove('is-active')
        }
    })
}


document.addEventListener('DOMContentLoaded',fn)
