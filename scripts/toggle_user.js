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