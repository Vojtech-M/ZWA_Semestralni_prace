<?php
/**
 * Job: Register users
 * 
 * This file contains a form for user registration. It checks if the user exists in the database and if the password is correct.
 */

$firstname = $lastname = $email = $phone = '';
$errors = [];
$formValid = true; // form is valid by default
$defaultProfilePicture = './img/profile.png';

include "./php/check_login.php";
include './php/validation.php';
include './php/file_upload.php';
include "./php/reservation_validation.php";
include './php/data_handler.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $id = uniqid();
    // Default role is user
    $role = "user";
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = htmlspecialchars(trim($_POST['password']));
    $password2 = htmlspecialchars(trim($_POST['password2']));

    $errors = validateInputs($firstname, $lastname, $email, $phone, $password, $password2);

    if (!isset($_POST['agreement'])) {
        $formValid = false;
        $errors['agreement'] = "Musíte souhlasit s podmínkami."; // Error message for not agreeing to terms
    }

   // Handle file upload
   $fileUploadResult = handleFileUpload('file');
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
    // Filter out null values from errors
    $errors = array_filter($errors);

    // Check if there are any errors
    $formValid = empty($errors);
    $hash = password_hash($password, PASSWORD_DEFAULT);  // Hash the password

    $jsonArray = loadUsers();
    check_email($email, null); // Check if the email already exists
    // Check if the email already exists
    foreach ($jsonArray as $user) {
        if ($user['email'] == $email) {
            $errors['email'] = "Tento e-mail je již zaregistrován.";
            $formValid = false;
            break;
        }
    }


    if ($formValid) {
        // Prepare data to be saved into JSON
        $data = [
            'id' => $id,
            'role' => $role,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'phone' => $phone,
            'password' => $hash,
            'profile_picture' => $fileNameNew // Save uploaded file or use default
        ];
        saveDataToJsonFile('./user_data/users.json', $data);
        // Redirect to login page
        header("Location: login.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <?php include "./php/structure/head.html"; ?>
</head>
<body>
<?php include './php/structure/header.php'; ?> 
    <div class ="login_formular">
        <div class="registration_field">
            <h2>Registrace</h2> 
            <form id="registrationForm" action="registrace.php" method="post" enctype="multipart/form-data">
            <?php  include './php/structure/form_temeplate.php'; ?>
            <div class="form_field">
                <label for="agreement_field" class="required_label">Souhlasím s <a href="./pdf/terms_and_conditions.pdf" target="blank">podmínkami</a></label>
                <input id="agreement_field" type="checkbox" name="agreement" required tabindex="9">
                <?php if (isset($errors['agreement'])): ?>
                    <div class="error"><? htmlspecialchars($errors['agreement']) ?></div>
                <?php endif; ?>
            </div>
            <input id="submit" type="submit" value="Registrovat se" tabindex="10">  
            <p> Máte už účet ? <a class="register_link" href="login.php">Přihlaste se !</a> </p>
            </form>
        </div>
        </div>
<script src="./scripts/register.js" type=module> </script> 
<?php include './php/structure/footer.php'; ?>
</body>
</html>