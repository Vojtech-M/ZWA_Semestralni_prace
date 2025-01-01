<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token mismatch');
    }
    $errors = [];

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_self':
                // Retrieve form data
                $firstname = htmlspecialchars(trim($_POST['firstname']));
                $lastname = htmlspecialchars(trim($_POST['lastname']));
                $email = htmlspecialchars(trim($_POST['email']));
                $phone = htmlspecialchars(trim($_POST['phone']));
                $currentPassword = htmlspecialchars(trim($_POST['current_password']));
                $password = $user['password'];
               
                $errors = validateInputs($firstname, $lastname, $email, $phone, $currentPassword, $currentPassword);
                $errors['email'] = check_email($email, $user['id']);
                $errors['current_password'] = validate_current_password($currentPassword, $user['password']);

                $errors = array_filter($errors);

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
                    editUser(
                        $user['id'], 
                        $user['role'], 
                        $firstname, 
                        $lastname, 
                        $email, 
                        $phone, 
                        $currentPassword,
                        $fileNameNew
                    );
                }   
            break;

case 'update_password':
  // Retrieve form data
    $currentPassword = htmlspecialchars(trim($_POST['current_password']));
    $newPassword = htmlspecialchars(trim($_POST['new_password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm_password']));
    
    $errors['current_password_change'] = validate_current_password($currentPassword, $user['password']);
    $errors['new_password'] = validatePassword($newPassword, $confirmPassword);
    $errors = array_filter($errors);
    // If no errors, update the password
    if (empty($errors)) {
        editUser($user['id'], $user['role'], $user['firstname'], $user['lastname'], $user['email'], $user['phone'], $newPassword, $user['profile_picture']);
        echo "Heslo bylo úspěšně změněno.";
    }
break;

case 'add_user':
    // Retrieve form data
    $firstname_add = htmlspecialchars(trim($_POST['firstname_add_user']));
    $lastname = htmlspecialchars(trim($_POST['lastname_add_user']));
    $email = htmlspecialchars(trim($_POST['email_add_user']));
    $phone = htmlspecialchars(trim($_POST['phone_add_user']));
    $password = htmlspecialchars(trim($_POST['password_add_user']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm_password_add_user']));
    $role = htmlspecialchars(trim($_POST['role_add_user']));
    
    $errors["firstname_add_user"] = validateName($firstname_add);
    $errors["lastname_add_user"] = validateName($lastname);
    $errors["email_add_user"] = validateEmail($email);
    $errors["phone_add_user"] = validatePhone($phone);
    $errors["password_add_user"] = validatePassword($password, $confirmPassword);

    // Validate the user inpu

    $errors['email_add_user'] = check_email($email);
    var_dump($errors);
    // Validate the file upload
    $fileUploadResult = handleFileUpload('file_add_user');
    if ($fileUploadResult['success']) {
        $fileNameNew = $fileUploadResult['filePath'];
    } else {
        if (isset($fileUploadResult['noFile']) && $fileUploadResult['noFile'] === true) {
            $fileNameNew = $defaultProfilePicture;
        } else {
            $formValid = False;
            $errors['image'] = $fileUploadResult['error']; // Collect file upload error
        }
    } 
    $errors = array_filter($errors);
    // If no errors, insert the new user into the database
    if (empty($errors)) {
        // Insert the new user into the database
        addUser($role,$firstname_add, $lastname, $email, $phone, $password, $fileNameNew);
        echo "<p>Uživatel byl úspěšně přidán.</p>";
    }
break;

case "edit_user":

    $userId = htmlspecialchars(trim($_POST['id_edit_user']));
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $role = htmlspecialchars(trim($_POST['role']));

    $errors['id_edit_user'] = doesIDExist($userId);
    $errors['email_edit_user'] = check_email($email, $userId);
    $errors = validateInputs($firstname, $lastname, $email, $phone, null, null);

    // Handle file upload for profile picture
    $fileUploadResult = handleFileUpload('file');
    if ($fileUploadResult['success']) {
        $fileNameNew = $fileUploadResult['filePath'];
    } else {
        $fileNameNew = './img/profile.png'; // Default profile picture
    }
    // If no errors, update the user in the database
    if (empty($errors)) {
        // Update the user data in the database (assuming you have a function like this)
        editUser($userId, $role, $firstname, $lastname, $email, $phone, $user['password'], $fileNameNew);
        // Redirect to the profile page after successful update
        header("Location: ./profil.php");
        exit;
    }
break;

case 'reset_password':
    // Code for resetting a user's password by ID

    // Retrieve form data
    $userId = htmlspecialchars(trim($_POST['user_id']));
    $newPassword = htmlspecialchars(trim($_POST['new_password']));

    $errors['user_id'] = doesIDExist($userId);

    $user_to_change = getDataById($userId);
    // Validate the new password
    $errors['new_password'] = validatePassword($newPassword, $newPassword);
    var_dump($errors);
    $errors = array_filter($errors);
    // If no errors, reset the password
    if (empty($errors)) {
        // Reset the user's password in the database (assuming you have a function like this)
        editUser($userId, $user_to_change['role'], $user_to_change['firstname'], $user_to_change['lastname'], $user_to_change['email'], $user_to_change['phone'], $newPassword, $user_to_change['profile_picture']);
        // Redirect to the profile page after successful password reset
        echo "Heslo bylo úspěšně změněno.";
        exit;
    }

// header("Location: ./profil.php");
// exit;

case 'delete_user':
    // Code for deleting a user by ID

    // Retrieve form data
    $userId = htmlspecialchars(trim($_POST['user_id_delete']));
    $errors['user_id_delete'] = doesIDExist($userId);
    $errors = array_filter($errors);
    // If no errors, delete the user
    if (empty($errors)) {
        // Delete the user from the database (assuming you have a function like this)
        deleteUser($userId);
        // Redirect to the profile page after successful deletion
        header("Location: ./profil.php");
        exit;
    }
}
}
}
?>