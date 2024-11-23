<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : null;
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

                <?php if ($username): ?>
                    <a class="links_active" href="profil.php"> <img class="profile_picture "src="./img/profile.png" alt="profil"> <?php echo $username; ?></a>
                    <a class="links" href="logout.php">Odhlásit se</a>
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

                <?php if ($username): ?>
                    <li><a class="menuItem" href="profil.php"> <img class="profile_picture "src="./img/profile.png" alt="profil"> <?php echo $username; ?></a></li>
                    <li><a class="menuItem" href="logout.php">Odhlásit se</a></li>
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
    <script src="./scripts/toggle_menu.js" type=module> </script>
</header>
