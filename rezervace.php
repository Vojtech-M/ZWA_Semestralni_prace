<?php
 include "./php/check_login.php";

 if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $user = getDataById($user_id);
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

<?php  ?>
    <section class="registrace">
        <div class ="formular">
            <form action="rezervace.php" method="post">
                <div id="name">
                    <label for="reservation_date">Datum rezervace</label>
                    <input type="date" id="reservation_date" name="reservation_date" min='2024-04-04' max='2030-01-01' tabindex="1" required>
                    
                    <label for="timeslot">Čas rezervace</label>
                        <select name="timeslot" id="cars">
                        <option value="14">14:00 - 15:00</option>
                        <option value="15">15:00 - 16:00</option>
                        <option value="16">16:00 - 17:00</option>
                        <option value="17">17:00 - 18:00</option>
                        <option value="18">18:00 - 19:00</option>
                        <option value="19">20:00 - 21:00</option>
                        <option value="20">22:00 - 23:00</option>
                    </select>

                    <label for="quantity">Počet lidí:</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="50" tabindex="3">
                </div>
                
                <input id="reg_submit" type="submit" value="Zarezervovat" tabindex="1">
                <h5>* Pole označené jsou povinné</h5>
                <h4>Cena rezervace dle: <a href="cenik.php">Ceník</a></h4>
            </form>
        </div>
    </section>

<?php
 $file = './user_data/reservations.json';

    // Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $user['email'];
        $date = $_POST['reservation_date'];
        if ($date) {
            $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
            $date = $myDateTime->format('d.m.Y'); // Convert to DD.MM.YYYY format
        }
        $timeslot = $_POST['timeslot'];
        $quantity = $_POST['quantity']; // Default to 1 if not set
    

       // Function to check for reservation collision
    function check_collision($file, $date, $timeslot, $reservations) {
        foreach ($reservations as $reservation) {
            if ($reservation['date'] == $date && $reservation['timeslot'] == $timeslot) {
                return true;
            }
        }
        return false;
    }

    // Read existing reservations from the JSON file
    if (file_exists($file)) {
        $jsonData = file_get_contents($file);
        $reservations = json_decode($jsonData, true);
    } else {
        $reservations = [];
    }

    // Check for collision
    if (check_collision($file, $date, $timeslot, $reservations)) {
        echo "<p>Rezervace již existuje pro tento časový úsek.</p>";
    } else {
        // Prepare data to be saved into JSON
        $data = [
            'email' => $email,
            'date' => $date,
            'timeslot' => $timeslot,
            'quantity' => $quantity
        ];

        // Add new reservation to the array
        saveDataToJsonFile($file, $data);

        // Convert array back to JSON and save to file
        echo "<p>Rezervace byla úspěšně vytvořena.</p>";
    }
}

if ($user["role"] == 'admin') {
    // File containing the reservation data
    // Check if the file exists
    if (file_exists($file)) {
        // Read the file content
        $jsonData = file_get_contents($file);

        // Decode JSON data into PHP array
        $reservations = json_decode($jsonData, true);
    
        // Check if the data was successfully decoded
        if ($reservations) {
            // Number of records per page
            $RPP = 10;

            // Determine the current page
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

            // Calculate the total number of pages
            $totalPages = ceil(count($reservations) / $RPP);

            // Ensure the current page is within bounds
            $page = max(1, min($page, $totalPages));

            // Calculate the start index for the current page
            $startIndex = ($page - 1) * $RPP;

            // Extract the reservations for the current page
            $currentReservations = array_slice($reservations, $startIndex, $RPP);

            // Display reservations in a table
            echo "<div class=\"admin_table\">";
            echo "<table class=\"reservation-table\">";
            echo "<thead>";
            echo "<tr><th>Email</th><th>Datum</th><th>Čas</th><th>Počet lidí</th><th>Edit</th><th>Smazat</th></tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($currentReservations as $reservation) {
                $email = htmlspecialchars($reservation['email']);
                $date = htmlspecialchars($reservation['date']);
                $timeslot = htmlspecialchars($reservation['timeslot']);
                $quantity = htmlspecialchars($reservation['quantity']);
                echo "<tr>
                        <td>$email</td>
                        <td>$date</td>
                        <td>$timeslot</td>
                        <td>$quantity</td>
                        <td><button class=\"edit_reservations\">Edit</button></td>
                        <td><button class=\"remove_reservations\">Smazat</button></td>
                    </tr>";
            }

            echo "</tbody>";
            echo "</table>";

            // Display pagination links
            echo "<div class=\"pagination\">";
            if ($page > 1) {
                $prevPage = $page - 1;
                echo "<a href=\"?page=$prevPage\">&laquo; Previous</a> ";
            }
            for ($x = 1; $x <= $totalPages; $x++) {
                if ($x == $page) {
                    echo "<h3>$x</h3> ";
                } else {
                    echo "<a href=\"?page=$x\">$x</a> ";
                }
            }
            if ($page < $totalPages) {
                $nextPage = $page + 1;
                echo "<a href=\"?page=$nextPage\">Next &raquo;</a>";
            }
            echo "</div>";
            echo "</div>";

        } else {
            echo "Chyba při čtení dat rezervací.";
        }
    } else {
        echo "Rezervační soubor neexistuje.";
    }
}

?>
<?php include './php/structure/footer.php'; ?>

</body>
</html>
