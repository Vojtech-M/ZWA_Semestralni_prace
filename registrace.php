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
    <header>
        <nav>
            <div class="left-links">
                <a href="index.php"><img src="./img/logo1.png" height="130" width="240" alt="logo"/></a>
            </div>
            <div class="right-links">
                <a class="links" href="cenik.php">Ceník</a>
                <a class="links" href="restaurace.php">Restaurace</a>
                <a class="links" href="prihlaseni.php">Přihlášení</a>
                <a class="links" href="registrace.php">registrace</a>
            </div>
        </nav>
    </header>

<!--<span id="firstnameError" class="error-message"></span>  Error message placeholder for firstname --> 

    <section class="registrace">
        <div class ="formular">
            <div class="registration_field">
            <form action="registrace.php" method="post"> <!--posílat pomocí POST bezpečnější-->
                <div class="form_field">
                    <label for="firstname" class="required_label">Jméno</label>
                    <input type="text" id="firstname" name="firstname" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*" value="" required="" placeholder="Tomáš" tabindex="1">
                    <label for="lastname"  class="required_label" >Příjmení</label>
                    <input type="text"  id="lastname" name="lastname" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*" value="" placeholder="Novák"  tabindex="2">
                </div>
                <div class="form_field">
                    <label for="address_field" class="required_label">Adresa</label>
                    <input type="text" id="address_field" name="address" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z0-9 ]*" value="" required placeholder="Zahradní 80" tabindex="3">
                    <label for="postal" class="required_label">PSČ</label>
                    <input id="postal" type="text" name="postal" value=""pattern="([0-9]{5})?" required placeholder="251 66" tabindex="4">
                </div>
                <div class="form_field">
                    <label for="email_field"class="required_label">Email</label>
                    <input id="email_field" type="email" name="email" value="@" required tabindex="5" >
                    <label for="phone_field"class="phone_label">Telefonní číslo</label>
                    <input id="phone_field" type="text" name="phone" pattern="([0-9]{9})?" placeholder="606136603" tabindex="6" >
                </div>
                <div class="form_field">
                    <label for="pass1_field"class="required_label">Heslo</label>
                    <input id="pass1_field" type="password" name="passwd" required placeholder="Heslo" tabindex="7">
                    <label for="pass2_field"class="required_label">Heslo znovu</label>
                    <input id="pass2_field" type="password" name="passwd2" required placeholder="Heslo znovu" tabindex="8" >
                </div>
                <div class="form_field">
                    <label for="agreement_field"class="required_label">Souhlasím s <a href="conditions.html" target="blank">podmínkami</a></label>
                    <input id="agreement_field" type="checkbox" name="agreement" required tabindex="+">
                </div>



                <br>
                <input id="reg_submit" type="submit" value="Registrovat se" tabindex="9">
                <h5> Něco je povinný</h5>
            </form>
        </div>

        <div class="registration_banner">
            <h2>Přidej se k nám a získej výhody !</h2>

        </div>
        </div>



    </section>

<div class="echo_user_input">    
    <?php

include "validation.php";


    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstname = htmlspecialchars(trim($_POST['firstname']));
        $lastname = htmlspecialchars(trim($_POST['lastname']));
        $address = htmlspecialchars(trim($_POST['address']));
        $postal = htmlspecialchars(trim($_POST['postal']));
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $passwd = htmlspecialchars(trim($_POST['passwd']));

        $hash = password_hash($passwd,  
        PASSWORD_DEFAULT); 

        $usernameValid = validateUsername($_POST["firstname"],3); // nastavit delku
        $formValid = $usernameValid;

        // Pokud je formulář validní, uloý data do JSON souboru
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
            $file = 'users.json';
            if (file_exists($file)) {
                $jsonData = file_get_contents($file);
                $jsonArray = json_decode($jsonData, true);
            } else {
                $jsonArray = [];
            }

            // Add new data to the existing array
            $jsonArray[] = $data;

            // Convert array back to JSON and save to file
            file_put_contents($file, json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));  # aby v tom JSON nebyl UNICODE ale +ěščřžý
            echo "Registrace proběhla úspěšně, vítej, $firstname.";
            
        } 
        // pokud je někde chyba, tak nic neukládej a napiš error messege       
        else {         
            echo "Registrace se nepovedla, zkus to zvonu";
        }
    }
    ?>
</div>


<footer class="footer">
    <div class="footer-text">
        <p>Motokárové centrum Benešov</p>
    </div>
</footer>
</body>
</html>