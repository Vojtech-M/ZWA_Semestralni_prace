<?php
$filePath = '../user_data/users.json';

function loadUsers() {
    global $filePath;

    $jsonData = file_get_contents($filePath);
    if ($jsonData === false) {
        return [];
    }
    $users = json_decode($jsonData, true);
    if ($users === null) {
        return [];
    }
    return $users;
}

function saveUsers($users) {
    global $filePath;

    file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT));
}

function listUsers($limit = null, $offset = 0) {
    $users = loadUsers();

    if ($limit !== null) {
        $users = array_slice($users, $offset, $limit);
    }

    return $users;
}

function getUser($id) {
    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['id'] === $id) {
            return $user;
        }
    }
    return null;
}

function addUser($firstname,$lastname, $email,$passwd) {
    $users = loadUsers();
    $id = uniqid();
    $newUser = ['id' => $id, 'firstname' => $firstname, 'lastname' => $lastname,'email' => $email, 'password' => $passwd,  'profile_picture'  => ".\/img\/profile.png"];
    $users[] = $newUser;
    saveUsers($users);
    return $id;
}

function deleteProfilePicture($userToDelete) {
    $defaultProfilePicture = './img/profile.png';
    $profilePicturePath = str_replace('.\\', '', $userToDelete['profile_picture'] ?? '');
    if ($profilePicturePath && $profilePicturePath !== $defaultProfilePicture && file_exists('../' . $profilePicturePath)) {
        unlink('../' . $profilePicturePath);
    }
}

function deleteUser($id) {
    $updatedUsers = [];
    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['id'] !== $id) {
            $updatedUsers[] = $user;
        } else {
            deleteProfilePicture($user);
        }
    }

    saveUsers($updatedUsers);
}


