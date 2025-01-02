// Initiate the validation of the password and email fields

// imported validation
import { checkPassword,checkEmail } from "./validation.js";

document.addEventListener("DOMContentLoaded", () => {
    const passwordField = document.getElementById("password");
    const emailField = document.getElementById("email");
    // Add event listener to password field
    passwordField.addEventListener("input", () => {
        checkPassword(passwordField, "passwordError");
    });
    // Add event listener to email field
    emailField.addEventListener("input", () => {
        checkEmail(emailField, "emailError");
    });
});