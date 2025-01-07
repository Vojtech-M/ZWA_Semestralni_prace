// Job of this file: To handle the login form validation
// imported validation
import { checkPassword, checkEmail } from "./validation.js";

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

    // Form submission handler
    document.getElementById("loginForm").addEventListener("submit", function (event) {
        const isEmailValid = checkEmail(emailField, "emailError");
        const isPasswordValid = checkPassword(passwordField, "passwordError");

        // Prevent submission if either validation fails
        if (!isEmailValid || !isPasswordValid) {
            console.warn("Form validation failed. Submission prevented.");
            event.preventDefault(); // Prevent the form from submitting
        } else {
            console.log("Form validation passed. Submission allowed.");
        }
    });
});
