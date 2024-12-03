<?php
session_start(); // Start session

$firstname = $lastname = $email = $phone = '';
$errors = [];
$formValid = true; // formulář je na začátku valid



function validateName($name, $minLength = 3, $maxLength = 50) {
    // Check length
    if (strlen($name) < $minLength || strlen($name) > $maxLength) {
        return "Jméno musí být mezi $minLength a $maxLength znaky dlouhé.";
    }

    // Check for only valid characters (letters with no spaces or special characters)
    if (!preg_match("/^[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]+$/", $name)) {
        return "Jméno může obsahovat pouze písmena bez mezer nebo speciálních znaků.";
    }

    // If everything is valid, return null or true
    return null;
}

function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Neplatný formát e-mailu.";
    }
    return null;
}

function validatePhone($phone) {
    // Check for 9 digits (Czech phone number format)   
    if (!preg_match("/^\d{9}$/", $phone)) {
        return "Telefonní číslo musí mít 9 číslic.";
    }
    return null;
}

function validatePassword($password, $confirmPassword) {
    // Password must be at least 8 characters long
    if (strlen($password) < 8) {
        return "Heslo musí mít alespoň 8 znaků.";
    }

    // Passwords must match
    if ($password !== $confirmPassword) {
        return "Hesla se neshodují.";
    }

    // Check for at least one uppercase letter, one lowercase letter, and one number
    if (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/\d/", $password)) {
        return "Heslo musí obsahovat alespoň jedno velké písmeno, jedno malé písmeno a jedno číslo.";
    }

    return null;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $passwd = htmlspecialchars(trim($_POST['passwd']));
    $id = uniqid();
// nahrání file na server
    $file = $_FILES['file']; 
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));


    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed)){
        if ($fileError === 0){
            if ($fileSize < 2000000){
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = './uploads/' . $fileNameNew;
                move_uploaded_file($fileTmpName,  $fileDestination);
                

            } else {
                $fileUploadError = "File is too big";
                $formValid = false;
            }
        } else {
            $fileUploadError = "There was error uploading file";
            $formValid = false;
        }
    } else {
        $fileUploadError = "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
        $formValid = false;
        $errors['file'];
    }   
    /*
    kontrolovat
    jmeno
    prijmeni
    adresa
    PSČ
    email
    telefon
    heslo1
    heslo2
    */






    $errors['firstname'] = validateName($firstname);
    $errors['lastname'] = validateName($lastname);
    $errors['email']= validateEmail($email);
    if (strlen($phone) > 0) {
        $errors['phone'] = validatePhone($phone);
    }    
    $errors['passwd'] = validatePassword($passwd, $_POST['passwd2']);
  
    $errors = array_filter($errors);
    if (empty($errors)) {
        $formValid=True;
    } else {
        $formValid=False;
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
            $emailError = "Tento e-mail již je zaregistrován.";
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
            'profile_picture' => isset($fileNameNew) ? $fileDestination : './img/profile.png' // Save uploaded file or use default
        ];

        // Get existing data from the JSON file (if it exists)
        $file = './user_data/users.json';
        if (file_exists($file)) {
            $jsonData = file_get_contents($file);
            $jsonArray = json_decode($jsonData, true);
        } else {
            $jsonArray = [];
        }
        $jsonArray[] = $data;

        // Convert array back to JSON and save to file
        file_put_contents($file, json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        //echo "Registrace proběhla úspěšně, vítej, $firstname.";
        header("Location: prihlaseni.php");
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

<section class="registrace">
    <div class ="formular">
        <div class="registration_field">
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
                    <input id="email_field" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="example@mail.com" tabindex="5">
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
                    <div class="error" id="emailError"></div>
                    <label for="phone_field" class="phone_label">Telefonní číslo</label>
                    <input id="phone_field" type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="606136603" tabindex="6">
                <?php if (isset($errors['phone'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['phone']) ?></div>
                <?php endif; ?>                   
                    <div class="error" id="phone_fieldError"></div>
                   
                </div>
                <div class="form_field">
                    <label for="pass1_field" class="required_label">Heslo</label>
                    <!--<input id="pass1_field" type="password" name="passwd" required placeholder="Heslo" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" tabindex="7"> -->
                    <input type="password" id="pass1_field" name="passwd" placeholder="Heslo" required title="Heslo musí být minimálně 8 znaků"aria-required="true" />
                
                    <img id="password-toggle" src="./img/closed_eye.png" alt="Toggle password visibility" role="button" tabindex="0" aria-label="Show password" />      
                    
                    <div class="error" id="pass1Error"></div>
                    <label for="pass2_field" class="required_label">Heslo znovu</label>
                    <input id="pass2_field" type="password" name="passwd2" required placeholder="Heslo znovu" tabindex="8" >
                <?php if (isset($errors['passwd'])): ?>
                    <div class="error" id="pass2Error"><?= htmlspecialchars($errors['firstname']) ?></div>
                <?php endif; ?>
                    <div class="error" id="pass2Error"></div>
                </div>  
    
                <div class="form_field">
                    <label for="agreement_field" class="required_label">Souhlasím s <a href="conditions.html" target="blank">podmínkami</a></label>
                    <input id="agreement_field" type="checkbox" name="agreement" required tabindex="9">
                </div>
                <div class="form_field">
                    <input type="file" id="myFile" name="file" >
                </div>
                <div class="error">
                    <?php echo isset($fileUploadError) ? htmlspecialchars($fileUploadError) : ''; ?>
                </div>
                <input id="submit" type="submit" value="Registrovat se" tabindex="10">             
                
            </form>
        </div>
        <div class="registration_banner">
                <h2>Přidej se k nám a získej výhody!</h2>
            </div>
        </div>
</section>
<script src="./scripts/register.js" type=module> </script> 
<?php include './php/structure/footer.php'; ?>
</body>
</html>