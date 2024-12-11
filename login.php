<?php
require "./php/check_login.php";
include './php/validation.php';

$errors = [];
$email = "";
$password = "";
$valid = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $errors["email"] = validateEmail($email);
    $errors["passwd"] = validatePassword($password,$password);

    $errors = array_filter($errors);

    if (empty($errors)) {
        $formValid = true;
    } else {
        $formValid = false;
    }

    if ($valid) {
        $isExist = false;
        $usersFile = './user_data/users.json';
        $users = json_decode(file_get_contents($usersFile), true);
        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $isExist = true;
                $_SESSION['id'] = $user['id']; // Save user ID in session
                header("Location: ./index.php");
                exit;
            }
        }
    }
} else {
    $email = ""; // Ensure email is empty for first load
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
    <link rel="stylesheet" href="./css/layout.css">
</head>
<body>
    <?php include './php/structure/header.php'; ?>
    <section class="registrace">
        <div class="login_formular">
            <h2>Přihlášení</h2> 
            <form action="" id="loginForm" method="post">
                <div id="name">
                    <label for="email" class="custom_text">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="example@mail.com" tabindex="1">
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
                    <div class="error" id="emailError"></div>
                </div>
                <div id="passwd">
                    <label for="password">Heslo:</label>
                    <input type="password" name="password" id="password" value="<?php if(isset($_GET['password'])) echo(htmlspecialchars($_GET['password']));?>" required placeholder="vase heslo" tabindex="2">
                    <div class="error" id="passwordError"></div>
                <?php if (isset($errors['passwd'])): ?>
                    <div class="error" id="pass2Error"><?= htmlspecialchars($errors['passwd']) ?></div>
                <?php endif; ?>
                </div>
                <input type="submit" name="login" value="Přihlásit se" tabindex="3">
                <?php if (!empty($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <p> Ještě nemáte učet ? <a class="register_link" href="registrace.php">Zaregistrujte se !</a> </p>
                
            </form>
        </div>
    </section>
    <?php include './php/structure/footer.php'; ?>
    <script src="./scripts/login.js" type=module></script> 
</body>
</html>