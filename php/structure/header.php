<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : null;
$firstName = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : null;

$profilePicture = './img/default-profile.png'; // Default profile picture

if ($email) {
    $usersFile = './user_data/users.json';
    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);
        if (is_array($users)) {
            foreach ($users as $user) {
                if ($user['email'] === $email) {
                    if (isset($user['profile_picture'])) {
                        $profilePicture = htmlspecialchars($user['profile_picture']);
                    }
                    if (!$firstName && isset($user['firstname'])) {
                        $firstName = htmlspecialchars($user['firstname']);
                    }
                    break;
                }
            }
        }
    }
}
?>
<header>
    <nav>
        <div class="logo_corner">
            <a href="index.php"><img src="./img/logo1.png" alt="logo"></a>
        </div>
        <div class="computer_screen">
            <div class="right-links">
                <a class="links" href="cenik.php">Ceník</a>
                <a class="links" href="restaurace.php">Restaurace</a>

                <?php if ($email): ?>
                    <a class="links_active" href="profil.php">
                        <img class="profile_picture" src="<?php echo $profilePicture; ?>" alt="Profil">
                        <?php echo $firstName; ?>
                    </a>
                    <a class="links" href="./php/logout.php">Odhlásit se</a>
                <?php else: ?>
                    <a class="links_active" href="prihlaseni.php">Přihlášení</a>
                    <a class="links" href="registrace.php">Registrace</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="mobile_screens">
            <ul class="menu">
                <li><a class="menuItem" href="cenik.php">Ceník</a></li>
                <li><a class="menuItem" href="restaurace.php">Restaurace</a></li>

                <?php if ($email): ?>
                    <li>
                        <a class="menuItem" href="profil.php">
                            <img class="profile_picture" src="<?php echo $profilePicture; ?>" alt="Profil">
                            <?php echo $email; ?>
                        </a>
                    </li>
                    <li><a class="menuItem" href="./php/logout.php">Odhlásit se</a></li>
                <?php else: ?>
                    <li><a class="menuItem" href="prihlaseni.php">Přihlášení</a></li>
                    <li><a class="menuItem" href="registrace.php">Registrace</a></li>
                <?php endif; ?>
            </ul>
            <button class="hamburger">
                <img src="./img/menu.png" height="32" width="32" alt="Menu" class="menuIcon"> 
                <img src="./img/menu_white.png" height="32" width="32" alt="Close" class="closeIcon" style="display: none;"> 
            </button>
        </div>
    </nav>
    <script src="./scripts/toggle_menu.js" type=module></script>
</header>
