<?php
if (file_exists($file)) {
    // Read the file content
    $reservations = loadReservations();   
    // Check if the data was successfully decoded
    if ($reservations) {
        // Sort the reservations by date
        usort($reservations, function($a, $b) {
            $dateA = DateTime::createFromFormat('d.m.Y', $a['date']);
            $dateB = DateTime::createFromFormat('d.m.Y', $b['date']);
            
            if ($dateA == $dateB) {
                return $a['timeslot'] - $b['timeslot'];
            }
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
                ";
                echo "</td>";
                echo "<td>
                <form action=\"reservation.php\" method=\"post\">
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
?>