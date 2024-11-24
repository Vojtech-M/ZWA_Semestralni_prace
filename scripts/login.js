const emailInput = document.getElementById("email");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");

const pass1Input = document.getElementById("password");
function checkEmail(inputField, errorElementId) {
    const value = inputField.value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email validation

    if (!emailPattern.test(value)) {
        document.getElementById(errorElementId).innerText =
            "Neplatný formát e-mailu.";
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}

function checkPassword(inputField, errorElementId) {
    const value = inputField.value.trim();
    if (value.length < 8) {
        document.getElementById(errorElementId).innerText =
            "Heslo musí být delší než 8 znaků.";
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}

document.getElementById("loginForm").addEventListener("submit", function (event) {
    const isEmailValid = checkEmail(emailInput, "emailError");
    const isPasswordValid = checkPassword(pass1Input, "passwordError"); // Correct reference here

    if (!isEmailValid || !isPasswordValid) {
        console.warn("Form validation failed. Submission prevented.");
        event.preventDefault(); // Prevent the form from submitting
    } else {
        console.log("Form validation passed. Submission allowed.");
    }
});
