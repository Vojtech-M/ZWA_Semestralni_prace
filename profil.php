<?php
/**
 * Job: User profile
 * This file contains a user profile page. It displays user information and allows the user to edit their profile.
 */
include './php/check_login.php';
include './php/validation.php';
include './php/data_handler.php';
include './php/file_upload.php';
include './php/reservation_validation.php';
include './php/user_action.php';
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

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <?php include "./php/structure/head.html"; ?>
    <link rel="stylesheet" href="./css/profile.css">
</head>
<body>
<?php include './php/structure/header.php'; ?>

<!-- User information -->
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

<!-- Edit profile form -->
<article>
    <?php
        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
        $email = $user['email'];
        $phone = $user['phone'];
        $errors = [];
        $formValid = true; // for is valid at the beginning
    ?>
    <form action="profil.php" method="post" enctype="multipart/form-data">
        <h2> Upravit můj profil</h2>
        <h4>* Při zadání nového hesla dojde ke změně !</h4>
        <?php include './php/structure/form_temeplate.php'; ?> 
        <button type="submit" class="user_managment_button"name="action" value="update_self">Upravit</button>   
    </form>
</article>

<!-- User reservations -->
<article>
        <h2>Moje rezervace</h2>
        <?php if (!empty($userReservations)): 
        // Sort reservations by date
        usort($userReservations, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']); });
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

<!-- Admin user management -->
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

<!-- Add user form -->
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

<!-- Edit user form -->
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

<!-- Add admin rights form -->
<article>
    <h2>Přidat admin práva uživateli</h2>
        <form action="profil.php" method="post">
        <label for="user_id">ID uživatele:</label>
        <input type="text" id="user_id" name="user_id" required>
        <button type="submit" class="user_managment_button" name="action" value="add_admin">Přidat admin práva</button>
        </form>
</article>





<?php endif; ?>
<?php include './php/structure/footer.php'; ?>
<script src="./scripts/load_users.js" ></script> 
<script src="./scripts/profile.js" ></script> 
<script src="./scripts/register.js"></script> 
</body>
</html>

