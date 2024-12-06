<?php
/**
 * Job: Register users
 */
$firstname = $lastname = $email = $phone = '';
$errors = [];
$formValid = true; // formulář je na začátku valid

include './php/validation.php';
include './php/file_upload.php';
include './php/get_data.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $passwd = htmlspecialchars(trim($_POST['passwd']));
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
    $errors['passwd'] = validatePassword($passwd, $_POST['passwd2']);

    // Filter out null values from errors
    $errors = array_filter($errors);

    if (empty($errors)) {
        $formValid = true;
    } else {
        $formValid = false;
    }

    $hash = password_hash($passwd, PASSWORD_DEFAULT);  // zaheshování hesla

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
</head>
<body>
<?php include './php/structure/header.php'; ?> 

    <div class ="login_formular">
        <div class="registration_field">

            <h2>Registrace</h2> 
            <form id="registrationForm" action="registrace.php" method="post" enctype="multipart/form-data">
                <div class="form_field">
                    <label for="firstname" class="required_label">Jméno</label>
                    <input type="text" id="firstname" name="firstname" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*" value="<?php echo htmlspecialchars($firstname); ?>" placeholder="Tomáš" tabindex="1">
                <?php if (isset($errors['firstname'])): ?>
                    <div class="error" id="firstNameError"><?= htmlspecialchars($errors['firstname']) ?></div>
                <?php endif; ?>

                    <label for="lastname"  class="required_label">Příjmení</label>
                    <input type="text" id="lastname" name="lastname" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*" value="<?php echo htmlspecialchars($lastname); ?>" placeholder="Novák"  tabindex="2">
                <?php if (isset($errors['lastname'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['lastname']) ?></div>
                <?php endif; ?>
                    <div class="error" id="lastNameError"></div>
                </div>

                <div class="form_field">
                    <label for="email_field" class="required_label">Email</label>
                    <input id="email_field" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="example@mail.com" tabindex="3">
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
                    <div class="error" id="emailError"></div>
                    <label for="phone_field" class="phone_label">Telefonní číslo</label>
                    <input id="phone_field" type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="606136603" tabindex="4">
                <?php if (isset($errors['phone'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['phone']) ?></div>
                <?php endif; ?>                   
                    <div class="error" id="phone_fieldError"></div>
                   
                </div>
                <div class="form_field">
                    <label for="pass1_field" class="required_label">Heslo</label>
                    <input type="password" id="pass1_field" name="passwd" placeholder="Heslo" required />
                
                    <img id="password-toggle" src="./img/closed_eye.png" alt="Toggle password visibility" role="button" tabindex="5" aria-label="Show password" />      
                    
                    <div class="error" id="pass1Error"></div>
                    <label for="pass2_field" class="required_label">Heslo znovu</label>
                    <input id="pass2_field" type="password" name="passwd2" required placeholder="Heslo znovu" tabindex="6" >
                <?php if (isset($errors['passwd'])): ?>
                    <div class="error" id="pass2Error"><?= htmlspecialchars($errors['passwd']) ?></div>
                <?php endif; ?>
                    <div class="error" id="pass2Error"></div>
                </div>  
                <div class="form_field">
                    <label for="myFile">Profilový obrázek</label>
                    <input type="file" id="myFile" name="file" >
                </div>
                <div class="error">
                    <?php echo isset($fileUploadError) ? htmlspecialchars($fileUploadError) : ''; ?>
                </div>
                <div class="form_field">
                    <label for="agreement_field" class="required_label">Souhlasím s <a href="conditions.html" target="blank">podmínkami</a></label>
                    <input id="agreement_field" type="checkbox" name="agreement" required tabindex="7">
                </div>
                <p>Políčka označená <span class="red_text">*</span> jsou povinná</p>
                <input id="submit" type="submit" value="Registrovat se" tabindex="8">  
                <p> Máte už účet ? <a class="register_link" href="login.php">Přihlaste se !</a> </p>
            </form>
        </div>
        </div>
<script src="./scripts/register.js" type=module> </script> 
<?php include './php/structure/footer.php'; ?>
</body>
</html>