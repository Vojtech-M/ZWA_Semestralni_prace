<?php
$filePath = '/home/michavo5/www/user_data/users.json';
$reservationsFilePath = '/home/michavo5/www/user_data/reservations.json';
/**
 * 
 * Načtení uživatelů ze souboru
 * 
 * @return array - pole uživatelů
 * 
 */
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

/**
 * 
 * uložení uživatelů do souboru
 * 
 * @param array $users - pole uživatelů
 * 
 * @return void
 */

function saveUsers($users) {
    global $filePath;
    file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT));
}



/**
 * Vrátí seznam uživatelů
 * 
 * @param int|null $limit - maximální počet uživatelů
 * @param int $offset - počáteční index uživatelů
 * 
 */
function listUsers($limit = null, $offset = 0) {
    $users = loadUsers();

    if ($limit !== null) {
        $users = array_slice($users, $offset, $limit);
    }

    return $users;
}


/**
 *  Vyhledání uživatele podle ID
 * 
 * @param string $id - ID uživatele
 * @return array|null - uživatel nebo null pokud neexistuje
 * 
 * 
 * 
 */
function getUser($id) {
    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['id'] === $id) {
            return $user;
        }
    }
    return null;
}
/**
 * Přidání nového uživatele do databáze
 * @param string $role - uřivatelská role
 * @param string $firstname - jméno
 * @param string $lastname - příjmení
 * @param string $email - email
 * @param string $phone - telefonní číslo
 * @param string $password - heslo
 * @param string $profile_picture - cesta k profilovému obrázku
 * @return string - id nově vytvořeného uživatele
 *  
 * @return string - id nově vytvořeného uživatele
 */
function addUser($role,$firstname, $lastname, $email,$phone, $password, $profile_picture) {
    $users = loadUsers();
    $id = uniqid();
    $newUser = ['id' => $id, 'role'=> $role,'firstname' => $firstname,'lastname' => $lastname, 'email' => $email,'phone'=> $phone, 'password'=> $password,'profile_picture' => $profile_picture];
    $users[] = $newUser;
    saveUsers($users);
    return $id;
}


/**
 * 
 * Smazání profilového obrázku uživatele
 * 
 * @param array $userToDelete - uživatel, jehož profilový obrázek se má smazat
 * 
 * @return void
 */
function deleteProfilePicture($userToDelete) {
    $defaultProfilePicture = './img/profile.png';
    $profilePicturePath = $userToDelete['profile_picture'];

    // Ensure the profile picture path is not the default picture
    if ($profilePicturePath && $profilePicturePath !== $defaultProfilePicture) {
        // Resolve the absolute path
        $absolutePath = realpath(__DIR__ . '/../' . $profilePicturePath);
        if ($absolutePath && file_exists($absolutePath)) {
            unlink($absolutePath);
        }
    }
}


/**
 * 
 * Smazání uživatele
 * 
 * @param string $id - ID uživatele
 * 
 * @return void
 */
function deleteUser($id) {
    $updatedUsers = [];
    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['id'] !== $id) {
            $updatedUsers[] = $user;
        } else {
            echo $user["profile_picture"];
            deleteProfilePicture($user);
        }
    }

    saveUsers($updatedUsers);
}



/**
 * Uložení rezervací do souboru
 * @param array $reservations - pole rezervací
 * @return void
 */
function saveReservations($reservations) {
    global $reservationsFilePath;
    file_put_contents($reservationsFilePath, json_encode($reservations, JSON_PRETTY_PRINT));
}

function deleteReservation($id) {
    $updatedReservations = [];
    $reservations = loadReservations();
    foreach ($reservations as $reservation) {
        if ($reservation['id'] !== $id) {
            $updatedReservations[] = $reservation;
        }
    }

    saveReservations($updatedReservations);
}



/**
 * 
 * Editace uživatele
 * 
 * @param string $id - ID uživatele
 * @param string $role - uživatelská role
 * @param string $firstname - jméno
 * @param string $lastname - příjmení
 * @param string $email - email
 * @param string $phone - telefonní číslo
 * @param string $password - heslo
 * @param string $profile_picture - cesta k profilovému obrázku
 * 
 * @return void
 */
function editUser($id, $role,$firstname, $lastname, $email,$phone, $password, $profile_picture ) {
    $users = loadUsers();
    foreach ($users as &$user) {  // & - reference na prvek v poli (nikoliv kopii)
        if ($user['id'] === $id) {
            $user['firstname'] = $firstname;
            $user['lastname'] = $lastname;
            $user['email'] = $email;
            $user['phone'] = $phone;
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $user['password'] = $hash;
            $user['profile_picture'] = $profile_picture;
            break;
        }
    }
    saveUsers($users);
}