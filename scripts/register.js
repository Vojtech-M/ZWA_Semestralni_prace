// Input fields
const firstnameInput = document.getElementById("firstname");
const lastnameInput = document.getElementById("lastname");
const addressInput = document.getElementById("address_field");
const postalInput = document.getElementById("postal");
const emailInput = document.getElementById("email_field");
const phoneInput = document.getElementById("phone_field");
const pass1Input = document.getElementById("pass1_field");
const pass2Input = document.getElementById("pass2_field");

// Error display fields
const firstNameError = document.getElementById("firstNameError");
const lastNameError = document.getElementById("lastNameError");
const addressError = document.getElementById("address_fieldError");
const postalError = document.getElementById("postalError");
const emailError = document.getElementById("emailError");
const phoneError = document.getElementById("phone_fieldError");
const pass1Error = document.getElementById("pass1Error");
const pass2Error = document.getElementById("pass2Error");
// Get the password input field and toggle button
const passwordInput = document.getElementById("pass1_field");
const passwordToggle = document.getElementById("password-toggle");

// Function to toggle password visibility
function togglePasswordVisibility() {
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        passwordToggle.src = "./img/opened_eye.png";
        passwordToggle.setAttribute("aria-label", "Hide password");
    } else {
        passwordInput.type = "password";
        passwordToggle.src = "./img/closed_eye.png";
        passwordToggle.setAttribute("aria-label", "Show password");
    }
}

// Add event listener to the toggle button
passwordToggle.addEventListener("click", togglePasswordVisibility);

// Optional: Add keyboard accessibility for the toggle button
passwordToggle.addEventListener("keydown", (event) => {
    if (event.key === "Enter" || event.key === " ") {
        togglePasswordVisibility();
        event.preventDefault(); // Prevent default behavior for space key
    }
});



// Function to check the validity of a username (firstname or lastname)
function checkUsername(inputField, errorElementId) {
    const value = inputField.value.trim();
    const validUsernamePattern = /^[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{3,}$/; // At least 3 letters

    if (!validUsernamePattern.test(value)) {
        document.getElementById(errorElementId).innerText =
            "Pole musí být delší než 3 znaky a může obsahovat pouze písmena.";
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}

// Function to check phone number validity
function checkPhoneNumber(inputField, errorElementId) {
    const value = inputField.value.trim();
    const phonePattern = /^[0-9]{9}$/; // 9 digits for Czech phone numbers

    if (!phonePattern.test(value)) {
        document.getElementById(errorElementId).innerText =
            "Telefonní číslo musí mít 9 čísel.";
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}

// Function to check postal code validity
function checkPostalCode(inputField, errorElementId) {
    const value = inputField.value.trim();
    const postalCodePattern = /^[0-9]{3} ?[0-9]{2}$/; // Czech postal code format

    if (!postalCodePattern.test(value)) {
        document.getElementById(errorElementId).innerText =
            "Neplatné PSČ. Použijte formát 123 45.";
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}

// Function to check email validity
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

// Function to check address (simple validation for now)
function checkAddress(inputField, errorElementId) {
    const value = inputField.value.trim();
    if (value.length < 5) {
        document.getElementById(errorElementId).innerText =
            "Adresa musí být delší než 5 znaků.";
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}

// Function to check if the passwords match
function checkPasswordMatch(pass1Input, pass2Input, errorElementId) {
    if (pass1Input.value !== pass2Input.value) {
        document.getElementById(errorElementId).innerText =
            "Hesla se neshodují."; // Passwords do not match
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}

// Add event listener to dynamically check password match
pass2Input.addEventListener("input", function () {
    checkPasswordMatch(pass1Input, pass2Input, "pass2Error");
});





// Form submission handler
document.getElementById("registrationForm").addEventListener("submit", function (event) {
    const isFirstnameValid = checkUsername(firstnameInput, "firstNameError");
    const isLastnameValid = checkUsername(lastnameInput, "lastNameError");
    const isAddressValid = checkAddress(addressInput, "address_fieldError");
    const isPostalValid = checkPostalCode(postalInput, "postalError");
    const isEmailValid = checkEmail(emailInput, "emailError");
    const isPhoneValid = checkPhoneNumber(phoneInput, "phone_fieldError");
    const isPasswordValid = checkPasswordMatch(pass1Input, pass2Input, "pass2Error");
    // Check if all validations passed
    if (!isFirstnameValid || !isLastnameValid || !isAddressValid || !isPostalValid || !isEmailValid || !isPhoneValid || !isPasswordValid) {
        console.warn("Form validation failed. Submission prevented.");
        event.preventDefault(); // Prevent the form from submitting
    } else {
        console.log("Form validation passed. Submission allowed.");
    }
});
