<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['role'])) {
    header('Location: prihlaseni.php'); // Redirect to login if not logged in
    exit();
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

<?php if ($_SESSION['role'] !== 'admin'): ?>
    <section class="registrace">
        <div class ="formular">
            <form action="rezervace.php" method="post">
                <div id="name">
                    <label for="reservation_date">Datum rezervace</label>
                    <input type="date" id="reservation_date" name="reservation_date" min='2024-04-04' max='2030-01-01' tabindex="1" required>
                    
                    <label for="reservation_time">Čas rezervace</label>
                    <input type="time" id="reservation_time" name="reservation_time" min="12:00" max="23:00"tabindex="2" required>

                    <label for="quantity">Počet lidí:</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="50" tabindex="3">
                </div>
                <br>
                <input id="reg_submit" type="submit" value="Zarezervovat" tabindex="">
                <h5>* Pole označené jsou povinné</h5>
                <h4>Cena rezervace dle: <a href="cenik.php">Ceník</a></h4>
            </form>
        </div>
</section>


 <?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $date = $_POST['reservation_date'] ?? '';
    if ($date) {
        $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
        $date = $myDateTime->format('d.m.Y'); // Convert to DD.MM.YYYY format
    }
   
    $time = $_POST['reservation_time'] ?? '';
    $quantity = $_POST['quantity'] ?? 1; // Default to 1 if not set
  
    // Prepare data to be saved into JSON
    $data = [
        'email' => $email,
        'date' => $date,
        'time' => $time,
        'quantity' => $quantity
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
    echo "Rezervace proběhla úspěšně, vítej, Těš��me se na tebe.";
}

?>

<?php else: 
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
            $time = htmlspecialchars($reservation['time']);
            $quantity = htmlspecialchars($reservation['quantity']);
            echo "<tr>
                    <td>$email</td>
                    <td>$date</td>
                    <td>$time</td>
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
    ?>
<?php endif; ?>

<?php include './php/structure/footer.php'; ?>
<script>
function deleteReservation(reservationId) {
    if (confirm("Are you sure you want to delete this reservation?")) {
        fetch('delete_reservation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: reservationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Reservation deleted successfully.");
                location.reload(); // Reload the page to see the changes
            } else {
                alert("Failed to delete reservation.");
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
</body>
</html>