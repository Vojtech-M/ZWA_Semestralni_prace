<?php
function check_collision($file, $date, $timeslot, $reservations, $currentId = null) {
    foreach ($reservations as $reservation) {
        // Check if the reservation conflicts in time and date
        if ($reservation['date'] == $date && $reservation['timeslot'] == $timeslot) {
            // If the reservation is not the one being edited, consider it a collision
            if ($currentId === null || $reservation['id'] != $currentId) {
                return true;
            }
        }
    }
    return false;
}
function check_quantity($quantity) {
    if ($quantity < 1 || $quantity > 50) {
        return false;
    }else {
        return true;
    }
}
function check_timeslot($timeslot) {
    if ($timeslot < 14 || $timeslot > 22) {
        return false;
    }else {
        return true;
    }
    
}


function handleEditReservation($postData) {
    $id = $postData['id'];
    $date = $postData['date'];
    $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
    $date = $myDateTime->format('d.m.Y');
    $timeslot = $postData['timeslot'];
    $quantity = $postData['quantity'];
    $reservation_collision = false;

    // Load all existing reservations
    $reservations = loadReservations();

    // Load the file for reservation data
    $file = './data/reservations.json';

    // Check if the reservation already exists and is for the same user (ID)
    if (check_collision($file, $date, $timeslot, $reservations, $id)) {
        $reservation_collision = true;
    } else {
        // Update the reservation if no collision is found
        editReservation($id, $date, $timeslot, $quantity);
    }
}






?>