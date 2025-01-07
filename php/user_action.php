<?php
/**
 * 
 * Funkce pro zpracování uživatelských akcí
 * 
 * 
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
       echo "Nepovolený přístup!";
    }
    $errors = [];
    $defaultProfilePicture = './img/profile.png';

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_self':
                // Retrieve form data
                $firstname = trim($_POST['firstname']);
                $lastname = trim($_POST['lastname']);
                $email = trim($_POST['email']);
                $phone = trim($_POST['phone']);
                $currentPassword = ($_POST['current_password']);
                $password = $user['password'];
               
                $errors = validateInputs($firstname, $lastname, $email, $phone, $currentPassword, $currentPassword);
                $errors['email'] = check_email($email, $user['id']);
                $errors['current_password'] = validate_current_password($currentPassword, $user['password']);
                $errors = array_filter($errors);
                $currentPassword = password_hash($currentPassword, PASSWORD_DEFAULT);

               if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
                    // No file uploaded, proceed without error
                    $fileNameNew = $user['profile_picture'];
                } else {
                    // Handle file upload
                    $fileUploadResult = handleFileUpload('file');
                    if ($fileUploadResult['success']) {
                        $fileNameNew = $fileUploadResult['filePath'];
                        deleteProfilePicture($user);
                    } else {
                        $formValid = false;
                        $errors['image'] = $fileUploadResult['error']; // Collect file upload error
                    }
                }

                // If no errors, update the user
                if (empty($errors)) {
                    // Update the user data in the database
                    editUser($user['id'], $user['role'],$firstname, $lastname, $email, $phone, $currentPassword, $fileNameNew
                    );
                    $user_data_result = "Profil byl úspěšně aktualizován.";
                }   
            break;

            case 'update_password':
                $currentPassword = ($_POST['current_password_change']);
                $newPassword = ($_POST['new_password_change']);
                $confirmPassword = ($_POST['confirm_password_change']);
                
                $errors['current_password_change'] = validate_current_password($currentPassword, $user['password']);
                $errors['new_password_change'] = validatePassword($newPassword, $confirmPassword);
                $errors = array_filter($errors);
                // If no errors, update the password\
                $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                if (empty($errors)) {
                    $user_data_result =  "Heslo bylo úspěšně změněno.";
                    editUser($user['id'], $user['role'], $user['firstname'], $user['lastname'], $user['email'], $user['phone'], $newPassword, $user['profile_picture']);
                
                }
            break;

            case 'add_user':
                // Retrieve form data
                $firstname_add_user = ($_POST['firstname_add_user']);
                $lastname_add_user = trim($_POST['lastname_add_user']);
                $email_add_user = trim($_POST['email_add_user']);
                $phone_add_user = trim($_POST['phone_add_user']);
                $password_add_user = ($_POST['password_add_user']);
                $confirmPassword_add_user = (trim($_POST['confirm_password_add_user']));
                $role_add_user = (trim($_POST['role_add_user']));
                
                $errors["firstname_add_user"] = validateName($firstname_add_user);
                $errors["lastname_add_user"] = validateName($lastname_add_user);
                $errors["email_add_user"] = validateEmail($email_add_user);
                $errors["phone_add_user"] = validatePhone($phone_add_user);
                $errors["password_add_user"] = validatePassword($password_add_user, $confirmPassword_add_user);

                // Validate the user inpu
                $errors['email_add_user'] = check_email($email_add_user);
                // Validate the file upload
                $fileUploadResult = handleFileUpload('file_add_user');
                if ($fileUploadResult['success']) {
                    $fileNameNew = $fileUploadResult['filePath'];
                } else {
                    if (isset($fileUploadResult['noFile']) && $fileUploadResult['noFile'] === true) {
                        $fileNameNew = $defaultProfilePicture;
                    } else {
                        $formValid = False;
                        $errors['image_add_user'] = $fileUploadResult['error']; // Collect file upload error
                    }
                } 
                $errors = array_filter($errors);
                // If no errors, insert the new user into the database
                if (empty($errors)) {
                    // Insert the new user into the database
                    addUser($role_add_user,$firstname_add_user, $lastname_add_user, $email_add_user, $phone_add_user, $password_add_user, $fileNameNew);
                    $user_data_result = "Uživatel byl úspěšně přidán.";
                }
            break;

            case "edit_user":

                $id_edit_user = trim($_POST['id_edit_user']);
                $firstname_edit_user = trim($_POST['firstname_edit_user']);
                $lastname_edit_user = trim($_POST['lastname_edit_user']);
                $email_edit_user = trim($_POST['email_edit_user']);
                $phone_edit_user = trim($_POST['phone_edit_user']);
                $role_edit_user = trim($_POST['role_edit_user']);

                $errors['id_edit_user'] = doesIDExist($id_edit_user);
                $errors['email_edit_user'] = validateEmail($email_edit_user, $id_edit_user);
                $errors['firstname_edit_user'] = validateName($firstname_edit_user);
                $errors['lastname_edit_user'] = validateName($lastname_edit_user);
                $errors['phone_edit_user'] = validatePhone($phone_edit_user);

                $errors = array_filter($errors);
                // Handle file upload for profile picture
                $fileUploadResult = handleFileUpload('file_edit_user');
                $user = getDataById($id_edit_user);
                if ($fileUploadResult['success']) {
                    $fileNameNew_edit_user = $fileUploadResult['filePath'];
                    deleteProfilePicture($user);
                } else {
                    if (isset($fileUploadResult['noFile']) && $fileUploadResult['noFile'] === true) {
                        $fileNameNew_edit_user = $user['profile_picture'];
                    } else {
                        $formValid = False;
                        $errors['image_edit_user'] = $fileUploadResult['error']; // Collect file upload error
                    }
                } 
                $user = getDataById($id_edit_user);
                // If no errors, update the user in the database
                if (empty($errors)) {
                
                    // Update the user data in the database (assuming you have a function like this)
                    editUser($id_edit_user, $role_edit_user, $firstname_edit_user, $lastname_edit_user, $email_edit_user, $phone_edit_user, $user['password'], $fileNameNew_edit_user);
                    $user_data_result = "Uživatel byl úspěšně aktualizován.";
                    // Redirect to the profile page after successful update
                }
            break;

            case 'reset_password':
                // Code for resetting a user's password by ID
                // Retrieve form data
                $user_id_reset = trim($_POST['user_id_reset']);
                $newPassword_reset = ($_POST['new_password_reset']);
                $confirmPassword_reset = ($_POST['confirm_password_reset']);
                $errors['user_id_reset'] = doesIDExist($user_id_reset);

                $user_to_change = getDataById($user_id_reset);
                // Validate the new password
                $errors['new_password_reset'] = validatePassword($newPassword_reset, $confirmPassword_reset);
                $errors = array_filter($errors);
                // If no errors, reset the password
                $newPassword_reset = password_hash($newPassword_reset, PASSWORD_DEFAULT);
                if (empty($errors)) {
                    // Reset the user's password in the database (assuming you have a function like this)
                    editUser($user_id_reset, $user_to_change['role'], $user_to_change['firstname'], $user_to_change['lastname'], $user_to_change['email'], $user_to_change['phone'], $newPassword_reset, $user_to_change['profile_picture']);
                    // Redirect to the profile page after successful password reset
                    $user_data_result = "Heslo bylo úspěšně změněno.";
                }
            break;

            case 'delete_user':
                // Code for deleting a user by ID
                // Retrieve form data
                $user_id_delete = trim($_POST['user_id_delete']);
                $errors['user_id_delete'] = doesIDExist($user_id_delete);
                $errors = array_filter($errors);
                // If no errors, delete the user
                if (empty($errors)) {
                    // Delete the user from the database
                    deleteUser($user_id_delete);
                    $user_data_result = "Uživatel byl úspěšně smazán.";
                }
            break;
        }
}
}
?>