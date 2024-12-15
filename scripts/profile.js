document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.querySelector("button.toggleEditForm");
    toggleButton.addEventListener("click", function () {
        const element = document.querySelector(".editForm");
        if (element.style.display === "none" || element.style.display === "") {
            element.style.display = "flex";
        } else {
            element.style.display = "none";
        }
    });
});
document.getElementById('add_user').addEventListener('click', function() {
    //alert('Tlačítko bylo stisknuto!');
    const element = document.querySelector(".addForm");
    if (element.style.display === "none" || element.style.display === "") {
        element.style.display = "flex";
    } else {
        element.style.display = "none";
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll("button.toggleEditReservationForm");
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            const form = this.nextElementSibling;
            if (form.classList.contains("hidden")) {
                form.classList.remove("hidden");
            } else {
                form.classList.add("hidden");
            }
        });
    });
});
