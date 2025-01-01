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
if (!isset($_SESSION['id'])) {
    header("Location: ./login.php");
    exit;
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Load user data
$user = getDataById($_SESSION['id']);
$userReservations = getUserReservations($_SESSION['id']);
$defaultProfilePicture = './img/profile.png';

include './php/user_action.php';

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

<article>
<form action="profil.php" method="post" enctype="multipart/form-data">
    <h2>Upravit můj profil</h2>
    <p>Pro změnu údajů je nutné zadat aktuální heslo.</p>

    <?php
        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
        $email = $user['email'];
        $phone = $user['phone'];
    ?>

    <!-- First Name and Last Name -->
    <div class="form_field">
        <label for="firstname" class="required_label">Jméno</label>
        <input type="text" id="firstname" name="firstname" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*"value="<?php echo htmlspecialchars($firstname); ?>"  placeholder="Tomáš" tabindex="1">
        <?php if (isset($errors['firstname'])): ?>
            <div class="error" id="firstNameError"><?= htmlspecialchars($errors['firstname']) ?></div>
        <?php endif; ?>

        <label for="lastname" class="required_label">Příjmení</label>
        <input type="text" id="lastname" name="lastname" 
               pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*" 
               value="<?php echo htmlspecialchars($lastname); ?>" 
               placeholder="Novák" tabindex="2">
        <?php if (isset($errors['lastname'])): ?>
            <div class="error" id="lastNameError"><?= htmlspecialchars($errors['lastname']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Email and Phone -->
    <div class="form_field">
        <label for="email_field" class="required_label">Email</label>
        <input id="email_field" type="email" name="email" 
               value="<?php echo htmlspecialchars($email); ?>" 
               required placeholder="example@mail.com" tabindex="3">
        <?php if (isset($errors['email'])): ?>
            <div class="error" id="emailError"><?= htmlspecialchars($errors['email']) ?></div>
        <?php endif; ?>

        <label for="phone_field" class="phone_label">Telefonní číslo</label>
        <input id="phone_field" type="text" name="phone" 
               value="<?php echo htmlspecialchars($phone); ?>" 
               placeholder="606136603" tabindex="4">
        <?php if (isset($errors['phone'])): ?>
            <div class="error" id="phone_fieldError"><?= htmlspecialchars($errors['phone']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Current Password -->
    <div class="form_field">
        <label for="current_password" class="required_label">Aktuální heslo</label>
        <input type="password" id="current_password" name="current_password" 
               placeholder="Zadejte aktuální heslo" required tabindex="5">
        <?php if (isset($errors['current_password'])): ?>
            <div class="error" id="currentPasswordError"><?= htmlspecialchars($errors['current_password']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Profile Picture -->
    <div class="form_field">
        <label for="myFile">Profilový obrázek</label>
        <input type="file" id="myFile" name="file" tabindex="6">
        <?php if (isset($errors['image'])): ?>
            <div class="error"><?= htmlspecialchars($errors['image']) ?></div>
        <?php endif; ?>
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <!-- Submission Button -->
    <button type="submit" class="user_managment_button" 
            name="action" value="update_self" tabindex="7">
        Upravit
    </button>
</form>
</article>

<article>
    <form action="profil.php" method="post">
        <h2>Změna hesla</h2>
        <p>Pro změnu hesla zadejte aktuální heslo a nové heslo.</p>

        <!-- Current Password -->
        <div class="form_field">
            <label for="current_password" class="required_label">Aktuální heslo</label>
            <input type="password" id="current_password" name="current_password" 
                   placeholder="Zadejte aktuální heslo" required>
            <?php if (isset($errors['current_password_change'])): ?>
                <div class="error"><?= htmlspecialchars($errors['current_password_change']) ?></div>
            <?php endif; ?>
        </div>

        <!-- New Password -->
        <div class="form_field">
            <label for="new_password" class="required_label">Nové heslo</label>
            <input type="password" id="new_password" name="new_password" 
                   placeholder="Zadejte nové heslo" required>
                   
            <?php if (isset($errors['new_password'])): ?>
                <div class="error"><?= htmlspecialchars($errors['new_password']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Confirm New Password -->
        <div class="form_field">
            <label for="confirm_password" class="required_label">Potvrzení nového hesla</label>
            <input type="password" id="confirm_password" name="confirm_password" 
                   placeholder="Zadejte heslo znovu" required>
            <?php if (isset($errors['confirm_password'])): ?>
                <div class="error"><?= htmlspecialchars($errors['confirm_password']) ?></div>
            <?php endif; ?>
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

<!-- Add user form -->
<article>
        <h2>Přidat nového uživatele</h2>
        <form action="profil.php" method="post" enctype="multipart/form-data">
            <!-- First Name -->
            <div class="form_field">
                <label for="firstname_add_user" class="required_label">Jméno</label>
                <input type="text" id="firstname_add_user" name="firstname_add_user" value="<?php echo isset($firstname_add_user) ? htmlspecialchars($firstname_add_user) : ''; ?>"  required placeholder="Tomáš" tabindex="1">
                <?php if (isset($errors['firstname_add_user'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['firstname_add_user']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Last Name -->
            <div class="form_field">
                <label for="lastname_add_user" class="required_label">Příjmení</label>
                <input type="text" id="lastname_add_user" name="lastname_add_user" required placeholder="Novák" tabindex="2">
                <?php if (isset($errors['lastname_add_user'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['lastname_add_user']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="form_field">
                <label for="email_add_user" class="required_label">Email</label>
                <input type="email" id="email_add_user" name="email_add_user" required placeholder="example@mail.com"  tabindex="3">
                <?php if (isset($errors['email_add_user'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['email_add_user']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div class="form_field">
                <label for="phone_add_user" class="phone_label">Telefonní číslo</label>
                <input type="text" id="phone_add_user" name="phone_add_user" placeholder="606136603" tabindex="4">
                <?php if (isset($errors['phone_add_user'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['phone_add_user']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Role Selection -->
            <div class="form_field">
                <label for="role_add_user" class="required_label">Role</label>
                <select id="role_add_user" name="role_add_user" required>
                    <option value="user">Uživatel</option>
                    <option value="admin">Administrátor</option>
                </select>
            </div>

            <!-- Profile Picture -->
            <div class="form_field">
                <label for="file_add_user">Profilový obrázek</label>
                <input type="file" id="file_add_user" name="file_add_user" tabindex="5">
            </div>

            <!-- Password -->
            <div class="form_field">
                <label for="password_add_user" class="required_label">Heslo</label>
                <input type="password" id="password_add_user" name="password_add_user" required placeholder="Zadejte heslo" tabindex="6">
                <?php if (isset($errors['password_add_user'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['password_add_user']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div class="form_field">
                <label for="confirm_password_add_user" class="required_label">Potvrzení hesla</label>
                <input type="password" id="confirm_password_add_user" name="confirm_password_add_user" required placeholder="Heslo znovu" tabindex="7">
                <?php if (isset($errors['confirm_password_add_user'])): ?>
                    <div class="error"><?=
                        htmlspecialchars($errors['confirm_password_add_user']) ?></div>
                <?php endif; ?>
            </div>

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <!-- Submit Button -->
            <button type="submit" class="user_managment_button" name="action" value="add_user" tabindex="7">
                Přidat uživatele
            </button>
        </form>
    </article>

    <article>
    <h2>Upravit uživatele</h2>
    <form action="profil.php" method="post" enctype="multipart/form-data">
        <div class="form_field">
            <label for="id_edit_user" class="required_label">ID</label>
            <input type="text" id="id_edit_user" name="id_edit_user" required>
            <?php if (isset($errors['id_edit_user'])): ?>
                <div class="error"><?= htmlspecialchars($errors['firstname']) ?></div>
            <?php endif; ?>
        </div>

        <!-- First Name -->
        <div class="form_field">
            <label for="firstname" class="required_label">Jméno</label>
            <input type="text" id="firstname" name="firstname" required>
            <?php if (isset($errors['firstname'])): ?>
                <div class="error"><?= htmlspecialchars($errors['firstname']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Last Name -->
        <div class="form_field">
            <label for="lastname" class="required_label">Příjmení</label>
            <input type="text" id="lastname" name="lastname" required>
            <?php if (isset($errors['lastname'])): ?>
                <div class="error"><?= htmlspecialchars($errors['lastname']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="form_field">
            <label for="email" class="required_label">Email</label>
            <input type="email" id="email" name="email"required>
            <?php if (isset($errors['email'])): ?>
                <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form_field">
            <label for="phone" class="required_label">Telefonní číslo</label>
            <input type="text" id="phone" name="phone"required>
            <?php if (isset($errors['phone_edit_user'])): ?>
                <div class="error"><?= htmlspecialchars($errors['phone_edit_user']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Role -->
        <div class="form_field">
            <label for="role" class="required_label">Role</label>
            <select id="role" name="role" required>
                <option value="user">Uživatel</option>
                <option value="admin">Administrátor</option>
            </select>
        </div>

        <!-- Profile Picture -->
        <div class="form_field">
            <label for="file">Profilový obrázek</label>
            <input type="file" id="file" name="file">
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <!-- Submit Button -->
        <button type="submit" class="user_managment_button" name="action" value="edit_user">
            Upravit uživatele
        </button>
    </form>
</article>

<article>
    <h2>Resetovat heslo uživatele</h2>
    <form action="profil.php" method="post">
        <!-- User ID -->
        <div class="form_field">
            <label for="user_id" class="required_label">ID uživatele</label>
            <input type="text" id="user_id" name="user_id" required placeholder="Zadejte ID uživatele">
            <?php if (isset($errors['user_id'])): ?>
                <div class="error"><?= htmlspecialchars($errors['user_id']) ?></div>
            <?php endif; ?>
        </div>

        <!-- New Password -->
        <div class="form_field">
            <label for="new_password" class="required_label">Nové heslo</label>
            <input type="password" id="new_password" name="new_password" required placeholder="Zadejte nové heslo">
            <?php if (isset($errors['new_password'])): ?>
                <div class="error"><?= htmlspecialchars($errors['new_password']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Confirm New Password -->
        <div class="form_field">
            <label for="confirm_new_password" class="required_label">Potvrzení nového hesla</label>
            <input type="password" id="confirm_new_password" name="confirm_new_password" required placeholder="Potvrďte nové heslo">
            <?php if (isset($errors['confirm_new_password'])): ?>
                <div class="error"><?= htmlspecialchars($errors['confirm_new_password']) ?></div>
            <?php endif; ?>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <!-- Submit Button -->
        <button type="submit" name="action" value="reset_password">Resetovat heslo</button>
    </form>
</article>

<article>
    <h2>Smazat uživatele</h2>
    <form action="profil.php" method="post">
        <!-- User ID -->
        <div class="form_field
        ">
            <label for="user_id_delete" class="required_label">ID uživatele</label>
            <input type="text" id="user_id_delete" name="user_id_delete" required placeholder="Zadejte ID uživatele">
            <?php if (isset($errors['user_id_delete'])): ?>
                <div class="error"><?=
                    htmlspecialchars($errors['user_id_delete']) ?></div>
            <?php endif; ?>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <!-- Submit Button -->
            <button type="submit" name="action" value="delete_user">Smazat uživatele</button>
        </div>
</article>

<?php endif; ?>
<?php include './php/structure/footer.php'; ?>
<script src="./scripts/load_users.js" ></script> 
<script src="./scripts/profile.js" ></script> 
<script src="./scripts/register.js"></script> 
</body>
</html>

