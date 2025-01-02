import { checkPassword,checkUsername,checkPhoneNumber,checkEmail,checkPasswordMatch } from "./validation.js";

// Input fields
const firstnameInput = document.getElementById("firstname");
const lastnameInput = document.getElementById("lastname");
const emailInput = document.getElementById("email_field");
const phoneInput = document.getElementById("phone_field");
const pass1Input = document.getElementById("pass1_field");
const pass2Input = document.getElementById("pass2_field");

// Error display fields
const firstNameError = document.getElementById("firstNameError");
const lastNameError = document.getElementById("lastNameError");
const emailError = document.getElementById("emailError");
const phoneError = document.getElementById("phone_fieldError");
const pass1Error = document.getElementById("pass1Error");
const pass2Error = document.getElementById("pass2Error");

// Get all password input fields and their corresponding toggle buttons
const passwordInputs = document.querySelectorAll(".password-input");
const passwordToggles = document.querySelectorAll(".password-toggle");

// Function to toggle password visibility
function togglePasswordVisibility(event) {
    const button = event.currentTarget;
    const passwordInput = button.previousElementSibling;

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        button.src = "./img/icons/opened_eye.png";
        button.setAttribute("aria-label", "Hide password");
    } else {
        passwordInput.type = "password";
        button.src = "./img/icons/closed_eye.png";
        button.setAttribute("aria-label", "Show password");
    }
}

// Add event listeners to all password toggle buttons
passwordToggles.forEach((toggleButton) => {
    toggleButton.addEventListener("click", togglePasswordVisibility);
    toggleButton.addEventListener("keydown", (event) => {
        if (event.key === "Enter" || event.key === " ") {
            togglePasswordVisibility(event);
            event.preventDefault();
        }
    });
});



// Add event listeners to dynamically check input fields

firstnameInput.addEventListener("input", function () {
    checkUsername(firstnameInput, "firstNameError");
});

lastnameInput.addEventListener("input", function () {
    checkUsername(lastnameInput, "lastNameError");
});

emailInput.addEventListener("input", function () {
    checkEmail(emailInput, "emailError");
}
);
phoneInput.addEventListener("input", function () {
    checkPhoneNumber(phoneInput, "phone_fieldError");
}
);
pass2Input.addEventListener("input", function () {
    checkPasswordMatch(pass1Input, pass2Input, "pass2Error");
});

pass1Input.addEventListener("input", function () {
    checkPassword(pass1Input, "pass1Error");
});

// Form submission handler
document.getElementById("registrationForm").addEventListener("submit", function (event) {
    const isFirstnameValid = checkUsername(firstnameInput, "firstNameError");
    const isLastnameValid = checkUsername(lastnameInput, "lastNameError");
    const isEmailValid = checkEmail(emailInput, "emailError");
    const isPhoneValid = checkPhoneNumber(phoneInput, "phone_fieldError");
    const isPasswordValid = checkPasswordMatch(pass1Input, pass2Input, "pass2Error");
    const ispasswordValid2 = checkPassword(pass1Input, "pass1Error");
    
    // Check if all validations passed
    if (!ispasswordValid2 || !isFirstnameValid || !isLastnameValid  || !isEmailValid || !isPasswordValid || !isPhoneValid) {
        console.warn("Form validation failed. Submission prevented.");
        event.preventDefault(); // Prevent the form from submitting
    } else {
        console.log("Form validation passed. Submission allowed.");
    }
});
