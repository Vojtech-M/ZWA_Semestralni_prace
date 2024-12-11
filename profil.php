<?php
include './php/check_login.php';
include './php/validation.php';
include "./php/lib.php";
include './php/file_upload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $passwd = $_POST['password'];
        $profile_picture = './img/profile.png';

        $errors['firstname'] = validateName($firstname);
        $errors['lastname'] = validateName($lastname);
        $errors['email'] = validateEmail($email);
        $errors['phone'] = validatePhone($phone);
        $errors['passwd'] = validatePassword($passwd, $passwd);
    
        // Filter out null values from errors
        $errors = array_filter($errors);
    
        if (empty($errors)) {
            $formValid = true;
        } else {
            $formValid = false;
        }
    
        $hash = password_hash($passwd, PASSWORD_DEFAULT);  // zaheshování hesla


        if ($formValid) {
        addUser($role,$firstname, $lastname, $email, $phone, $hash,$profile_picture);
        } 

    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $role = $_POST['role'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $profile_picture = './img/profile.png';
        editUser($id, $role,$firstname, $lastname, $email,$phone, $password, $profile_picture);
    } elseif ($action === 'delete') {
        $id = $_POST['id'];
        deleteUser($id);
    } elseif ($action === 'update_self'){
        $user = getDataById($_SESSION['id']);
        $id = $_SESSION['id'];
        $role = $user['role'];
        $firstname = htmlspecialchars(trim($_POST['firstname']));
        $lastname = htmlspecialchars(trim($_POST['lastname']));
        $originalEmail = $user['email'];
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $password = htmlspecialchars(trim($_POST['password']));
        $profile_picture = $user['profile_picture'];

        $file = './user_data/users.json';
        if (file_exists($file)) {
            $jsonData = file_get_contents($file);
            $jsonArray = json_decode($jsonData, true);
        } else {
            $jsonArray = [];
        }
    

        $errors['firstname'] = validateName($firstname);
        $errors['lastname'] = validateName($lastname);
        $errors['email'] = validateEmail($email);
        $errors['phone'] = validatePhone($phone);
        $errors['password'] = validatePassword($password, $password);

        // Filter out null values from errors
        $errors = array_filter($errors);

        if (empty($errors)) {
            $formValid = true;
        } else {
            $formValid = false;
        }
    

        // Check if the email already exists
         // Pokud se e-mail změnil, zkontrolujeme, zda již neexistuje
        if ($email !== $originalEmail) {
            $users = loadUsers();
            foreach ($users as $user) {
                if ($user['email'] === $email) {
                    $errors['email'] = "Tento e-mail je již používán.";
                    break;
                }
            }
        }

        // Handle file upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileUploadResult = handleFileUpload('profile_picture');
            if ($fileUploadResult['success']) {
                $profile_picture = $fileUploadResult['filePath'];
                deleteProfilePicture($user);
            }
        }
        if ($formValid) {
            editUser($id, $role,$firstname, $lastname, $email,$phone, $password, $profile_picture);
        }
     
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
        <button class="toggleEditForm">Upravit můj profil</button>
    </div>
    <div class="right-text">
        <img class="profile_picture_view"src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profilový obrázek">
    </div>
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
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nemáte žádné rezervace.</p>
        <?php endif; ?>
</article>

<div id="editFormUser" class="editForm hidden">
        <!-- Regular user view -->

        <form action="" method="post">
                <h2> Upravit profil</h2>
                <label>
                    Jméno:
                    <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                </label>
                <label>
                    Příjmení:
                    <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                    <?php if (isset($errors['lastname'])): ?>
                        <div class="error"><?php echo $errors['firstname']; ?></div>
                    <?php endif; ?>
                </label>
                <label>
                    Email:
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error"><?php echo $errors['email']; ?></div>
                    <?php endif; ?>
                </label>
                <label>
                    Telefon:  </label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    <?php if (isset($errors['phone'])): ?>
                        <div class="error"><?php echo $errors['phone']; ?></div>
                    <?php endif; ?>
                <label class="required_label">
                    Heslo </label>
                    <input type="password" name="password" required>
                    <?php if (isset($errors['password'])): ?>
                        <div class="error"><?php echo $errors['password']; ?></div>
                    <?php endif; ?>
                   
                    <p>Pro změnu údajů je nutné zadat heslo</p>
                <label for="profile_picture">Profilový obrázek:</label>
                    <input type="file" name="profile_picture" id="profile_picture"><br>
                    <?php if (isset($errors['profile_picture'])): ?>
                        <div class="error"><?php echo $errors['profile_picture']; ?></div>
                    <?php endif; ?>
                <button type="submit" name="action" value="update_self">Upravit</button>
            </form>
</div>

<?php if ($user["role"] == 'admin'): ?>
        <!-- Admin view -->
<article>
    <div>
        <h2>Seznam uživatelů</h2>
        <ul id="userList"></ul>
        <button id="loadMore">Načíst více uživatelů</button>
    </div>
    <div class="reservation_link">
        <a href="rezervace.php">Správa rezervací</a> 
    </div>
</article>

    <article>
        <!-- CRUD -->
        <section>
            <!-- CREATE -->
            <h2>Přidat nového uživatele</h2>
            <form action="" method="post">
                <label for="role">Vyberte roli:</label>
                    <select id="role" name="role">
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                    </select> 
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
                    <input type="text" name="phone">
                </label>
                <label>
                    Heslo:
                    <input type="text" name="password" required>
                </label>
                <!-- <label for="profile_picture">Profilový obrázek:</label>
                    <input type="file" name="profile_picture" id="profile_picture"><br>
                     php if (isset($errors['profile_picture'])): ?> 
                        <div class="error">php echo $errors['profile_picture']; ?></div>-->
                    <!-- php endif; ?> 
                <input type="file" id="myFile" name="file" tabindex="8"> -->

                <button type="submit" name="action" value="add">Přidat</button>
            </form>

            <!-- UPDATE -->
            <h2>Upravit uživatele</h2>
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

            <!-- DELETE -->
            <h2>Smazat uživatele</h2>
            <form action="" method="post">
                <label>
                    ID uživatele:
                    <input type="text" name="id" required>
                </label>
                <button type="submit" name="action" value="delete">Smazat</button>
            </form>
        </section>
        </article>


    <?php endif; ?>
</article>
<?php include './php/structure/footer.php'; ?>
<script src="./scripts/load_users.js" ></script> 
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.querySelector("button.toggleEditForm");
    toggleButton.addEventListener("click", function () {
        const element = document.querySelector(".editForm");
        if (element.style.display === "none" || element.style.display === "") {
            element.style.display = "flex";
        } else {
            element.style.display = "none";
        }
    });
});
</script>
</body>
</html>