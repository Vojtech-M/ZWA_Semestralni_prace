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
    file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); // JSON_UNESCAPED_UNICODE - zabraňuje kódování znaků do unicode
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
            //echo $user["profile_picture"]; //debugging - check if the path is correct
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


function editReservation($id, $date, $timeslot, $quantity) {
    $file = './user_data/reservations.json';
    if (file_exists($file)) {
        $jsonData = file_get_contents($file);
        $reservations = json_decode($jsonData, true);

        foreach ($reservations as &$reservation) {
            if ($reservation['id'] === $id) {
                $reservation['date'] = $date;
                $reservation['timeslot'] = $timeslot;
                $reservation['quantity'] = $quantity;
                break;
            }
        }

        file_put_contents($file, json_encode($reservations));
    }
}

// åfunction updateReservation()

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
    foreach ($users as &$user) { // & - reference
        if ($user['id'] === $id) {
            $user['firstname'] = $firstname;
            $user['role'] = $role;
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


/**
 * 
 * Načtení rezervací ze souboru
 * 
 * @return array - pole rezervací
 */
function validateInputs($firstname, $lastname, $email, $phone, $password, $password2) {
    return [
        'firstname' => validateName($firstname),
        'lastname' => validateName($lastname),
        'email' => validateEmail($email),
        'phone' => validatePhone($phone),
        'password' => validatePassword($password, $password2),
    ];
}
function handleUserForm($postData) {
    $id = $postData['id'] ?? '';
    $role = $postData['role'];
    $firstname = $postData['firstname'];
    $lastname = $postData['lastname'];
    $email = $postData['email'];
    $phone = $postData['phone'];
    $password = $postData['password'];
    $password2 = $postData['password2'];
    $defaultProfilePicture = './img/profile.png';

    // Validate inputs
    $errors = validateInputs($firstname, $lastname, $email, $phone, $password, $password2);

    $fileUploadResult = handleFileUpload('file');
    if ($fileUploadResult['success']) {
        $fileNameNew = $fileUploadResult['filePath'];
    } else {
        if (isset($fileUploadResult['noFile']) && $fileUploadResult['noFile'] === true) {
            $fileNameNew = $defaultProfilePicture;
        } else {
            $fileNameNew = null; // Handle error or retain old picture
            $errors['image'] = $fileUploadResult['error']; // Collect file upload error
        }
    }

    if (check_email($email, $id)) {
        $errors['email'] = 'Tento e-mail již používá jiný uživatel.';
    }

    // Filter out null errors
    $errors = array_filter($errors);
    $formValid = empty($errors);


    // If valid, proceed with the respective action
    if ($formValid) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($postData['action'] === 'add') {
            addUser($role, $firstname, $lastname, $email, $phone, $hash, $fileNameNew);
        } elseif ($postData['action'] === 'update') {
            $id = $postData['id'];
            echo "User byl upraven";
            editUser($id, $role, $firstname, $lastname, $email, $phone, $hash, $fileNameNew);
        }
    } else {
        echo "User nebyl upraven";
        // Return errors to the form and display
        return $errors;
    }
}

function handleUpdateSelf($postData) {
    // Get the current user's data
    $user = getDataById($_SESSION['id']);
    $id = $_SESSION['id'];
    $role = $user['role']; // Role remains unchanged for self-update
    $firstname = htmlspecialchars(trim($postData['firstname']));
    $lastname = htmlspecialchars(trim($postData['lastname']));
    $email = htmlspecialchars(trim($postData['email']));
    $phone = htmlspecialchars(trim($postData['phone']));
    $newPassword = isset($postData['password']) ? htmlspecialchars(trim($postData['password'])) : '';
    $confirmPassword = isset($postData['password2']) ? htmlspecialchars(trim($postData['password2'])) : '';
    $profile_picture = $user['profile_picture']; // Default to current profile picture

    // Validate input fields
    $errors = [];
    $errors['firstname'] = validateName($firstname);
    $errors['lastname'] = validateName($lastname);
    $errors['email'] = validateEmail($email);
    $errors['phone'] = validatePhone($phone);

    // Password validation (if a new password is provided)
    if (!empty($newPassword)) {
        $errors['password'] = validatePassword($newPassword, $confirmPassword);
    }
    check_email($email, $id); // Check if the email already exists
    
    // Handle file upload
    $fileUploadResult = handleFileUpload('file');
    if ($fileUploadResult['success']) {
        $profile_picture = $fileUploadResult['filePath'];
        deleteProfilePicture($user);
    } else {
        // A file was uploaded but invalid
        $fileNameNew = null; // Or handle as required
    }
    $errors = array_filter($errors);  
    // If there are no errors, update the profile
    if (empty($errors)) {
        // Hash the password if it was updated, otherwise keep the old one
        $passwordHash = !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : $user['password'];
        // Update the user's data
        editUser($id, $role, $firstname, $lastname, $email, $phone, $passwordHash, $profile_picture);
    }
}

/**
 * Update user role to 'admin'.
 * 
 * @param int $userId The user ID.
 * @return bool True if the role was successfully updated, false otherwise.
 */
function updateUserRoleToAdmin($userId) {
    $users = loadUsers();
    foreach ($users as &$user) {
        if ($user['id'] === $userId) {
            $user['role'] = 'admin';
            saveUsers($users);
            return true;
        }
    }
    return false;
}