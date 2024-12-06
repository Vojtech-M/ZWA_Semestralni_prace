<?php
session_start();

// Check if the user is logged in and is an admin
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit();
    }

    $usersFile = '../user_data/users.json'; // Ensure the path is correct
    if (!file_exists($usersFile)) {
        echo json_encode(['success' => false, 'message' => 'User data file not found']);
        exit();
    }

    $users = json_decode(file_get_contents($usersFile), true);
    if ($users === null) {
        echo json_encode(['success' => false, 'message' => 'Error loading user data']);
        exit();
    }

    $userToDelete = null;
    foreach ($users as $index => $user) {
        if ($user['email'] === $email) {
            $userToDelete = $user;
            unset($users[$index]);
            break;
        }
    }

    // Reindex the array to remove gaps in the keys
    $users = array_values($users);

    if ($userToDelete !== null) {
        // Delete the profile picture if it exists and is not the default picture
        $defaultProfilePicture = './img/profile.png'; // Update this to match your default picture path
        $profilePicturePath = str_replace('.\\', '', $userToDelete['profile_picture'] ?? '');
        if ($profilePicturePath && $profilePicturePath !== $defaultProfilePicture && file_exists('../' . $profilePicturePath)) {
            unlink('../' . $profilePicturePath);
        }

        if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error saving user data']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
}
?>