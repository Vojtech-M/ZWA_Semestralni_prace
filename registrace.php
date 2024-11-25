<?php
session_start(); // Start session

$firstname = $lastname = $address = $postal = $email = $phone = '';
$firstnameError = $lastnameError = $addressError = $postalError = $emailError = $phoneError = $passwordError = $fileUploadError = "";
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


function validateAddress($address, $minLength = 3, $maxLength = 100) {
    // Check length
    if (strlen($address) < $minLength || strlen($address) > $maxLength) {
        return "Adresa musí být mezi $minLength a $maxLength znaky dlouhá.";
    }

    // Allow letters, numbers, spaces, and some special characters
    if (!preg_match("/^[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z0-9 ,.-]+$/", $address)) {
        return "Adresa může obsahovat pouze písmena, číslice a běžné speciální znaky (např. , . -).";
    }

    return null;
}
function validatePostal($postal) {
    // Czech postal code format: 123 45 (3 digits, a space, 2 digits)
    if ($postal == "") {
        $postal == null;
        return null;
    } else {
        if (!preg_match("/^\d{3} \d{2}$/", $postal)) {
            return "PSČ musí být ve formátu 123 45.";
        }
        return null;
    }
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
    $address = htmlspecialchars(trim($_POST['address']));
    $postal = htmlspecialchars(trim($_POST['postal']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $passwd = htmlspecialchars(trim($_POST['passwd']));

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






    $firstnameError = validateName($firstname);
    if ($firstnameError) {
        $formValid = false;
    }
    
    $lastnameError = validateName($lastname);
    if ($lastnameError) {
        $formValid = false;
    }
    
    $addressError = validateAddress($address);
    if ($addressError) {
        $formValid = false;
    }
    
    $postalError = validatePostal($postal);
    if ($postalError) {

        $formValid = false;
    }
    

    $emailError = validateEmail($email);
    if ($emailError) {
    
        $formValid = false;
    }


    $phoneError = validatePhone($phone);
    if ($phoneError) {

        $formValid = false;
    }
    
    $passwordError = validatePassword($passwd, $_POST['passwd2']);
    if ($passwordError) {
        $formValid = false;
    }


    $hash = password_hash($passwd, PASSWORD_DEFAULT);  // zaheshování hesla

    //$usernameValid = validateUsername($firstname, 3); 
    //if (!$usernameValid) {
    //    echo "Invalid username.";
    //    $formValid = false;
    //}

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format.";
        $formValid = false;
    }
    $email = htmlspecialchars(trim($_POST['email']));
    
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


    if ($formValid) {
        // Prepare data to be saved into JSON
        $data = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'address' => $address,
            'postal' => $postal,
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
        header("Location: index.php");
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
            <div class="error"><?php echo htmlspecialchars($firstnameError); ?></div>
                    <div class="error" id="firstNameError"></div>
                    <label for="lastname"  class="required_label">Příjmení</label>
                    <input type="text" id="lastname" name="lastname" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*" value="<?php echo htmlspecialchars($lastname); ?>" placeholder="Novák"  tabindex="2">
            <div class="error"><?php echo htmlspecialchars($lastnameError); ?></div>
                    <div class="error" id="lastNameError"></div>
                </div>
                <div class="form_field">
                    <label for="address_field" class="required_label">Adresa</label>
                    <input type="text" id="address_field" name="address" value="<?php echo htmlspecialchars($address); ?>" required placeholder="Zahradní 80" tabindex="3">
                    <div class="error" id="address_fieldError"></div>
            <div class="error"><?php echo htmlspecialchars($addressError); ?></div>
                    <label for="postal" class="PSČ"> PSČ</label>
                    <input id="postal" type="text" name="postal" value="<?php echo htmlspecialchars($postal); ?>"placeholder="251 47" tabindex="4">
            <div class="error"><?php echo htmlspecialchars($postalError); ?></div>
                    <div class="error" id="postalError"></div>
                </div>
                <div class="form_field">
                    <label for="email_field" class="required_label">Email</label>
                    <input id="email_field" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="example@mail.com" tabindex="5">
                    <div class="error" id="emailError"></div>
                    <div class="error">
                    <?php echo isset($emailError) ? htmlspecialchars($emailError) : ''; ?>
                    </div>


                    <label for="phone_field" class="phone_label">Telefonní číslo</label>
                    <input id="phone_field" type="text" name="phone" pattern="[0-9]{9}" value="<?php echo htmlspecialchars($phone); ?>" placeholder="606136603" tabindex="6">
                    <div class="error" id="phone_fieldError"></div>
                    <div class="error"><?php echo htmlspecialchars($phoneError ); ?></div>
                </div>
                <div class="form_field">
                    <label for="pass1_field" class="required_label">Heslo</label>
                    <!--<input id="pass1_field" type="password" name="passwd" required placeholder="Heslo" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" tabindex="7"> -->
                    <input type="password" id="pass1_field" name="passwd" placeholder="Heslo" required aria-required="true" />
                <img
                    id="password-toggle"
                    src="./img/closed_eye.png" 
                    alt="Toggle password visibility"
                    role="button"
                    tabindex="0"
                    aria-label="Show password"
                    style="cursor: pointer; width: 24px; height: 24px;"
                />      
                    
                    <div class="error" id="pass1Error"></div>
                    <label for="pass2_field" class="required_label">Heslo znovu</label>
                    <input id="pass2_field" type="password" name="passwd2" required placeholder="Heslo znovu" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  tabindex="8" >
                    <div class="error" id="pass2Error"></div>
                </div>  
                <div class="error"><?php echo htmlspecialchars($passwordError); ?></div>
                

                <div class="form_field">
                    <label for="agreement_field" class="required_label">Souhlasím s <a href="conditions.html" target="blank">podmínkami</a></label>
                    <input id="agreement_field" type="checkbox" name="agreement" required tabindex="9">
                </div>
                <div class="form_field">
                    <input type="file" id="myFile" name="file" required>
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

<!--<script>
pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z0-9 ]*"
pattern="[0-9]{3} ?[0-9]{2}"



                let nameInput = document.querySelector("input#firstname");
                document.querySelector("form").addEventListener("submit", function (event) {
                    event.preventDefault();  // zamezíme odeslání formuláře

                    if (nameInput.value.trim() == "") {
                        alert("Vyplňte jméno.");
                        return;
                    }

                    let xhr = new XMLHttpRequest();     // 1. vytvoříme instanci XMLHttpRequest
                    xhr.open("GET", "http://zwa.toad.cz/passwords.txt"); // 2. otevřeme spojení
                    xhr.addEventListener("load", function (e) {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            let text = xhr.responseText;
                            let names = text.split("\n");

                            if (names.includes(nameInput.value)) {
                                alert("Jméno už existuje");
                                return;
                            }
                            alert("Jméno je v pořádku");
                            nameInput.value = "";
                        } else {
                            console.log("Error: ", xhr.statusText);
                        }
                    });

                    xhr.addEventListener("error", function () {
                        console.log("Error: ", xhr.statusText);
                    });

                    xhr.send(); // 3. odešleme požadavek
                });
            
                </script>
-->