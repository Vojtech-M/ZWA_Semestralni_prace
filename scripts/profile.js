// Import validation functions for passwords
import { checkPassword, checkPasswordMatch } from "./validation.js";

function toggleDisplay(buttonId, formClass) {
    document.getElementById(buttonId).addEventListener('click', function() {
        const element = document.querySelector(`.${formClass}`);
        element.classList.toggle('hidden'); // Toggle the 'hidden' class
    });
}

toggleDisplay('add_user', 'addForm');

document.addEventListener("DOMContentLoaded", () => {
    // 1. Update self password form
    const updateSelfPasswordForm = document.getElementById("updateProfileForm");
    const update_self_password = document.getElementById("current_password");

    update_self_password.addEventListener("input", () => {
        checkPassword(update_self_password, "update_self_password_error");
    });

    updateSelfPasswordForm.addEventListener("submit", function(e) {
        if (!checkPassword(update_self_password, "update_self_password_error")) {
            e.preventDefault(); // Prevent form submission if validation fails
        }
    });

    // 2. Change password form
    const changePasswordForm = document.getElementById("change_password_form");
    const currentPasswordField = document.getElementById("current_password_change");
    const newPasswordField = document.getElementById("new_password_change");
    const confirmPasswordField = document.getElementById("confirm_password_change");

    currentPasswordField.addEventListener("input", () => {
        checkPassword(currentPasswordField, "password_error_current_password");
    });
    newPasswordField.addEventListener("input", () => {
        checkPassword(newPasswordField, "password_error_new_password");
    });
    confirmPasswordField.addEventListener("input", () => {
        checkPassword(confirmPasswordField, "password_error_confirm_new_password");
    });
    confirmPasswordField.addEventListener("input", function () {
        checkPasswordMatch(newPasswordField, confirmPasswordField, "password_error_confirm_new_password");
    });

    changePasswordForm.addEventListener("submit", function(e) {
        const valid = checkPassword(currentPasswordField, "password_error_current_password") &&
                      checkPassword(newPasswordField, "password_error_new_password") &&
                      checkPassword(confirmPasswordField, "password_error_confirm_new_password") &&
                      checkPasswordMatch(newPasswordField, confirmPasswordField, "password_error_confirm_new_password");

        if (!valid) {
            e.preventDefault(); // Prevent submission
        }
    });

    // 3. Reset password form
    const resetPasswordForm = document.getElementById("reset_password_form");
    const newUserPasswordResetField = document.getElementById("new_password_reset");
    const confirmUserPasswordResetField = document.getElementById("confirm_password_reset");

    newUserPasswordResetField.addEventListener("input", () => {
        checkPassword(newUserPasswordResetField, "password_error_new_password_reset");
    });
    confirmUserPasswordResetField.addEventListener("input", () => {
        checkPassword(confirmUserPasswordResetField, "password_error_confirm_new_password_reset");
    });
    confirmUserPasswordResetField.addEventListener("input", function () {
        checkPasswordMatch(newUserPasswordResetField, confirmUserPasswordResetField, "password_error_confirm_new_password_reset");
    });

    resetPasswordForm.addEventListener("submit", function(e) {
        const valid = checkPassword(newUserPasswordResetField, "password_error_new_password_reset") &&
                      checkPassword(confirmUserPasswordResetField, "password_error_confirm_new_password_reset") &&
                      checkPasswordMatch(newUserPasswordResetField, confirmUserPasswordResetField, "password_error_confirm_new_password_reset");

        if (!valid) {
            e.preventDefault(); // Prevent submission
        }
    });

    // 4. Add user form
    const addUserForm = document.getElementById("add_user_form");
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

    addUserForm.addEventListener("submit", function(e) {
        const valid = checkPassword(password_add_user, "password_error_add_user") &&
                      checkPassword(confirm_password_add_user, "password_error_confirm_add_user") &&
                      checkPasswordMatch(password_add_user, confirm_password_add_user, "password_error_confirm_add_user");

        if (!valid) {
            e.preventDefault(); // Prevent submission
        }
    });
});
