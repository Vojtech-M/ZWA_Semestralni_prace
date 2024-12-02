<?php
session_start();

// Check if the user is logged in
if (empty($_SESSION['role'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get the reservation ID from the request
$data = json_decode(file_get_contents('php://input'), true);
$reservationId = $data['id'] ?? null;

if ($reservationId === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid reservation ID']);
    exit();
}

// Load reservations from JSON
$reservationsFile = './user_data/reservations.json';
$reservations = json_decode(file_get_contents($reservationsFile), true);

if (!is_array($reservations)) {
    echo json_encode(['success' => false, 'message' => 'No reservations found']);
    exit();
}

// Find and delete the reservation
$reservationFound = false;
foreach ($reservations as $key => $reservation) {
    if ($reservation['id'] === $reservationId) {
        unset($reservations[$key]);
        $reservationFound = true;
        break;
    }
}

if ($reservationFound) {
    // Save the updated reservations back to the JSON file
    file_put_contents($reservationsFile, json_encode(array_values($reservations), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['success' => true, 'message' => 'Reservation deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Reservation not found']);
}
?>