
const firstnameInput = document.getElementById("firstname");
const lastnameInput = document.getElementById("lastname");
const addressInput = document.getElementById("address_field");
const postalInput = document.getElementById("postal");
const emailInput = document.getElementById("email_field");
const phoneInput = document.getElementById("phone_field");

const pass1Input = document.getElementById("pass1_field");
const pass2Input = document.getElementById("pass2_field");
const mismatchMessage = document.getElementById("passwordMismatchMessage");

    
function checkUsername() {
    const username = firstnameInput.value;
    const validUsernamePattern = /^[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{3,}$/; // alespoň 3 znaky

    if (!validUsernamePattern.test(firstnameInput.value)) {
        document.getElementById("firstNameError").innerText = "Jméno musí být delší než 3 znaky a může obsahovat pouze písmena.";
        return false;
    } else {
        document.getElementById("firstNameError").innerText = ""; // Clear the error message
        return true;
    }
}

function checkPasswordMatch() {
        if (pass1Input.value !== pass2Input.value) {
            mismatchMessage.style.display = "block";
            return false;
        } else {
            mismatchMessage.style.display = "none";
            return true;
        }
    }
    // Event listeners
    pass2Input.addEventListener("input", checkPasswordMatch);
    firstnameInput.addEventListener("input", checkUsername); // Validate username on input

    document.getElementById("registrationForm").addEventListener("submit", function(event) {
        const isUsernameValid = checkUsername();
        const isPasswordValid = checkPasswordMatch();
        if (!isUsernameValid || !isPasswordValid) {
        event.preventDefault(); // Prevent form submission if validation fails
    }
    });