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
    <form action="" method="post" enctype="multipart/form-data">
        <h2> Upravit můj profil</h2>
        <?php
            include './php/structure/form_temeplate.php'; ?> 
        <button type="submit" name="action" value="update_self">Upravit</button>   
    </form>
</article>




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
                </tr>
            </thead>
            <tbody id="userTableBody">
                <!-- User rows will be dynamically added here -->
            </tbody>
        </table>
<button class="user_managment_button" id="loadMore">Načíst uživatele</button>
<button class="user_managment_button" id="add_user">Přidat uživatele</button>
<button class="user_managment_button" id="edit_user">Upravit uživatele</button>
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
        <button type="submit" name="action" value="add">Přidat</button>
    </form>
</section>
</article>
</div>


<article id="editFormUser" class="editForm hidden">
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
        <button type="submit" name="action" value="update">Upravit</button>
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