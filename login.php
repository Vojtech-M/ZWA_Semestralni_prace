<?php
/**
 * Job: Login users
 * This file contains a form for user login. It checks if the user exists in the database and if the password is correct.
 */
require "./php/check_login.php";
require "./php/validation.php";
require "./php/data_handler.php";

if (isset($_SESSION['id'])) {
    header("Location: ./index.php");
    exit;
}
// Variables for form fields and errors
$errors = [];
$email = "";
$password = "";
$valid = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors["email"] = validateEmail($email);
    $errors["password"] = validatePassword($password, $password);

    $errors = array_filter($errors);
    $formValid = empty($errors);
    $userExists = false; // Variable to track if the user exists

    // If the form is valid, check if the user exists in the database
    $usersFile = './user_data/users.json';
    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $userExists = true; // User with this email exists
            if (password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id']; // Save user ID in session
                header("Location: ./index.php");
                exit;
            } else {
                $errors['password'] = "Při posledním odeslání bylo heslo špatně."; // Incorrect password error
            }
            break;
        }
    }
    // If the email does not exist in the database
    if (!$userExists) {
        $errors['email'] = "Při posledním odeslání byl e-mail špatně.";
        $errors['password'] = "Při posledním odeslání bylo heslo špatně.";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<?php include "./php/structure/head.html"; ?>
</head>
<body>
    <?php include './php/structure/header.php'; ?>
        <div class="login_formular">
            <h2>Přihlášení</h2> 
            <form action="login.php" id="loginForm" method="post">
                <!-- Email -->
                <div id="email_field">
                    <label for="email" class="custom_text required_label">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="example@mail.com" tabindex="1">
                    <?php if (isset($errors['email'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
                    <?php endif; ?>
                    <div class="error" id="emailError"></div>
                </div>

                <!-- Password -->
                <div id="password_field">
                    <label for="password" class="required_label">Heslo:</label>
                    <input type="password" name="password" id="password" value="<?php if(isset($_GET['password'])) echo(htmlspecialchars($_GET['password']));?>" required placeholder="vase heslo" pattern=".{8,50}" title="Heslo musí být mezi 8 až 50 znaky dlouhé." >
                    <div class="error" id="passwordError"></div>
                    <?php if (isset($errors['password'])): ?>
                        <div class="error" id="pass2Error"><?= htmlspecialchars($errors['password']) ?></div>
                    <?php endif; ?>
                </div>
                <input type="submit" name="login" value="Přihlásit se" tabindex="3">
                <p> Ještě nemáte učet ? <a class="register_link" href="registration.php">Zaregistrujte se !</a> </p>
            </form>
        </div>
    <?php include './php/structure/footer.php'; ?>
    <script type="module" src="./scripts/login.js"></script>
</body>
