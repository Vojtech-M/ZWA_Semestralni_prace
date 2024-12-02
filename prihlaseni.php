<?php
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Get existing data from the JSON file
    $file = './user_data/users.json';
    if (file_exists($file)) {
        $jsonData = file_get_contents($file);
        $jsonArray = json_decode($jsonData, true);

        // Check if the email exists and the password matches
        foreach ($jsonArray as $user) {
            if ($user['email'] == $email && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['email'] = $user['email'];
                $_SESSION['firstname'] = $user['firstname'];
                $_SESSION['lastname'] = $user['lastname'];
                $_SESSION['role'] = $user['role'];

                // Redirect to profile page
                header("Location: profil.php");
                exit();
            }
        }
        $error = "Nesprávný email nebo heslo.";
    } else {
        $error = "Uživatelská data nejsou k dispozici.";
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
    <meta description="Nejzábavnější motokárová dráha ve středních Čechách.">
    <title>Motokárové centrum Benešov</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/helma.png"> 
</head>
<body>
    <?php include './php/structure/header.php'; ?>
    <section class="registrace">
        <div class="formular">
            <a href="index.php"><img src="./img/logo3.png" alt="logo"></a>
            <form action="" id="loginForm" method="post">
                <div id="name">
                    <label for="email" class="custom_text">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="example@seznam.cz" tabindex="1">
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
    <script src="./scripts/login.js" type=module></script> 
</body>
</html>