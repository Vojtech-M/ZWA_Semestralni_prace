<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['firstname'])) {
    header('Location: prihlaseni.php'); // Redirect to login if not logged in
    exit();
}

// Load user data from JSON
$usersFile = './user_data/users.json';
$users = json_decode(file_get_contents($usersFile), true);

$userData = null;
if (is_array($users)) {
    foreach ($users as $user) {
        if ($user['firstname'] === $_SESSION['firstname']) {
            $userData = $user; // Get the logged-in user's data
            break;
        }
    }
}

if ($userData === null) {
    echo "User data not found!";
    exit();
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
    <title>Motokárové centrum Benešov - Profil</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>

<?php include './php/structure/header.php'; ?>

<article>
        <div class="left-text">
            <h1>Profil uživatele</h1>
            <p>Jméno: <?php echo htmlspecialchars($userData['firstname']); ?></p>
            <p>Příjmení: <?php echo htmlspecialchars($userData['lastname']); ?></p>
            <p>Adresa: <?php echo htmlspecialchars($userData['address']); ?></p>
            <p>PSČ: <?php echo htmlspecialchars($userData['postal']); ?></p>
            <p>Email: <?php echo htmlspecialchars($userData['email']); ?></p>
            <p>Telefonní číslo: <?php echo htmlspecialchars($userData['phone']); ?></p>
        </div>
        <div class="right-text">
            <img src="<?php echo htmlspecialchars($userData['profile_picture']); ?>" width="500" alt="Profilový obrázek">
        </div>
    </article>

<?php include './php/structure/footer.php'; ?>

</body>
</html>
