// Function to check password validity
export function checkPassword(inputField, errorElementId) {
  const value = inputField.value.trim();
  const validPasswordPattern = /^(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])(?=.*[A-ZĚŠČŘŽÝÁÍÉ])(?=.*[a-zěščřžýáíé]).{8,50}$/;

  if (!validPasswordPattern.test(value)) {
      document.getElementById(errorElementId).innerText =
          "Heslo musí být delší než 8 znaků, kratší než 50 a obsahovat minimálně jedno velké písmeno,číslici a speciální znak (!@#$%^&*(),.?\":{}|<></>).";
      return false;
  } else {
      document.getElementById(errorElementId).innerText = ""; // Clear error
      return true;
  }
}
 // Function to check the validity of a username (firstname or lastname)
export function checkUsername(inputField, errorElementId) {
    const value = inputField.value.trim();
    const validUsernamePattern = /^[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{3,50}$/; // At least 3 letters max 50

    if (!validUsernamePattern.test(value)) {
        document.getElementById(errorElementId).innerText =
            "Pole musí být delší než 3 znaky, kratší než 50 a může obsahovat pouze písmena.";
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}
export function checkPhoneNumber(inputField, errorElementId) {
    const value = inputField.value.trim();
    const phonePattern = /^[0-9]{9}$/; // 9 digits


    if (value === "") {
        // If the field is empty, no error (it is optional)
        document.getElementById(errorElementId).innerText = "";
        return true;
    }
    if (!phonePattern.test(value)) {
        document.getElementById(errorElementId).innerText =
            "Telefonní číslo musí mít 9 čísel.";
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}
// Function to check email validity
export function checkEmail(inputField, errorElementId) {
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

// Function to check if the passwords match
export function checkPasswordMatch(pass1Input, pass2Input, errorElementId) {
    if (pass1Input.value !== pass2Input.value) {
        document.getElementById(errorElementId).innerText =
            "Hesla se neshodují."; // Passwords do not match
        return false;
    } else {
        document.getElementById(errorElementId).innerText = ""; // Clear error
        return true;
    }
}