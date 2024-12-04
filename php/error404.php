<?php
/**
 * Display error
 */
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Document</title>
</head>
<body>
<?php 
    include './structure/header.php'; 
    ?>
    
    <div class="error_site">
    <h2> něco se nepovedlo </h2>
    <img src="../img/404_cat.jpg" alt="ikona trati">
    <a class="links" href="index.php">Zpět na úvodní stránku</a>
    </div>
    <?php include './structure/footer.php'; ?>
</body>
</html>