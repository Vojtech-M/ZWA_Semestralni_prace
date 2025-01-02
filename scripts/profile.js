function toggleDisplay(buttonId, formClass) {
    document.getElementById(buttonId).addEventListener('click', function() {
        const element = document.querySelector(`.${formClass}`);
        element.classList.toggle('hidden');  // Toggle the 'hidden' class
    });
}

toggleDisplay('add_user', 'addForm');
