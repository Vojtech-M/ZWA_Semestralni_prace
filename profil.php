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

// Load user data
$user = getDataById($_SESSION['id']);
$userReservations = getUserReservations($_SESSION['id']);
$defaultProfilePicture = './img/profile.png';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
        case 'update':
            // Handle common user form logic for add and update actions
            $id = $_POST['id'];
            $role = $_POST['role'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $defaultProfilePicture = './img/profile.png';

            // Validate inputs
            $errors['firstname'] = validateName($firstname);
            $errors['lastname'] = validateName($lastname);
            $errors['email'] = validateEmail($email);
            $errors['phone'] = validatePhone($phone);
            $errors['password'] = validatePassword($password, $password2);

            $fileUploadResult = handleFileUpload('file');
            if ($fileUploadResult['success']) {
                $fileNameNew = $fileUploadResult['filePath'];
            } else {
            if (isset($fileUploadResult['noFile']) && $fileUploadResult['noFile'] === true) {
                // No file was uploaded
                $fileNameNew = $defaultProfilePicture;
            } else {
                // A file was uploaded but invalid
                echo $fileUploadResult['error']; // Display error message
                $fileNameNew = null; // Or handle as required
            }
        }
            // Filter out null errors
            $errors = array_filter($errors);
            $formValid = empty($errors);

            var_dump($errors);
            // If valid, proceed with the respective action
            if ($formValid) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                if ($action === 'add') {
                    addUser($role, $firstname, $lastname, $email, $phone, $hash, $fileNameNew);
                } elseif ($action === 'update') {
                    $id = $_POST['id'];
                    editUser($id, $role, $firstname, $lastname, $email, $phone, $hash,$fileNameNew);
                }
            }
            break;

        case 'delete':
            $id = $_POST['id'];
            deleteUser($id);
            loadUsers();
            break;

        case 'update_self':
            // Get the current user's data
            $user = getDataById($_SESSION['id']);
            $id = $_SESSION['id'];
            $role = $user['role']; // Role remains unchanged for self-update
            $firstname = htmlspecialchars(trim($_POST['firstname']));
            $lastname = htmlspecialchars(trim($_POST['lastname']));
            $email = htmlspecialchars(trim($_POST['email']));
            $phone = htmlspecialchars(trim($_POST['phone']));
            $newPassword = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
            $confirmPassword = isset($_POST['password2']) ? htmlspecialchars(trim($_POST['password2'])) : '';
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
        
         
           

            $fileUploadResult = handleFileUpload('file');
            if ($fileUploadResult['success']) {
                $profile_picture = $fileUploadResult['filePath'];
                deleteProfilePicture($user);
            } else {
                // A file was uploaded but invalid
                echo $fileUploadResult['error']; // Display error message
                $fileNameNew = null; // Or handle as required
            }
            $errors = array_filter($errors);
        
        
            // If there are no errors, update the profile
            if (empty($errors)) {
                // Hash the password if it was updated, otherwise keep the old one
                $passwordHash = !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : $user['password'];
                // Update the user's data
                editUser($id, $role, $firstname, $lastname, $email, $phone, $passwordHash, $profile_picture);
                echo "Profil byl úspěšně aktualizován.";
            } else {
                // Display errors to the user
                foreach ($errors as $field => $error) {
                    echo "<p class='error'>Chyba v poli {$field}: {$error}</p>";
                }
            }
            break;

        case 'delete_reservation':
            $id = $_POST['id'];
            deleteReservation($id);
            break;

            case 'edit_reservation':
                $id = $_POST['id'];
                $date = $_POST['date'];
                $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
                $date = $myDateTime->format('d.m.Y');
                $timeslot = $_POST['timeslot'];
                $quantity = $_POST['quantity'];
                $reservation_collision = false;
            
                // Load all existing reservations
                $reservations = loadReservations();
            
                // Load the file for reservation data
                $file = './data/reservations.json';
            
                // Check if the reservation already exists and is for the same user (ID)
                if (check_collision($file, $date, $timeslot, $reservations, $id)) {
                    $reservation_collision = true;
                } else {
                    // Update the reservation if no collision is found
                    editReservation($id, $date, $timeslot, $quantity);
                }
        break;
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
        <button class="toggleEditForm user_managment_button">Upravit můj profil</button>
    </div>
    <div class="right-text">
        <img class="profile_picture_view"src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profilový obrázek">
    </div>
</article>

<div id="editFormUser" class="editForm">
<?php
            $firstname = $user['firstname'];
            $lastname = $user['lastname'];
            $email = $user['email'];
            $phone = $user['phone'];
            $errors = [];
            $formValid = true; // for is valid at the beginning
        ?>
        <!-- Regular user view -->
    <form action="" method="post">
        <h2> Upravit můj profil</h2>
        <?php
            include './php/structure/form_temeplate.php'; ?> 
        <button type="submit" name="action" value="update_self">Upravit</button>       
    </form>
</div>
<article>
        <h2>Moje rezervace</h2>
        <?php if (!empty($userReservations)): ?>
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
                    <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($reservation['id']); ?>">
                        <button type="submit" name="action" value="delete_reservation" class="remove_reservations user_managment_button">Smazat</button>
                    </form>

                    <button class="toggleEditReservationForm user_managment_button">Upravit</button>
                    <div class="editReservationForm hidden">
                        <?php include './php/edit_reservation_form.php'; ?>
                        <?php if ($reservation_collision): ?>
                            <p class="error">Rezervace v tomto čase již existuje.</p>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nemáte žádné rezervace.</p>
        <?php endif; ?>

        <div class="reservation_link">
        <a href="rezervace.php">Rezervace</a> 
    </div>
</article>


<!-- Admin view -->
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
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <!-- User rows will be dynamically added here -->
            </tbody>
        </table>
<button class="user_managment_button" id="loadMore">Načíst uživatele</button>
<button class="user_managment_button" id="add_user">Přidat uživatele</button>
</article>


<div id="addFormUser" class="addForm hidden">
<article>
<section>
        <form action="" method="post">
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
        <button type="submit" name="action" value="add">Přidat</button>
    </form>
</section>
</article>
</div>

<div id="editDifferentFormUser" class="editDifferentFormUser hidden">
<article>
<section>
        <form action="" method="post">
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
        <button type="submit" name="action" value="update">Upravit</button>
    </form>
</section>
</article>
</div>
<?php endif; ?>
<?php include './php/structure/footer.php'; ?>
<script src="./scripts/load_users.js" ></script> 
<script src="./scripts/profile.js" ></script> 
<script src="./scripts/register.js"></script> 
</body>
</html>