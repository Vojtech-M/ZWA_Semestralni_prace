// Import validation functions for passwords
import { checkPassword,checkPasswordMatch } from "./validation.js";

function toggleDisplay(buttonId, formClass) {
    document.getElementById(buttonId).addEventListener('click', function() {
        const element = document.querySelector(`.${formClass}`);
        element.classList.toggle('hidden');  // Toggle the 'hidden' class
    });
}

toggleDisplay('add_user', 'addForm');

document.addEventListener("DOMContentLoaded", () => {
    // Existing password fields for updating the user profile
    const update_self_password = document.getElementById("current_password");
    const currentPasswordField = document.getElementById("current_password_change");
    const newPasswordField = document.getElementById("new_password_change");
    const confirmPasswordField = document.getElementById("confirm_password_change");

    // Add event listeners for the update form's password fields
    update_self_password.addEventListener("input", function() {
        checkPassword(update_self_password, "update_self_password_error");
    });

    currentPasswordField.addEventListener("input", () => {
        checkPassword(currentPasswordField, "password_error_current_password");
    });

    newPasswordField.addEventListener("input", () => {
        checkPassword(newPasswordField, "password_error_new_password");
    });

    confirmPasswordField.addEventListener("input", () => {
        checkPassword(confirmPasswordField, "password_error_confirm_new_password");
    });

    // Add event listeners for the password fields in the add user form
    confirmPasswordField.addEventListener("input", function () {
        checkPasswordMatch(newPasswordField, confirmPasswordField, "password_error_confirm_new_password");
    });



    // New password reset form validation
    const newUserPasswordResetField = document.getElementById("new_password_reset");
    const confirmUserPasswordResetField = document.getElementById("confirm_password_reset");

    newUserPasswordResetField.addEventListener("input", () => {
        checkPassword(newUserPasswordResetField, "password_error_new_password_reset");
    }
    );
    confirmUserPasswordResetField.addEventListener("input", () => {
        checkPassword(confirmUserPasswordResetField, "password_error_confirm_new_password_reset");
    }
    );

    confirmUserPasswordResetField.addEventListener("input", function () {
        checkPasswordMatch( newUserPasswordResetField,confirmUserPasswordResetField, "password_error_confirm_new_password_reset");
    });
    
    const password_add_user = document.getElementById("password_add_user");
    const confirm_password_add_user = document.getElementById("confirm_password_add_user");

    password_add_user.addEventListener("input", () => {
        checkPassword(password_add_user, "password_error_add_user");
    });
    confirm_password_add_user.addEventListener("input", () => {
        checkPassword(confirm_password_add_user, "password_error_confirm_add_user");
    });

    confirm_password_add_user.addEventListener("input", function () {
        checkPasswordMatch(password_add_user, confirm_password_add_user, "password_error_confirm_add_user");
    });
});
