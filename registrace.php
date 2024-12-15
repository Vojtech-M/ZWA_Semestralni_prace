<?php
/**
 * Job: Register users
 * 
 * This file contains a form for user registration. It checks if the user exists in the database and if the password is correct.
 */
$firstname = $lastname = $email = $phone = '';
$errors = [];
$formValid = true; // formulář je na začátku valid
include "./php/check_login.php";
include './php/validation.php';
include './php/file_upload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = htmlspecialchars(trim($_POST['password']));
    $id = uniqid();

    // Handle file upload
    $fileUploadResult = handleFileUpload('file');
    if (!$fileUploadResult['success']) {
        $fileNameNew = './img/profile.png'; // Set default picture if file upload fails
    } else {
        $fileNameNew = $fileUploadResult['filePath'];  
    }

    // Validate inputs
    $errors['firstname'] = validateName($firstname);
    $errors['lastname'] = validateName($lastname);
    $errors['email'] = validateEmail($email);
    $errors['phone'] = validatePhone($phone);
    $errors['password'] = validatePassword($password, $_POST['password2']);

    // Filter out null values from errors
    $errors = array_filter($errors);

    if (empty($errors)) {
        $formValid = true;
    } else {
        $formValid = false;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);  // zaheshování hesla

    // Get existing data from the JSON file (if it exists)
    $file = './user_data/users.json';
    if (file_exists($file)) {
        $jsonData = file_get_contents($file);
        $jsonArray = json_decode($jsonData, true);
    } else {
        $jsonArray = [];
    }

    // Check if the email already exists
    foreach ($jsonArray as $user) {
        if ($user['email'] == $email) {
            $errors['email'] = "Tento e-mail již je zaregistrován.";
            $formValid = false;
            break;
        }
    }

    $role = "user";

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
        header("Location: login.php");
        exit();
    } else {
        //echo "Registrace se nepovedla, zkus to znovu.";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Author" content="Vojtěch Michal">
    <meta name="Keywords" content="motokáry">
    <meta name="description" content="Nejzábavnější motokárová dráha ve středních Čechách.">
    <title>Motokárové centrum Benešov</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/helma.png"> 
    <link rel="stylesheet" href="./css/layout.css">
</head>
<body>
<?php include './php/structure/header.php'; ?> 

    <div class ="login_formular">
        <div class="registration_field">

            <h2>Registrace</h2> 
            <form id="registrationForm" action="registrace.php" method="post" enctype="multipart/form-data">
            <?php  include './php/structure/form_temeplate.php'; ?>
            <div class="form_field">
                <label for="agreement_field" class="required_label">Souhlasím s <a href="conditions.html" target="blank">podmínkami</a></label>
                <input id="agreement_field" type="checkbox" name="agreement" required tabindex="9">
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