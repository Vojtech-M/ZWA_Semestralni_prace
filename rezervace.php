<?php
session_start();

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Author" content="Vojtěch Michal">
    <meta name="Keywords" content="motokáry"><meta>
    <meta description="Nejzábavnější motokárová dráha ve středních Čechách.">
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
            <form action="rezervace.php" method="post">
                <div id="name">
                    <!-- <label for="firstname" class="custom_text"> </span>*Jméno</label>
                    <input type="text" id="firstname" name="firstname" value=""  placeholder="Tomáš"  tabindex="1">

                    <label for="lastname">Příjmení</label>
                    <input type="text" id="lastname" name="lastname" value="" required placeholder="Novák"  tabindex="2"> -->

                    <label for="reservation_date">Datum rezervace</label>
                    <input type="date" id="reservation_date" name="reservation_date" min='2024-04-04' max='2030-01-01' tabindex="1" required>
                    
                    <label for="reservation_time">Čas rezervace</label>
                    <input type="time" id="reservation_time" name="reservation_time" min="12:00" max="23:00"tabindex="2" required>

                    <label for="quantity">Počet lidí:</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="5" tabindex="3">
                </div>
                <br>
                <input id="reg_submit" type="submit" value="Zarezervovat" tabindex="">
                <h5>* Pole označené jsou povinné</h5>
                <h4>Cena rezervace dle: <a href="cenik.php">Ceník</a></h4>
            </form>
        </div>
    </section>

 <div class="echo_user_input">

<!-- 
pridat do formulare

document query selector form
form event listenr
e. preent default 



-->



<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = isset($_SESSION['firstname']);
    $lastname =isset($_SESSION['firstname']);
  
    // Prepare data to be saved into JSON
    $data = [
        'firstname' =>  $firstname,
        'lastname' =>  $lastname
    ];

    $file = './user_data/reservations.json'; 
    if (file_exists($file)) {
        $jsonData = file_get_contents($file);
        $jsonArray = json_decode($jsonData, true);
    } else {
        $jsonArray = [];
    }

    // Add new data to the existing array
    $jsonArray[] = $data;

    // Convert array back to JSON and save to file
    file_put_contents($file, json_encode($jsonArray, JSON_PRETTY_PRINT));

    // Confirm registration
    echo "Rezervace proběhla úspěšně, vítej, $firstname.";
}

    // File containing the reservation data
    $file = './user_data/reservations.json';

    // Check if the file exists
    if (file_exists($file)) {
        // Read the file content
        $jsonData = file_get_contents($file);

        // Decode JSON data into PHP array
        $reservations = json_decode($jsonData, true);

        // Check if the data was successfully decoded
        if ($reservations) {
            echo "<table>";
            echo "<thead>";
            echo "<tr><th>Jméno</th><th>Příjmení</th></tr>"; // Table headers for first name and last name
            echo "</thead>";
            echo "<tbody>";

            // Loop through the reservations and output each one in a table row
            foreach ($reservations as $reservation) {
                $firstname = htmlspecialchars($reservation['firstname']);
                $lastname = htmlspecialchars($reservation['lastname']);
                echo "<tr><td>$firstname</td><td>$lastname</td></tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "Chyba při čtení dat rezervací.";
        }
    } else {
        echo "Rezervační soubor neexistuje.";
    }
    ?>

</div>
<?php include './php/structure/footer.php'; ?>
</body>
</html>