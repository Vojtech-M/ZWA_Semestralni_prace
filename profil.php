<?php
/**
 * Job: User profile
 * This file contains a user profile page. It displays user information and allows the user to edit their profile.
 */
require "./php/check_login.php";
require "./php/validation.php";
require "./php/data_handler.php";
require "./php/file_upload.php";
require './php/user_action.php';

if (!isset($_SESSION['id'])) {
    header("Location: ./login.php");
    exit;
}
// csrf token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Load user data
$user = getDataById($_SESSION['id']);
$userReservations = getUserReservations($_SESSION['id']);
$defaultProfilePicture = './img/profile.png';
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <?php require "./php/structure/head.html"; ?>
    <link rel="stylesheet" href="./css/profile.css">
</head>
<body>
<?php include './php/structure/header.php'; ?>

<!-- User information -->
<article>
    <!-- Display result of users's actions-->
    <?php if(isset($user_data_result)): ?>
        <div class="user_data_result"><?= htmlspecialchars($user_data_result) ?></div>
    <?php endif; ?>

    <div class="left-text">
        <h1>Profil uživatele</h1>
        <p>Jméno: <?php echo htmlspecialchars($user['firstname']); ?></p>
        <p>Příjmení: <?php echo htmlspecialchars($user['lastname']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Telefonní číslo: <?php echo htmlspecialchars($user['phone']); ?></p>
    </div>
    <div class="right-text">
        <img class="profile_picture_view" src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profilový obrázek">
    </div>
</article>

<article>
    <form action="profil.php" method="post" id="updateProfileForm" enctype="multipart/form-data">
        <h2>Upravit můj profil</h2>
        <p>Pro změnu údajů je nutné zadat aktuální heslo.</p>
        <?php
            // Default values from user data or previous submission
            $firstname = $_POST['firstname'] ?? $user['firstname'];
            $lastname = $_POST['lastname'] ?? $user['lastname'];
            $email = $_POST['email'] ?? $user['email'];
            $phone = $_POST['phone'] ?? $user['phone'];
        ?>
        <!-- First Name and Last Name -->
        <div class="form_field">
            <label for="firstname" class="required_label">Jméno</label>
            <input type="text" id="firstname" name="firstname" 
                pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{3,50}$" 
                title="Jméno musí obsahovat pouze písmena a být dlouhé 3 až 50 znaků"
                value="<?= htmlspecialchars($firstname); ?>" 
                placeholder="Tomáš">
            <?php if (isset($errors['firstname'])): ?>
                <div class="error" id="firstNameError"><?= htmlspecialchars($errors['firstname']) ?></div>
            <?php endif; ?>

            <label for="lastname" class="required_label">Příjmení</label>
            <input type="text" id="lastname" name="lastname" 
                pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{3,50}$" 
                title="Příjmení musí obsahovat pouze písmena a být dlouhé 3 až 50 znaků"
                value="<?= htmlspecialchars($lastname); ?>" 
                placeholder="Novák">
            <?php if (isset($errors['lastname'])): ?>
                <div class="error" id="lastNameError"><?= htmlspecialchars($errors['lastname']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Email and Phone -->
        <div class="form_field">
            <label for="email_field" class="required_label">Email</label>
            <input id="email_field" type="email" name="email" 
                value="<?= htmlspecialchars($email); ?>" 
                required placeholder="example@mail.com">
            <?php if (isset($errors['email'])): ?>
                <div class="error" id="emailError"><?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>

            <label for="phone_field" class="phone_label">Telefonní číslo</label>
            <input id="phone_field" type="text" name="phone" 
                value="<?= htmlspecialchars($phone); ?>" 
                placeholder="606136603"
                pattern="[0-9]{9}" title="Telefonní číslo musí obsahovat 9 čísel">
            <?php if (isset($errors['phone'])): ?>
                <div class="error" id="phone_fieldError"><?= htmlspecialchars($errors['phone']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Current Password -->
        <div class="form_field">
            <label for="current_password" class="required_label">Aktuální heslo</label>
            <input type="password" id="current_password" name="current_password" 
                placeholder="Zadejte aktuální heslo" required 
                title='Heslo musí obsahovat alespoň jedno velké písmeno jedno číslo a jeden speciální znak (!@#$%^&*(),.?\":{}|<></>).'>
            <?php if (isset($errors['current_password'])): ?>
                <div class="error" id="currentPasswordError"><?= htmlspecialchars($errors['current_password']) ?></div>
            <?php endif; ?>
            <div class="error" id="update_self_password_error"></div>
        </div>

        <!-- Profile Picture -->
        <div class="form_field">
            <label for="myFile">Profilový obrázek</label>
            <input type="file" id="myFile" name="file">
            <?php if (isset($errors['image'])): ?>
                <div class="error"><?= htmlspecialchars($errors['image']) ?></div>
            <?php endif; ?>
        </div>

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
        <!-- Submission Button -->
        <button type="submit" class="user_managment_button" 
                name="action" value="update_self" >
                Upravit
        </button>
    </form>
</article>

<article>
<form action="profil.php" id="change_password_form" method="post">
    <h2>Změna hesla</h2>
    <p>Pro změnu hesla zadejte aktuální heslo a nové heslo.</p>

    <!-- Current Password -->
    <div class="form_field">
        <label for="current_password_change" class="required_label">Aktuální heslo</label>
        <input type="password" id="current_password_change" name="current_password_change" 
               placeholder="Zadejte aktuální heslo" required
               pattern=".{8,50}" title='Heslo musí obsahovat alespoň jedno velké písmeno jedno číslo a jeden speciální znak (!@#$%^&*(),.?\":{}|<></>).'>
        <?php if (isset($errors['current_password_change'])): ?>
            <div class="error"><?= htmlspecialchars($errors['current_password_change']) ?></div>
        <?php endif; ?>
        <div class="error" id="password_error_current_password"></div>
    </div>

    <!-- New Password -->
    <div class="form_field">
        <label for="new_password_change" class="required_label">Nové heslo</label>
        <input type="password" id="new_password_change" name="new_password_change" 
               placeholder="Zadejte nové heslo" required
               pattern=".{8,50}" title='Heslo musí obsahovat alespoň jedno velké písmeno jedno číslo a jeden speciální znak (!@#$%^&*(),.?\":{}|<></>).'>
        <?php if (isset($errors['new_password_change'])): ?>
            <div class="error"><?= htmlspecialchars($errors['new_password_change']) ?></div>
        <?php endif; ?>
        <div class="error" id="password_error_new_password"></div>
    </div>

    <!-- Confirm New Password -->
    <div class="form_field">
        <label for="confirm_password_change" class="required_label">Potvrzení nového hesla</label>
        <input type="password" id="confirm_password_change" name="confirm_password_change" 
               placeholder="Zadejte heslo znovu" required
               pattern=".{8,50}" title='Heslo musí obsahovat alespoň jedno velké písmeno jedno číslo a jeden speciální znak (!@#$%^&*(),.?\":{}|<></>).'>
        <?php if (isset($errors['confirm_password_change'])): ?>
            <div class="error"><?= htmlspecialchars($errors['confirm_password_change']) ?></div>
        <?php endif; ?>
        <div class="error" id="password_error_confirm_new_password"></div>
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <!-- Submission Button -->
    <button type="submit" class="user_managment_button" 
            name="action" value="update_password">
        Změnit heslo
    </button>
</form>
</article>

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
        <a href="reservation.php">Upravit rezervaci</a> 
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
                    <th>Telefonní číslo</th>
                   
                </tr>
            </thead>
            <tbody id="userTableBody">
            </tbody>
        </table>
<button class="user_managment_button" id="loadMore">Načíst uživatele</button>
<button class="user_managment_button" id="add_user">Přidat uživatele</button>
</article>

<article class="addForm hidden">
<h2>Přidat nového uživatele</h2>
<form action="profil.php" method="post" id="add_user_form" enctype="multipart/form-data">
    <!-- First Name -->
    <div class="form_field">
        <label for="firstname_add_user" class="required_label">Jméno</label>
        <input type="text" id="firstname_add_user" name="firstname_add_user" 
               value="<?= isset($firstname_add_user) ? htmlspecialchars($firstname_add_user) : ''; ?>" 
                pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{3,50}$" 
                title="Jméno musí obsahovat pouze písmena a být dlouhé 3 až 50 znaků"
               required placeholder="Tomáš">

        <?php if (isset($errors['firstname_add_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['firstname_add_user']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Last Name -->
    <div class="form_field">
        <label for="lastname_add_user" class="required_label">Příjmení</label>
        <input type="text" id="lastname_add_user" name="lastname_add_user" 
               value="<?= isset($lastname_add_user) ? htmlspecialchars($lastname_add_user) : ''; ?>" 
               required placeholder="Novák" 
                pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{3,50}$" 
                title="Příjmení musí obsahovat pouze písmena a být dlouhé 3 až 50 znaků">
        <?php if (isset($errors['lastname_add_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['lastname_add_user']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Email -->
    <div class="form_field">
        <label for="email_add_user" class="required_label">Email</label>
        <input type="email" id="email_add_user" name="email_add_user" 
               value="<?= isset($email_add_user) ? htmlspecialchars($email_add_user) : ''; ?>" 
               required placeholder="example@mail.com">
        <?php if (isset($errors['email_add_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['email_add_user']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Phone -->
    <div class="form_field">
        <label for="phone_add_user" class="phone_label">Telefonní číslo</label>
        <input type="text" id="phone_add_user" name="phone_add_user" 
               value="<?= isset($phone_add_user) ? htmlspecialchars($phone_add_user) : ''; ?>" 
               placeholder="606136603"
               pattern="[0-9]{9}" title="Telefonní číslo musí obsahovat 9 čísel">
        <?php if (isset($errors['phone_add_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['phone_add_user']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Role Selection -->
    <div class="form_field">
        <label for="role_add_user" class="required_label">Role</label>
        <select id="role_add_user" name="role_add_user" required>
            <option value="" disabled >Select role</option> 
            <option value="user" <?= isset($role_add_user) && $role_add_user === 'user' ? 'selected' : ''; ?>>Uživatel</option>
            <option value="admin" <?= isset($role_add_user) && $role_add_user === 'admin' ? 'selected' : ''; ?>>Administrátor</option>
        </select>
    </div>

    <!-- Profile Picture -->
    <div class="form_field">
        <label for="file_add_user">Profilový obrázek</label>
        <input type="file" id="file_add_user" name="file_add_user">
        <?php if (isset($errors['image_add_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['image_add_user']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Password -->
    <div class="form_field">
        <label for="password_add_user" class="required_label">Heslo</label>
        <input type="password" id="password_add_user" name="password_add_user" 
               required placeholder="Zadejte heslo">
        <?php if (isset($errors['password_add_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['password_add_user']) ?></div>
        <?php endif; ?>
        <div class="error" id="password_error_add_user"></div>
    </div>

    <!-- Confirm Password -->
    <div class="form_field">
        <label for="confirm_password_add_user" class="required_label">Potvrzení hesla</label>
        <input type="password" id="confirm_password_add_user" name="confirm_password_add_user" 
               required placeholder="Heslo znovu">
        <?php if (isset($errors['confirm_password_add_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['confirm_password_add_user']) ?></div>
        <?php endif; ?>
        <div class="error" id="password_error_confirm_add_user"></div>
    </div>

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    <!-- Submit Button -->
    <button type="submit" class="user_managment_button" name="action" value="add_user">
        Přidat uživatele
    </button>
</form>
</article>

    <article>
    <h2>Upravit uživatele</h2>
    <form action="profil.php" method="post" enctype="multipart/form-data">
    <div class="form_field">
        <label for="id_edit_user" class="required_label">ID</label>
        <input type="text" id="id_edit_user" name="id_edit_user" required value="<?= isset($id_edit_user) ? htmlspecialchars($id_edit_user) : '' ?>">
        <?php if (isset($errors['id_edit_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['id_edit_user']) ?></div>
        <?php endif; ?>
    </div>

    <!-- First Name -->
    <div class="form_field">
        <label for="firstname_edit_user" class="required_label">Jméno</label>
        <input type="text" id="firstname_edit_user" name="firstname_edit_user" required 
          pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{3,50}$" 
                title="Jméno musí obsahovat pouze písmena a být dlouhé 3 až 50 znaků"
        value="<?= isset($firstname_edit_user) ? htmlspecialchars($firstname_edit_user) : '' ?>">
        <?php if (isset($errors['firstname_edit_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['firstname_edit_user']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Last Name -->
    <div class="form_field">
        <label for="lastname_edit_user" class="required_label">Příjmení</label>
        <input type="text" id="lastname_edit_user" name="lastname_edit_user" required 
          pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]{3,50}$" 
                title="Příjmení musí obsahovat pouze písmena a být dlouhé 3 až 50 znaků"
        value="<?= isset($lastname_edit_user) ? htmlspecialchars($lastname_edit_user) : '' ?>">
        <?php if (isset($errors['lastname_edit_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['lastname_edit_user']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Email -->
    <div class="form_field">
        <label for="email_edit_user" class="required_label">Email</label>
        <input type="email" id="email_edit_user" name="email_edit_user" required value="<?= isset($email_edit_user) ? htmlspecialchars($email_edit_user) : '' ?>">
        <?php if (isset($errors['email_edit_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['email_edit_user']) ?></div>
        <?php endif; ?>
    </div>

    <div class="form_field">
        <label for="phone_edit_user" class="required_label">Telefonní číslo</label>
        <input type="text" id="phone_edit_user" name="phone_edit_user" 
        value="<?= isset($phone_edit_user) ? htmlspecialchars($phone_edit_user) : '' ?>"
        pattern="[0-9]{9}" title="Telefonní číslo musí obsahovat 9 čísel">
        <?php if (isset($errors['phone_edit_user'])): ?>
            <div class="error"><?= htmlspecialchars($errors['phone_edit_user']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Role -->
    <div class="form_field">
        <label for="role_edit_user" class="required_label">Role</label>
        <select id="role_edit_user" name="role_edit_user" required>
            <option value="" disabled>Select role</option> 
            <option value="user" <?= isset($role_edit_user) && $role_edit_user === 'user' ? 'selected' : '' ?>>Uživatel</option>
            <option value="admin" <?= isset($role_edit_user) && $role_edit_user === 'admin' ? 'selected' : '' ?>>Administrátor</option>
        </select>
    </div>

    <!-- Profile Picture -->
    <div class="form_field">
        <label for="file_edit_user">Profilový obrázek</label>
        <input type="file" id="file_edit_user" name="file_edit_user">
        <?php if (isset($errors['image_edit_user'])): ?>
            <div class="error"><?=htmlspecialchars($errors['image_edit_user']) ?></div>
        <?php endif; ?>
    </div>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    <!-- Submit Button -->
    <button type="submit" class="user_managment_button" name="action" value="edit_user">
        Upravit uživatele
    </button>
</form>
</article>

<article>
<h2>Resetovat heslo uživatele</h2>
<form action="profil.php" id="reset_password_form" method="post">
    <!-- User ID -->
    <div class="form_field">
        <label for="user_id_reset" class="required_label">ID uživatele</label>
        <input type="text" id="user_id_reset" name="user_id_reset" 
               value="<?= isset($user_id_reset) ? htmlspecialchars($user_id_reset) : ''; ?>" 
               required placeholder="Zadejte ID uživatele">
        <?php if (isset($errors['user_id_reset'])): ?>
            <div class="error"><?= htmlspecialchars($errors['user_id_reset']) ?></div>
        <?php endif; ?>
    </div>

    <!-- New Password -->
    <div class="form_field">
        <label for="new_password_reset" class="required_label">Nové heslo</label>
        <input type="password" id="new_password_reset" name="new_password_reset" required placeholder="Zadejte nové heslo">
        <?php if (isset($errors['new_password_reset'])): ?>
            <div class="error"><?= htmlspecialchars($errors['new_password_reset']) ?></div>
        <?php endif; ?>
        <div class="error" id="password_error_new_password_reset"></div>
    </div>

    <!-- Confirm New Password -->
    <div class="form_field">
        <label for="confirm_password_reset" class="required_label">Potvrzení nového hesla</label>
        <input type="password" id="confirm_password_reset" name="confirm_password_reset" required placeholder="Potvrďte nové heslo">
        <?php if (isset($errors['confirm_password_reset'])): ?>
            <div class="error"><?= htmlspecialchars($errors['confirm_password_reset']) ?></div>
        <?php endif; ?>
        <div class="error" id="password_error_confirm_new_password_reset"></div>
    </div>
    
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    
    <!-- Submit Button -->
    <button type="submit" name="action" class="user_managment_button" value="reset_password">Resetovat heslo</button>
</form>
</article>

<article>
    <h2>Smazat uživatele</h2>
    <form action="profil.php" method="post">
    <!-- User ID -->
    <div class="form_field">
        <label for="user_id_delete" class="required_label">ID uživatele</label>
        <input type="text" id="user_id_delete" name="user_id_delete" 
               value="<?= isset($user_id_delete) ? htmlspecialchars($user_id_delete) : ''; ?>" 
               required placeholder="Zadejte ID uživatele">
        <?php if (isset($errors['user_id_delete'])): ?>
            <div class="error"><?= htmlspecialchars($errors['user_id_delete']) ?></div>
        <?php endif; ?>
    </div>
    
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    
    <!-- Submit Button -->
    <button type="submit" name="action" class="user_managment_button" value="delete_user">Smazat uživatele</button>
</form>
</article>

<?php endif; ?>
<?php include './php/structure/footer.php'; ?>
<script src="./scripts/load_users.js" ></script> 
<script type="module" src="./scripts/profile.js" ></script> 
<script src="./scripts/register.js"></script> 
</body>
</html>

