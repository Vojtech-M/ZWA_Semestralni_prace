<?php
/**
 * Job: User profile
 * This file contains a user profile page. It displays user information and allows the user to edit their profile.
 */
include './php/check_login.php';
include './php/validation.php';
include './php/lib.php';
include './php/file_upload.php';
include './php/reservation_validation.php';
if (!isset($_SESSION['id'])) {
    header("Location: ./login.php");
    exit;
}
// Load user data
$user = getDataById($_SESSION['id']);
$userReservations = getUserReservations($_SESSION['id']);
$defaultProfilePicture = './img/profile.png';


// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleUserAction($_POST);
}

function handleUserAction($postData) {
    $action = $postData['action'];

    switch ($action) {
        case 'add':
        case 'update':
            handleUserForm($postData);
            break;
        case 'delete':
            $id = $postData['id'];
            deleteUser($id);
            loadUsers();
            break;

        case 'update_self':
            handleUpdateSelf($postData);
            break;

        case 'delete_reservation':
            $id = $postData['id'];
            deleteReservation($id);
            break;

        case 'edit_reservation':
            handleEditReservation($postData);
            break;
    }
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
            $errors['file'] = $fileUploadResult['error']; // Collect file upload error
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

function validateInputs($firstname, $lastname, $email, $phone, $password, $password2) {
    return [
        'firstname' => validateName($firstname),
        'lastname' => validateName($lastname),
        'email' => validateEmail($email),
        'phone' => validatePhone($phone),
        'password' => validatePassword($password, $password2),
    ];
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
    if (check_email($email, $id)) {
        $errors['email'] = 'Tento e-mail již používá jiný uživatel.';
    }
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
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <?php include "./php/structure/head.html"; ?>
    <link rel="stylesheet" href="./css/profile.css">
</head>
<body>
<?php include './php/structure/header.php'; ?>

<!-- User profile view -->
<article>
    <div class="left-text">
        <h1>Profil uživatele</h1>
        <p>Jméno: <?php echo htmlspecialchars($user['firstname']); ?></p>
        <p>Příjmení: <?php echo htmlspecialchars($user['lastname']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Telefonní číslo: <?php echo htmlspecialchars($user['phone']); ?></p>
    </div>
    <div class="right-text">
        <img class="profile_picture_view"src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profilový obrázek">
    </div>
</article>


<article>
<?php
    $firstname = $user['firstname'];
    $lastname = $user['lastname'];
    $email = $user['email'];
    $phone = $user['phone'];
    $errors = [];
    $formValid = true; // for is valid at the beginning
?>
        <!-- Regular user view -->
    <form action="profil.php" method="post" enctype="multipart/form-data">
        <h2> Upravit můj profil</h2>
        <h4>* Při zadání nového hesla dojde ke změně !</h4>
        <?php
            include './php/structure/form_temeplate.php'; ?> 
        <button type="submit" name="action" value="update_self">Upravit</button>   
    </form>
</article>
<article>
        <h2>Moje rezervace</h2>
        <?php if (!empty($userReservations)): 
        usort($userReservations, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
    ?>
            <ul>
            <?php foreach ($userReservations as $reservation): ?>
                <li>
                    <?php 
                    $timeslot = $reservation['timeslot'];
                    $timeslot1 = $timeslot . ":00";
                    $timeslot2 = $timeslot + 1 . ":00";
                    ?>
                    Datum: <?php echo htmlspecialchars($reservation['date']); ?>,
                    Čas: <?php echo htmlspecialchars("$timeslot1 - $timeslot2"); ?>,
                    Počet lidí: <?php echo htmlspecialchars($reservation['quantity']); ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nemáte žádné rezervace.</p>
        <?php endif; ?>

        <div class="reservation_link">
        <a href="rezervace.php">Upravit rezervaci</a> 
    </div>
</article>


<?php if ($user["role"] == 'admin'): ?>
<article>
        <table class="user_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Jméno</th>
                    <th>Příjmení</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
            </tbody>
        </table>
<button class="user_managment_button" id="loadMore">Načíst uživatele</button>
<button class="user_managment_button" id="add_user">Přidat uživatele</button>
</article>




<div id="addFormUser" class="addForm hidden">
<article>
<section>
        <form action="" method="post" enctype="multipart/form-data">
        <h2>Přidat nového uživatele</h2>
        <label for="role">Vyberte roli:</label>
            <select id="role" name="role">
            <option value="user">user</option>
            <option value="admin">admin</option>
            </select>
        <?php
            $firstname = $lastname = $email = $phone = '';
            $errors = [];
            $formValid = true; // formulář je na začátku valid
            include './php/structure/form_temeplate.php'; ?> 
        <button class="user_managment_button"type="submit" name="action" value="add">Přidat</button>
    </form>
</section>
</article>
</div>


<article>
<section>
        <form action="" method="post" enctype="multipart/form-data">
        <h2>Upravit uživatele</h2>
            <label>
            ID uživatele:
            <input type="text" name="id" required>
            </label>
            <label for="role">Vyberte roli:</label>
            <select id="role" name="role">
            <option value="user">user</option>
            <option value="admin">admin</option>
            </select>
            <?php
            $firstname = $lastname = $email = $phone = '';
            $errors = [];
            $formValid = true; // formulář je na začátku valid
            include './php/structure/form_temeplate.php'; ?> 
        <button class="user_managment_button"type="submit" name="action" value="update">Upravit</button>
    </form>
</section>
</article>
<?php endif; ?>
<?php include './php/structure/footer.php'; ?>
<script src="./scripts/load_users.js" ></script> 
<script src="./scripts/profile.js" ></script> 
<script src="./scripts/register.js"></script> 
</body>
</html>

