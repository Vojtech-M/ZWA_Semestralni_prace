<?php
session_start(); // Start session

include "./php/validation.php";

$firstname = $lastname = $address = $postal = $email = $phone = '';

$formValid = true; // formulář je na začátku valid

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $address = htmlspecialchars(trim($_POST['address']));
    $postal = htmlspecialchars(trim($_POST['postal']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $passwd = htmlspecialchars(trim($_POST['passwd']));

/*
    $file = $_FILES['file']; // nahrání file na server
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
            if ($fileSize < 1000000000000){
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = '/home/michavo5/' . $fileNameNew;
                move_uploaded_file($fileTmpName,  $fileDestination);
                

            } else {
                echo "File is too big";
            }
        } else {
            echo "There was error uploading file";
        }
    } else {
        echo "You cannot upload files of this type !";
    }
*/
    if ($passwd !== $_POST['passwd2']) {
        echo "Passwords do not match.";
        $formValid = false;
    }


    $hash = password_hash($passwd, PASSWORD_DEFAULT);  // zaheshování hesla

    $usernameValid = validateUsername($firstname, 3); 
    if (!$usernameValid) {
        echo "Invalid username.";
        $formValid = false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        $formValid = false;
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
            'password' => $hash
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
    <script defer src="./scripts/validation.js"></script>
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
                    <div class="error" id="firstNameError"></div>
                    <label for="lastname"  class="required_label">Příjmení</label>
                    <input type="text" id="lastname" name="lastname" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*" value="<?php echo htmlspecialchars($lastname); ?>" placeholder="Novák"  tabindex="2">
                </div>
                <div class="form_field">
                    <label for="address_field" class="required_label">Adresa</label>
                    <input type="text" id="address_field" name="address" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z0-9 ]*" value="<?php echo htmlspecialchars($address); ?>" required placeholder="Zahradní 80" tabindex="3">
                    <label for="postal" class="PSČ"> PSČ</label>
                    <input id="postal" type="text" name="postal" pattern="[0-9]{3} ?[0-9]{2}" value="<?php echo htmlspecialchars($postal); ?>" required placeholder="251 47" tabindex="4">
                </div>
                <div class="form_field">
                    <label for="email_field" class="required_label">Email</label>
                    <input id="email_field" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="example@mail.com" tabindex="5">
                    <label for="phone_field" class="phone_label">Telefonní číslo</label>
                    <input id="phone_field" type="text" name="phone" pattern="[0-9]{9}" value="<?php echo htmlspecialchars($phone); ?>" placeholder="606136603" tabindex="6">
                </div>
                <div class="form_field">
                    <label for="pass1_field" class="required_label">Heslo</label>
                    <input id="pass1_field" type="password" name="passwd" required placeholder="Heslo" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" tabindex="7">
                    <label for="pass2_field" class="required_label">Heslo znovu</label>
                    <input id="pass2_field" type="password" name="passwd2" required placeholder="Heslo znovu" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Hesla se musejí schodovat" tabindex="8" >
                </div>               
                <div class="form_field">
                    <label for="agreement_field" class="required_label">Souhlasím s <a href="conditions.html" target="blank">podmínkami</a></label>
                    <input id="agreement_field" type="checkbox" name="agreement" required tabindex="9">
                    <div id="passwordMismatchMessage">Passwords do not match.</div>
                </div>
                <div class="form_field">
                    <input type="file" id="myFile" name="file">
                </div>
                <input id="reg_submit" type="submit" value="Registrovat se" tabindex="10">             
                
            </form>
        </div>
        <div class="registration_banner">
                <h2>Přidej se k nám a získej výhody!</h2>
            </div>
        </div>
</section>
<script src="./scripts/register.js" type=module> </script> 
<?php include './php/structure/footer.php'; ?>
<script src="./scripts/register.js"></script>
</body>
</html>

<!--<script>
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