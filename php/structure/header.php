
<?php
/*
* This file contains the header of the website.

* The header contains the logo and navigation bar.
* The navigation bar contains links to the price list, restaurant, login, registration, and profile page.
* The navigation bar changes based on the user's login status.
* If the user is logged in, the navigation bar contains the user's profile picture and first name.
* If the user is not logged in, the navigation bar contains links to the login and registration pages.
*/

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $user = getDataById($user_id);
    $firstName = $user['firstname'];
    $lastName = $user['lastname'];
    $email = $user['email'];
    $profilePicture = $user['profile_picture'];
}
?>
<noscript>Pro správné fungování této stránky je nutné povolit Javascript </noscript>

<header>
    <div class="logo_corner">
        <a href="index.php"><img src="./img/icons/logo.png" alt="logo"></a>
    </div>

    <div class="computer_screen">
        <div class="right-links">
            <a class="links" href="price_list.php">Ceník</a>
            <a class="links" href="restaurant.php">Restaurace</a>

            <?php if (isset($_SESSION['id'])): ?>
                <a class="links_active" href="profil.php">
                    <img class="profile_picture" src="<?php echo $profilePicture; ?>" alt="Profil">
                    <?php echo $firstName; ?>
                </a>
                <a class="links" href="./php/logout.php">Odhlásit se</a>
            <?php else: ?>
                <a class="links_active" href="login.php">Přihlášení</a>
                <a class="links" href="registration.php">Registrace</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="mobile_screens">
        <ul class="menu">
            <li><a class="menuItem" href="price_list.php">Ceník</a></li>
            <li><a class="menuItem" href="restaurant.php">Restaurace</a></li>

            <?php if (isset($_SESSION['id'])): ?>
                <li>
                    <a class="menuItem" href="profil.php">
                        <img class="profile_picture" src="<?php echo $profilePicture; ?>" alt="Profil">
                        <?php echo $firstName; ?>
                    </a>
                </li>
                <li><a class="menuItem" href="./php/logout.php">Odhlásit se</a></li>
            <?php else: ?>
                <li><a class="menuItem" href="login.php">Přihlášení</a></li>
                <li><a class="menuItem" href="registration.php">Registrace</a></li>
            <?php endif; ?>
        </ul>
        <button class="hamburger">
            <img src="./img/icons/menu.png" alt="Menu" class="menuIcon"> 
            <img src="./img/icons/menu_white.png" alt="Close" class="closeIcon" style="display: none;"> 
        </button>
    </div>
    <script src="./scripts/toggle_menu.js" type=module></script>
</header>
