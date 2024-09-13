function enableFormFields(card) {
    const fields = card.querySelectorAll('input, select, textarea');
    const editIcon = card.querySelector('.edit-icon');
    const buttons = card.querySelectorAll('.updated-buttons');
    
    // Remove disabled attribute from form fields
    fields.forEach(field => field.removeAttribute('disabled'));

    // Hide the edit icon
    editIcon.style.display = 'none';

    // Show the buttons
    buttons.forEach(button => {
        button.classList.remove('hide-btn');
        button.classList.add('show-btn');
    });
}

function cancelEdit(card) {
    const fields = card.querySelectorAll('input, select, textarea');
    const editIcon = card.querySelector('.edit-icon');
    const buttons = card.querySelectorAll('.updated-buttons');

    // Add disabled attribute back to form fields
    fields.forEach(field => field.setAttribute('disabled', 'disabled'));

    // Show the edit icon
    editIcon.style.display = 'block';

    // Hide the buttons
    buttons.forEach(button =>{
        button.classList.add('hide-btn');
        button.classList.remove('show-btn');
    });
}

function init(){
    // Add event listeners to all edit icons
    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const card = icon.closest('.card');
            console.log(card);
            enableFormFields(card);
        });
    });


    // Add event listeners to all cancel buttons
    document.querySelectorAll('.btn-cancel').forEach(button => {
        button.addEventListener('click', function() {
            const card = button.closest('.card');
            cancelEdit(card);
        });
    });
}




document.addEventListener('DOMContentLoaded',init)