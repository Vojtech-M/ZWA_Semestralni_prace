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
    <link rel="stylesheet" href="./css/styles.css">
    <title>Document</title>
</head>
<body>
<?php 
    include './php/structure/header.php'; 
    ?>
    
    <h2> něco se nepovadlo </h2>
    <a class="links" href="index.php">Zpět na úvodní stránku</a>
    <?php include './php/structure/footer.php'; ?>
</body>
</html>