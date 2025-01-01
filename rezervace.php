<?php
/*  
 Job: Reservation
    This file contains a form for user reservation. It checks if the reservation exists in the database and if the date is correct.
    The user can reserve a time slot for a certain number of people. The reservation is saved in a JSON file.
    The user can also delete and edit their reservations. Admins can delete any reservation.
    The reservations are displayed in a table with pagination. Admins can edit and delete reservations.
*/
include "./php/check_login.php";
include "./php/data_handler.php";
include "./php/reservation_validation.php";
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <?php
    include "./php/structure/head.html";
    ?>
    <link rel="stylesheet" href="./css/reservations.css">
</head>
<body>
<?php include './php/structure/header.php'; ?> 

<?php  ?>
        <div class ="formular">
            <form action="rezervace.php" method="post">
                <div id="name">
                    <label class="required_label" for="reservation_date">Datum rezervace</label>
                    <input type="date" id="reservation_date" name="reservation_date" max='2030-01-01' tabindex="1" required>
                    
                    <label for="timeslot" class="required_label" required>Čas rezervace</label>
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

                    <label class="required_label" for="quantity">Počet lidí:</label>
                    <input type="number" id="quantity"  name="quantity" min="1" max="50" tabindex="3" required>
                </div>
                
                <button class=""  id="reg_submit" type="submit" name="action" value="reserve"   tabindex="4">Rezervovat</button>
             
                <h5>Pole označené <span class="red_text">*</span> jsou povinná</h5>
                <h5>Rezervaci je možné vytvořit maximálně pro 50 lidí</h5>
                <h4>Cena rezervace dle: <a href="price_list.php">Ceník</a></h4>
            </form>
        </div>

<?php
$file = './user_data/reservations.json';
$reservation_result = "";

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
        echo "<p class='reservation-result error'>Rezervace již existuje pro tento časový úsek.</p>";
    } 
    elseif (!check_quantity($quantity)) {
        echo "<p>Neplatný počet lidí.</p>";
    } elseif (!check_timeslot($timeslot)) {
        echo "<p>Neplatný čas.</p>";
    } elseif (DateTime::createFromFormat('Y-m-d', $_POST['reservation_date']) < new DateTime('today')) {
        echo "<p>Neplatné datum. Datum rezervace musí být dnešní nebo budoucí datum.</p>";
    }
    else {
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
        echo "<p class='reservation-result success'>Rezervace byla úspěšně vytvořena.</p>";
    }
    }

    elseif ($action === 'delete') {
        $id = $_POST['id'];
        deleteReservation($id);
    } 
    elseif ($action === 'edit_reservation') {
        $id = $_POST['id'];
        $date = $_POST['date'];
        $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
        $date = $myDateTime->format('d.m.Y');
        $timeslot = $_POST['timeslot'];
        $quantity = $_POST['quantity'];
        $reservations = loadReservations();
    
        if (check_collision($file, $date, $timeslot, $reservations, $id)) {
            $reservation_collision = true;
            $reservation_result = "Rezervace již existuje pro tento časový úsek";
            echo "<p class='reservation-result error'>Rezervace již existuje pro tento časový úsek.</p>";
        } else {
            // Update the reservation if no collision is found
            editReservation($id, $date, $timeslot, $quantity);
            $reservation_result = "Rezervace byla úspěšně upravena.";
            echo "<p class='reservation-result success'>$reservation_result</p>";
        }
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
        echo "<div class=\"reservation_table\">
            <table class=\"reservation-table\">";
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
                <div class=\"hidden\" id=\"editForm_$reservation_id\">";
                include './php/edit_reservation_form.php'; 
                echo "</div>
                <button id=\"editButton_$reservation_id\" class=\"editButton\">Edit</button>
                </td>";
                echo "</td>";
                echo "<td>
                <form action=\"rezervace.php\" method=\"post\">
                    <input type=\"hidden\" name=\"id\" value=\"$reservation_id\">
                    <button type=\"submit\" name=\"action\" value=\"delete\" class=\"delete\">delete</button>
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
            echo "<a href=\"?page=$prevPage\">&laquo; Předchozí</a> ";
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
            echo "<a href=\"?page=$nextPage\">Další &raquo;</a>";
        }
        echo "</div>";
        echo "</div>";
    } else {
        echo "Chyba při čtení dat rezervací.";
    }
} else {
    echo "Rezervační soubor neexistuje.";
}
$userReservations = getUserReservations($_SESSION['id']);
?>
<article>
    <h2>Moje rezervace</h2>
    <?php 
    // Sort the reservations by date
    if (!empty($userReservations)) {
        usort($userReservations, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
    }
    ?>

    <?php if (!empty($userReservations)): ?>
        <ul>
        <?php foreach ($userReservations as $reservation): ?>
            <li>
                <?php 
                $timeslot = $reservation['timeslot'];
                $timeslot1 = $timeslot . ":00";
                $timeslot2 = $timeslot + 1 . ":00";
                ?>
                Datum: <?php echo htmlspecialchars($reservation['date']); ?>,
                Čas: <?php echo htmlspecialchars("$timeslot1 - $timeslot2"); ?>,
                Počet lidí: <?php echo htmlspecialchars($reservation['quantity']); ?>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($reservation['id']); ?>">
                    <button type="submit" name="action" value="delete" class="remove_reservations user_managment_button">Smazat</button>
                </form>
                <?php include './php/edit_reservation_form.php'; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nemáte žádné rezervace.</p>
    <?php endif; ?>

    <div class="reservation_link">
        <a href="profil.php">Zpět na profil</a> 
    </div>
</article>

<?php include './php/structure/footer.php'; ?>
<script src="./scripts/reservation.js" type=module> </script>
</body>
</html>
