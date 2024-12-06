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

function addUser($role,$firstname, $lastname, $email,$phone, $password, $profile_picture) {
    $users = loadUsers();
    $id = uniqid();
    $newUser = ['id' => $id, 'role'=> $role,'firstname' => $firstname,'lastname' => $lastname, 'email' => $email,'phone'=> $phone, 'password"'=> $password,'profile_picture' => $profile_picture];
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

function editUser($id, $role,$firstname, $lastname, $email,$phone, $password, $profile_picture ) {
    $users = loadUsers();
    foreach ($users as &$user) {  // & - reference na prvek v poli (nikoliv kopii)
        if ($user['id'] === $id) {
            $user['firstname'] = $firstname;
            $user['lastname'] = $lastname;
            $user['email'] = $email;
            $user['phone'] = $phone;
            $user['password'] = $password;
            $user['profile_picture'] = $profile_picture;
            break;
        }
    }
    saveUsers($users);
}