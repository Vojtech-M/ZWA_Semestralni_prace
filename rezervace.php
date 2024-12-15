<?php
 include "./php/check_login.php";
 include "./php/lib.php";
 include "./php/reservation_validation.php";

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
    <link rel="stylesheet" href="./css/layout.css">
    <link rel="stylesheet" href="./css/reservations.css">
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
                        <option value="19">19:00 - 20:00</option>
                        <option value="20">20:00 - 21:00</option>
                        <option value="21">21:00 - 22:00</option>
                        <option value="22">22:00 - 23:00</option>
                    </select>

                    <label for="quantity">Počet lidí:</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="50" tabindex="3" required>
                </div>
                
                <button id="reg_submit" type="submit" name="action" value="reserve"   tabindex="4">Rezervovat</button>
             
                <h5>* Pole označené jsou povinné</h5>
                <h4>Cena rezervace dle: <a href="cenik.php">Ceník</a></h4>
            </form>
        </div>
    </section>

<?php
 $file = './user_data/reservations.json';

    // Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
        if ($action === 'reserve') {

            $registration_id = uniqid();
            $user_id = $user['id'];
            $email = $user['email'];
            $date = $_POST['reservation_date'];
            if ($date) {
                $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
                $date = $myDateTime->format('d.m.Y'); // Convert to DD.MM.YYYY format
            }
            $timeslot = $_POST['timeslot'];
            $quantity = $_POST['quantity']; // Default to 1 if not set
        
           
            $reservations = loadReservations();    
        // Check for collision
        if (check_collision($file, $date, $timeslot, $reservations)) {
            echo "<p>Rezervace již existuje pro tento časový úsek.</p>";
        } else {
            // Prepare data to be saved into JSON
            $data = [
                'id' => $registration_id,
                'user_id' => $user_id,
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

        elseif ($action === 'delete') {
            $id = $_POST['id'];
            deleteReservation($id);
        } elseif ($action === 'edit') {
            $id = $_POST['id'];
            echo "what ?"   ;
            echo '<section class="edit-reservation">';
            echo '<form action="rezervace.php" method="post">';
            echo '<input type="hidden" name="id" value="">';
            echo '<label for="reservation_date">Datum rezervace</label>';
            echo '<input type="date" id="reservation_date" name="reservation_date" value="" required>';
            echo '<label for="timeslot">Čas rezervace</label>';
            echo '<select name="timeslot" id="timeslot">';
            echo '</select>';
            echo '<label for="quantity">Počet lidí:</label>';
            echo '<input type="number" id="quantity" name="quantity" value="" min="1" max="50" required>';
            echo '<button type="submit" name="action" value="update">Uložit změny</button>';
            echo '</form>';
            echo '</section>';
        }

        elseif ($action === 'update') {
            $id = $_POST['id'];
            $date = $_POST['reservation_date'];
            $timeslot = $_POST['timeslot'];
            $quantity = $_POST['quantity'];
            $data = [
                'id' => $id,
                'date' => $date,
                'timeslot' => $timeslot,
                'quantity' => $quantity
            ];
            updateReservation($id, $data);
        }
}
if (file_exists($file)) {
    // Read the file content
    $reservations = loadReservations();   
    // Check if the data was successfully decoded
    if ($reservations) {
        // Sort the reservations by date
        usort($reservations, function($a, $b) {
            $dateA = DateTime::createFromFormat('d.m.Y', $a['date']);
            $dateB = DateTime::createFromFormat('d.m.Y', $b['date']);
            return $dateA <=> $dateB;
        });

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
        echo "<div class=\"reservation_table\">";
        echo "<table class=\"reservation-table\">";
        echo "<thead>";
        echo "<tr>";
        if ($user["role"] == "admin") {
            echo "<th>ID</th><th>Email</th>";
        }
        echo "<th>Datum</th><th>Čas</th><th>Počet lidí</th>";
        if ($user["role"] == "admin") {
            echo "<th>Edit</th><th>Smazat</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($currentReservations as $reservation) {
            $date = htmlspecialchars($reservation['date']);
            $timeslot = htmlspecialchars($reservation['timeslot']);
            $quantity = htmlspecialchars($reservation['quantity']);
            $timeslot1 = $timeslot . ":00";
            $timeslot2 = ($timeslot + 1) . ":00";

            echo "<tr>";
            if ($user["role"] == "admin") {
                $reservation_id = htmlspecialchars($reservation['id']);
                $email = htmlspecialchars($reservation['email']);
                echo "<td>$reservation_id</td><td>$email</td>";
            }
            echo "<td>$date</td><td>$timeslot1 - $timeslot2</td><td>$quantity</td>";

            if ($user["role"] == "admin") {
                echo "<td>
                        <form action=\"rezervace.php\" method=\"post\">
                            <input type=\"hidden\" name=\"id\" value=\"$reservation_id\">
                            <button type=\"submit\" name=\"action\" value=\"edit\" class=\"edit_reservations\">Edit</button>
                        </form>
                      </td>";
                echo "<td>
                        <form action=\"rezervace.php\" method=\"post\">
                            <input type=\"hidden\" name=\"id\" value=\"$reservation_id\">
                            <button type=\"submit\" name=\"action\" value=\"delete\" class=\"remove_reservations\">Smazat</button>
                        </form>
                      </td>";
            }
            echo "</tr>";
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
?>
<?php include './php/structure/footer.php'; ?>
<script> 
document.querySelectorAll('.remove_reservations').forEach(button => {
    button.addEventListener('click', function (e) {
        if (!confirm('Opravdu chcete tuto rezervaci smazat?')) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>


<!-- <form action="" method="post">
<label>
    ID rezervace:
    <input type="text" name="id" required>
</label>
<button type="submit" name="action" value="delete">Smazat</button>
</form> -->