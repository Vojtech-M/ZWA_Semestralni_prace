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

toggleDisplay('add_user', 'addForm');
toggleDisplay('edit_user', 'editForm');



document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll("button.toggleEditReservationForm");
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            const form = this.nextElementSibling;
            form.classList.toggle("active");
        });
    });
});
