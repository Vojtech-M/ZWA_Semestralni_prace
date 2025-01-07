<?php
/**
 * 
 * Funkce pro zpracování rezervací
 * 
 * 
 */

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
        $quantity = $_POST['quantity'];
        $reservations = loadReservations();    

    // Check for collision
    if (check_collision($file, $date, $timeslot, $reservations)) {
        echo "<p class='reservation-result error'>Rezervace již existuje pro tento časový úsek.</p>";
    } 
    // Validate the reservation
    elseif (!check_quantity($quantity)) {
        echo "<p class='reservation-result error'>Neplatný počet lidí.</p>";
    } elseif (!check_timeslot($timeslot)) {
        echo "<p class='reservation-result error'>Neplatný čas.</p>";
    } elseif (DateTime::createFromFormat('Y-m-d', $_POST['reservation_date']) < new DateTime('today')) {
        echo "<p class='reservation-result error' >Neplatné datum. Datum rezervace musí být dnešní nebo budoucí datum.</p>";
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
            echo "<p class='reservation-result error'>$reservation_result</p>";
        } else {
            // Update the reservation if no collision is found
            editReservation($id, $date, $timeslot, $quantity);
            $reservation_result = "Rezervace byla úspěšně upravena.";
            echo "<p class='reservation-result success'>$reservation_result</p>";
        }
    }
}
?>