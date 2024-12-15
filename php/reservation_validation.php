<?php
function check_collision($file, $date, $timeslot, $reservations) {
            foreach ($reservations as $reservation) {
                if ($reservation['date'] == $date && $reservation['timeslot'] == $timeslot) {
                    return true;
                }
            }
            return false;
        }
        ?>