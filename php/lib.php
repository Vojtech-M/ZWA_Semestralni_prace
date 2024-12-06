<?php
$filePath = '/home/michavo5/www/user_data/users.json';


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

function addUser($name, $email, $avatar) {
    $users = loadUsers();
    $id = uniqid();
    $newUser = ['id' => $id, 'name' => $name, 'email' => $email, 'avatar' => $avatar];
    $users[] = $newUser;
    saveUsers($users);
    return $id;
}

function deleteUser($id) {
    $updatedUsers = [];
    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['id'] !== $id) {
            $updatedUsers[] = $user;
        }
    }

    saveUsers($updatedUsers);
}

function editUser($id, $name, $email, $avatar) {
    $users = loadUsers();
    foreach ($users as &$user) {  // & - reference na prvek v poli (nikoliv kopii)
        if ($user['id'] === $id) {
            $user['name'] = $name;
            $user['email'] = $email;
            $user['avatar'] = $avatar;
            break;
        }
    }
    saveUsers($users);
}