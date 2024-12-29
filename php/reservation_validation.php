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
?>