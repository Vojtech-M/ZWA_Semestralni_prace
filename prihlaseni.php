<?php
session_start(); // Start session, no session_unset() here to preserve session data on login

// Check if user data file exists and load user data
$usersFile = './user_data/users.json';
$users = [];
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
    if ($users === null) {
        echo "<script>alert('Error loading user data');</script>";
    }
} else {
    echo "<script>alert('User data file not found');</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $password = $_POST['password'];

    // Verify if $users contains valid data
    if (is_array($users)) {
        foreach ($users as $user) {
            if ($user['firstname'] === $firstname && password_verify($password, $user['password'])) {
                $_SESSION['firstname'] = $firstname;
                header("Location: index.php");
                exit();
            }
        }
        // Invalid credentials message
        
        if (isset($_SESSION['login_error'])) {
            echo "<p class='error'>{$_SESSION['login_error']}</p>";
            unset($_SESSION['login_error']);
        }
    } else {
        echo "<script>alert('User data not available');</script>";
    }
}


?>


<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Author" content="Vojtěch Michal">
    <meta name="Keywords" content="motokáry"><meta>
    <meta description="Nejzábavnější motokárová dráha ve středních Čechách.">
    <title>Motokárové centrum Benešov</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/helma.png"> 
</head>
<body>
    <?php 
    include './php/structure/header.php'; 
    ?>
    <section class="registrace">
        <div class ="formular">
            <form action="" method="post">
            <div id="name">
                <label for="firstname" class="custom_text">Firstname:</label>
                <input type="text" id="firstname" name="firstname" value="<?php if(isset($_GET['firstname'])) echo(htmlspecialchars($_GET['firstname']));?>" required placeholder="Tomáš"  tabindex="1">
            </div>
            <div id="passwd">
                <label for="password">Heslo:</label>
                <input type="password" name="password" id="password" value="<?php if(isset($_GET['password'])) echo(htmlspecialchars($_GET['password']));?>" required tabindex="2">
            </div>
                <input type="submit" name="login" value="Přihlásit se" tabindex="3">
            </form>
        </div>
    </section>
    <?php include './php/structure/footer.php'; ?>
</body>
</html>