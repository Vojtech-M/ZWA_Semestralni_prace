<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit();
    }

    $usersFile = '../user_data/users.json';
    if (!file_exists($usersFile)) {
        echo json_encode(['success' => false, 'message' => 'User data file not found']);
        exit();
    }

    $users = json_decode(file_get_contents($usersFile), true);
    if ($users === null) {
        echo json_encode(['success' => false, 'message' => 'Error loading user data']);
        exit();
    }

    $userFound = false;
    foreach ($users as &$user) {
        if ($user['email'] === $email) {
            $user['role'] = 'admin';
            $userFound = true;
            break;
        }
    }

    if (!$userFound) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }

    if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving user data']);
    }
}
?>
