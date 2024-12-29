<?php
 include "./php/check_login.php";
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <?php include "./php/structure/head.html"; ?>
    <link rel="stylesheet" href="./css/restaurant.css">

</head>

<body>
<?php include './php/structure/header.php'; ?>    


<article> 
    <h2>O naší restauraci</h2>
    <p>Naše restaurace nabízí jedinečný zážitek s širokým výběrem pokrmů připravených z čerstvých surovin. Prostředí je vhodné pro rodinné oslavy, firemní akce a příjemné posezení po závodech.</p>
    <p>Specializujeme se na českou kuchyni, ale na našem jídelním lístku najdete i mezinárodní speciality. Součástí restaurace je také bar s nabídkou místních i světových nápojů.</p>

    <section class="center-content">
        <a href="./pdf/bistro.pdf" target="_blank">
            <img src="./img/bistro_nahled.png" alt="menu náhled" title="Prohlédnout">
        </a>

        <h2>Otevírací hodiny</h2>
        <ul>
            <li>Pondělí - Pátek: 10:00 - 22:00</li>
            <li>Sobota: 9:00 - 23:00</li>
            <li>Neděle: 9:00 - 21:00</li>
        </ul>
    </section>
</article>

<?php include './php/structure/footer.php'; ?>
</body>
</html>