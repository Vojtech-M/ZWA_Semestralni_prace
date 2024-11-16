<?php
session_start(); // Start session

include "./php/validation.php";









// Initialize variables
$firstname = $lastname = $address = $postal = $email = $phone = '';

$formValid = true; // Start with form valid as true

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $address = htmlspecialchars(trim($_POST['address']));
    $postal = htmlspecialchars(trim($_POST['postal']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $passwd = htmlspecialchars(trim($_POST['passwd']));


    $file = $_FILES['file'];
    //print_r($file);
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

    if ($passwd !== $_POST['passwd2']) {
        echo "Passwords do not match.";
        $formValid = false;
    }

    $hash = password_hash($passwd, PASSWORD_DEFAULT); 

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

        // Add new data to the existing array
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
                    <label for="postal" class="required_label">PSČ</label>
                    <input id="postal" type="text" name="postal" pattern="[0-9]{3} ?[0-9]{2}" value="<?php echo htmlspecialchars($postal); ?>" required placeholder="251 47" tabindex="4">
                </div>
                <div class="form_field">
                    <label for="email_field" class="required_label">Email</label>
                    <input id="email_field" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required tabindex="5">
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
</div>

<script>

const firstnameInput = document.getElementById("firstname");
const lastnameInput = document.getElementById("lastname");
const addressInput = document.getElementById("address_field");
const postalInput = document.getElementById("postal");
const emailInput = document.getElementById("email_field");
const phoneInput = document.getElementById("phone_field");

const pass1Input = document.getElementById("pass1_field");
const pass2Input = document.getElementById("pass2_field");
const mismatchMessage = document.getElementById("passwordMismatchMessage");

    
function checkUsername() {
    const username = firstnameInput.value;
    const validUsernamePattern = /^[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{4,}$/; // At least 4 characters

    if (!validUsernamePattern.test(firstnameInput.value)) {
        document.getElementById("firstNameError").innerText = "Jméno musí být delší než 3 znaky a může obsahovat pouze písmena.";
        return false;
    } else {
        document.getElementById("firstNameError").innerText = ""; // Clear the error message
        return true;
    }
}

function checkPasswordMatch() {
        if (pass1Input.value !== pass2Input.value) {
            mismatchMessage.style.display = "block";
            return false;
        } else {
            mismatchMessage.style.display = "none";
            return true;
        }
    }
    // Event listeners
    pass2Input.addEventListener("input", checkPasswordMatch);
    firstnameInput.addEventListener("input", checkUsername); // Validate username on input

    document.getElementById("registrationForm").addEventListener("submit", function(event) {
        const isUsernameValid = checkUsername();
        const isPasswordValid = checkPasswordMatch();
        if (!isUsernameValid || !isPasswordValid) {
        event.preventDefault(); // Prevent form submission if validation fails
    }
    });
</script>







        <div class="registration_banner">
            <h2>Přidej se k nám a získej výhody!</h2>
        </div>
    </div>
</section>

<?php include './php/structure/footer.php'; ?>

<script src="./scripts/register.js"></script>
</body>
</html>












