<?php
/**
 * Display error
 * 
 * This file displays an error message and an image of a cat.
 * It redirects the user back to the home page.
 */
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<?php include "./php/structure/head.html"; ?>
</head>
<body>
<?php 
    include './php/structure/header.php'; 
    ?>
    <div class="error_site">
        <h2> něco se nepovedlo </h2>
        <img src="./img/404_cat.jpg" alt="Kočky v počítači">
        <a class="links" href="./index.php">Zpět na úvodní stránku</a>
    </div>
    <?php include './php/structure/footer.php'; ?>
</body>
</html>
