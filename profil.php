<?php
/**
 * Job: User profile
 * This file contains a user profile page. It displays user information and allows the user to edit their profile.
 */
include './php/check_login.php';
include './php/validation.php';
include './php/lib.php';
include './php/file_upload.php';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
        case 'update':
            // Handle common user form logic for add and update actions
            $id = $_POST['id'] ?? '';
            $role = $_POST['role'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $profile_picture = './img/profile.png';

            // Validate inputs
            $errors['firstname'] = validateName($firstname);
            $errors['lastname'] = validateName($lastname);
            $errors['email'] = validateEmail($email);
            $errors['phone'] = validatePhone($phone);
            $errors['password'] = validatePassword($password, $password);

            // Filter out null errors
            $errors = array_filter($errors);
            $formValid = empty($errors);

            // If valid, proceed with the respective action
            if ($formValid) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                if ($action === 'add') {
                    addUser($role, $firstname, $lastname, $email, $phone, $hash, $profile_picture);
                } elseif ($action === 'update') {
                    $id = $_POST['id'];
                    editUser($id, $role, $firstname, $lastname, $email, $phone, $hash, $profile_picture);
                }
            }
            break;

        case 'delete':
            $id = $_POST['id'];
            deleteUser($id);
            loadUsers();
            break;

        case 'update_self':
            // Handle self profile update logic
            $user = getDataById($_SESSION['id']);
            $id = $_SESSION['id'];
            $role = $user['role'];
            $firstname = htmlspecialchars($_POST['firstname']);
            $lastname = htmlspecialchars($_POST['lastname']);
            $email = htmlspecialchars($_POST['email']);
            $phone = htmlspecialchars($_POST['phone']);
            $password = htmlspecialchars($_POST['password']);
            $profile_picture = $user['profile_picture'];

            // Validate inputs for self profile update
            $errors['firstname'] = validateName($firstname);
            $errors['lastname'] = validateName($lastname);
            $errors['email'] = validateEmail($email);
            $errors['phone'] = validatePhone($phone);
            $errors['password'] = validatePassword($password, $password);

            // Filter out null errors
            $errors = array_filter($errors);

            // Handle file upload if any
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $fileUploadResult = handleFileUpload('profile_picture');
                if ($fileUploadResult['success']) {
                    $profile_picture = $fileUploadResult['filePath'];
                    deleteProfilePicture($user);
                }
            }

            // If valid, update the user profile
            if (empty($errors)) {
                editUser($id, $role, $firstname, $lastname, $email, $phone, $password, $profile_picture);
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

            // Edit reservation logic (if needed, you can validate here)
            editReservation($id, $date, $timeslot, $quantity);
            break;
    }
}
$user = getDataById($_SESSION['id']);
$userReservations = getUserReservations($_SESSION['id']);

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Author" content="Vojtěch Michal">
    <meta name="Keywords" content="motokáry">
    <meta name="description" content="Nejzábavnější motokárová dráha ve středních Čechách.">
    <title>Motokárové centrum Benešov</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/helma.png"> 
    <link rel="stylesheet" href="./css/layout.css">
    <link rel="stylesheet" href="./css/profile.css">
</head>
<body>

<?php include './php/structure/header.php'; ?>

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

<div id="editFormUser" class="editForm hidden">
        <!-- Regular user view -->
        <form action="" method="post">
                <h2> Upravit můj profil</h2>
                <?php
                    $firstname = $user['firstname'];
                    $lastname = $user['lastname'];
                    $email = $user['email'];
                    $phone = $user['phone'];
                    $errors = [];
                    $formValid = true; // formulář je na začátku valid
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
                            <button type="submit" name="action" value="delete_reservation" class="remove_reservations">Smazat</button>
                        </form>

                    <button class="toggleEditReservationForm">Upravit</button>
                    <div class="editReservationForm hidden">
                        <form action="" method="post">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($reservation['id']); ?>">
                            <label>
                                Datum:
                                <input type="date" name="date" value="<?php echo htmlspecialchars($reservation['date'] ?? ''); ?>" required>
                            </label>
                            <label>
                                Čas:
                                <label for="timeslot">Čas rezervace</label>
                                    <select name="timeslot" id="timeslot" required>
                                        <option value="14" <?php echo ($reservation['timeslot'] == 14); ?>>14:00 - 15:00</option>
                                        <option value="15" <?php echo ($reservation['timeslot'] == 15); ?>>15:00 - 16:00</option>
                                        <option value="16" <?php echo ($reservation['timeslot'] == 16); ?>>16:00 - 17:00</option>
                                        <option value="17" <?php echo ($reservation['timeslot'] == 17); ?>>17:00 - 18:00</option>
                                        <option value="18" <?php echo ($reservation['timeslot'] == 18); ?>>18:00 - 19:00</option>
                                        <option value="19" <?php echo ($reservation['timeslot'] == 19); ?>>19:00 - 20:00</option>
                                        <option value="20" <?php echo ($reservation['timeslot'] == 20); ?>>20:00 - 21:00</option>
                                        <option value="21" <?php echo ($reservation['timeslot'] == 21); ?>>21:00 - 22:00</option>
                                        <option value="22" <?php echo ($reservation['timeslot'] == 22); ?>>22:00 - 23:00</option>
                                    </select>
                            </label>
                            <label>
                                Počet lidí:
                                <input type="number" name="quantity" value="<?php echo htmlspecialchars($reservation['quantity']); ?>" required>
                            </label>
                            <button type="submit" name="action" value="edit_reservation">Uložit</button>
                        </form>
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
        <button type="submit" name="action" value="update">Přidat</button>
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
        <button type="submit" name="action" value="update">Přidat</button>
    </form>
</section>
</article>
</div>
<?php endif; ?>
<?php include './php/structure/footer.php'; ?>
<script src="./scripts/load_users.js" ></script> 
<script src="./scripts/profile.js" ></script> 
</body>
</html>

                


<!-- 
?php 
$firstname = $user['firstname'];
include './php/structure/form_temeplate.php'; ?>
 -->



            <!-- CREATE 
            <h2>PPřidat nového uživatele</h2>
            <form action="" method="post">
                <label for="role">Vyberte roli:</label>
                    <select id="role" name="role">
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                    </select> 
                <label>
                    Jméno:
                    <input type="text" name="firstname" required>
                    <php if (isset($errors['firstname'])): ?>
                        <div class="error"><php echo $errors['firstname']; ?></div>
                    <php endif; ?>
                </label>
                <label>
                    Příjmení:
                    <input type="text" name="lastname" required>
                    <php if (isset($errors['lastname'])): ?>
                        <div class="error"><php echo $errors['lastname']; ?></div>
                    <php endif; ?>
                </label>
                <label>
                    Email:
                    <input type="email" name="email" required>
                    <php if (isset($errors['email'])): ?>
                        <div class="error"><php echo $errors['email']; ?></div>
                    <php endif; ?>
                </label>
                <label>
                    Telefon:
                    <input type="text" name="phone">
                    <php if (isset($errors['phone'])): ?>
                        <div class="error"><php echo $errors['phone']; ?></div>
                    <php endif; ?>
                </label>
                <label>
                    Heslo:
                    <input type="password" name="password" required>
                    <php if (isset($errors['password'])): ?>
                        <div class="error"><php echo $errors['password']; ?></div>
                    <php endif; ?>
                </label>
              <label for="profile_picture">Profilový obrázek:</label>
                    <input type="file" name="profile_picture" id="profile_picture"><br>
                     php if (isset($errors['profile_picture'])): ?> 
                        <div class="error">php echo $errors['profile_picture']; ?></div>-->
                    <!-- php endif; ?> 
                <input type="file" id="myFile" name="file" tabindex="8"> 

                <button type="submit" name="action" value="add">Přidat</button>
            </form>-->

            <!-- UPDATE 
            <h2>Upravit uživatele</h2>
            <php
            $users = loadUsers(); // This could be from a database or a JSON file
            ?>
           <form action="" method="post">
                <h2>Upravit uživatele</h2>
                <label for="user_select">Vyberte uživatele:</label>
                <select id="user_select" name="id" required>
                    <option value="">-- Vyberte uživatele --</option>
                    <//?php foreach ($users as $user): ?>
                        <option value="<//?php echo htmlspecialchars($user['id']); ?>"><//?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></option>
                    <//?php endforeach; ?>
                </select>
                <label for="role">Vyberte roli:</label>
                    <select id="role" name="role">
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                    </select> 
                <label>
                <label>
                    Jméno:
                    <input type="text" name="firstname" required>
                </label>
                <label>
                    Příjmení:
                    <input type="text" name="lastname" required>
                </label>
                <label>
                    Email:
                    <input type="email" name="email" required>
                </label>
                <label>
                    Telefon:
                    <input type="text" name="phone" required>
                </label>
                <label>
                    Heslo:
                    <input type="text" name="password" required>
                </label>
                <label>
                    Profilový obrázek:
                    <input type="file" name="profile_picture">
                </label>
                <img class="profile_picture_view" src="" alt="Profilový obrázek">
                <//?php if (isset($errors['firstname'])):?>
                        <div class="error"></?php echo $errors['firstname']; ?></div>
                    <//?php endif; ?>
                <button type="submit" name="action" value="update">Upravit</button>
            </form>

            <form action="" method="post">
                <label for="role">Vyberte roli:</label>
                    <select id="role" name="role">
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                    </select> 
                <label>
                <label>
                    ID uživatele:
                    <input type="text" name="id" required>
                </label>
                <label>
                    Jméno:
                    <input type="text" name="firstname" required>
                </label>
                <label>
                    Příjmení:
                    <input type="text" name="lastname" required>
                </label>
                <label>
                    Email:
                    <input type="email" name="email" required>
                </label>
                <label>
                    Telefon:
                    <input type="text" name="phone" required>
                </label>
                <label>
                    Heslo:
                    <input type="text" name="password" required>
                </label>
                <label>
                    Profilový obrázek:
                    <input type="file" name="profile_picture">
                </label>
                <button type="submit" name="action" value="update">Upravit</button>
            </form>

             DELETE 
            <h2>Smazat uživatele</h2>
             <form action="" method="post">
                <label>
                    ID uživatele:
                    <input type="text" name="id" required>
                </label>
                <button type="submit" name="action" value="delete">Smazat</button>
            </form> -->
