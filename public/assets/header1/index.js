 

function showDropDown(event,isDropdownVisible,dropdown,avatar,dropdowns){
    event.stopPropagation();
    isDropdownVisible = true;
    dropdown.classList.add('show');
    return isDropdownVisible

}
function hideDropDown(event,isDropdownVisible,dropdown,avatar,dropdowns){
    let isHoverinDropDown = Array.from(dropdowns).some((dropdown) => dropdown.contains(event.target));
    let isClickInsideAvatar = avatar.contains(event.target);
    if (!isHoverinDropDown && !isClickInsideAvatar && isDropdownVisible) {
        dropdown.classList.remove('show');
        isDropdownVisible = false;
         
    }
    return isDropdownVisible
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


document.addEventListener('DOMContentLoaded',()=>{
    var isDropdownVisible = false;
    var dropdown = document.getElementById('myDropdown');
    var avatar = document.querySelector('.user-info .avatar');
    var dropdowns = document.querySelectorAll('.header-dropdown');

    if(avatar){
        avatar.addEventListener('mouseenter', (e)=>{
            isDropdownVisible = showDropDown(e,isDropdownVisible,dropdown,avatar,dropdowns)
        });
    }
    document.addEventListener('mousemove',(e)=>{
        isDropdownVisible = hideDropDown(e,isDropdownVisible,dropdown,avatar,dropdowns)
    });
    
    mobileMenu();
})
