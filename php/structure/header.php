<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : null;
?>
<header>
    <nav>
        <div class="logo_corner">
            <a href="index.php"><img src="./img/logo1.png" height="130" width="240" alt="logo"></a>
        </div>
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
    </nav>
</header>