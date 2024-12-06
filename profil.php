<?php
include './php/check_login.php';
include "./php/lib.php";

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $user = getDataById($user_id);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $role = $_POST['role'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $passwd = $_POST['passwd'];
        $profile_picture = './img/profile.png';
        addUser($role,$firstname, $lastname, $email, $phone, $passwd,$profile_picture);
    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $role = $_POST['role'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['passwd'];
        $profile_picture = './img/profile.png';
        editUser($id, $role,$firstname, $lastname, $email,$phone, $password, $profile_picture);
    } elseif ($action === 'delete') {
        $id = $_POST['id'];
        deleteUser($id);
    }
}
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
    </div>
    <div class="right-text">
        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profilový obrázek">
    </div>
</article>

<article>
      
        <h2>Moje rezervace</h2>
        <?php if (!empty($userReservations)): ?>
            <ul>
                <?php foreach ($userReservations as $reservation): ?>
                    <li>
                        Datum: <?php echo htmlspecialchars($reservation['date']); ?>,
                        Čas: <?php echo htmlspecialchars($reservation['time']); ?>,
                        Počet lidí: <?php echo htmlspecialchars($reservation['quantity']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nemáte žádné rezervace.</p>
        <?php endif; ?>
</article>

<article>
        <!-- Regular user view -->
        <form method="post" enctype="multipart/form-data">
            <label for="firstname">Jméno:</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>"><br>
            <?php if (isset($errors['firstname'])): ?>
                <div class="error"><?php echo $errors['firstname']; ?></div>
            <?php endif; ?>

            <label for="lastname">Příjmení:</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>"><br>
            <?php if (isset($errors['lastname'])): ?>
                <div class="error"><?php echo $errors['lastname']; ?></div>
            <?php endif; ?>

            <label for="phone">Telefonní číslo:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br>
            <?php if (isset($errors['phone'])): ?>
                <div class="error"><?php echo $errors['phone']; ?></div>
            <?php endif; ?>

            <label for="profile_picture">Profilový obrázek:</label>
            <input type="file" name="profile_picture" id="profile_picture"><br>
            <?php if (isset($errors['profile_picture'])): ?>
                <div class="error"><?php echo $errors['profile_picture']; ?></div>
            <?php endif; ?>

            <input type="submit" value="Uložit změny">
        </form>
</article>
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
                    <input type="text" name="phone" required>
                </label>
                <label>
                    Heslo:
                    <input type="text" name="passwd" required>
                </label>
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
                    <input type="text" name="passwd" required>
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

</body>
</html>