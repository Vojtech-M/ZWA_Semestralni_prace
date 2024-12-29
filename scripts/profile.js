function toggleDisplay(buttonId, formClass) {
    document.getElementById(buttonId).addEventListener('click', function() {
        const element = document.querySelector(`.${formClass}`);
        if (element.style.display === "none" || element.style.display === "") {
            element.style.display = "flex";
        } else {
            element.style.display = "none";
        }
    });
}

// Toggle display of add user form
toggleDisplay('add_user', 'addForm');


