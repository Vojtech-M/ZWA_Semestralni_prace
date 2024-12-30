const emailInput = document.getElementById("email");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");
const pass1Input = document.getElementById("password");

import { checkEmail } from './register.js';
import { checkPassword } from './register.js';

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
