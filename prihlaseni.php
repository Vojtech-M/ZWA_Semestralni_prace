<?php
session_start();

$usersFile = './user_data/users.json'; 
$users = [];
$error = ""; // Initialize error message
$email = ""; // Initialize email field value
$role = "";
$password = ""; // Initialize password field value

if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
    if ($users === null) {
        $error = "Error loading user data.";
    }
} else {
    $error = "User data file not found.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // Verify if $users contains valid data
    if (is_array($users)) {
        $foundUser = false;
        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;         // Set logged in status
                $_SESSION['email'] = $email;          // Store email in session
                $_SESSION['firstname'] = $user['firstname']; // Store first name in session
                $_SESSION['role'] = $user['role'];    // Store role in session
                header("Location: index.php");        // Redirect to homepage after successful login
                exit();
            }
        }
        $error = "Tento uživatel neexistuje"; // Invalid login message
    } else {
        $error = "User data not available."; // Error loading users data
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
<header>
    <nav>
        <div class="logo_corner">
            <a href="index.php"><img src="./img/logo.png" alt="Logo"></a>
        </div>
        <div class="right-links">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?>!</span>
                <a href="logout.php" class="links">Logout</a>
            <?php else: ?>
                <a href="prihlaseni.php" class="links">Login</a>
                <a href="registrace.php" class="links">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
    <?php 
    include './php/structure/header.php'; 
    ?>
    <section class="registrace">
        <div class ="formular">
            <a href="index.php"><img src="./img/logo3.png" alt="logo"></a>
            <form action="" id="loginForm" method="post">
            <div id="name">
                <label for="email" class="custom_text">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="example@seznam.cz"  tabindex="1">
                <div class="error" id="emailError"></div>
            </div>
            <div id="passwd">
                <label for="password">Heslo:</label>
                <input type="password" name="password" id="password" value="<?php if(isset($_GET['password'])) echo(htmlspecialchars($_GET['password']));?>" required placeholder="tajneheslo" tabindex="2">
                <div class="error" id="passwordError"></div>
            </div>
                <input type="submit" name="login" value="Přihlásit se" tabindex="3">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <p> Ještě nemáte učet ? Zaregistrujte se ! </p>
            <a class="links" href="registrace.php">Registrace</a>
            </form>
        </div>
    </section>
    <?php include './php/structure/footer.php'; ?>
    <script src="./scripts/login.js" type=module> </script> 
</body>
</html>