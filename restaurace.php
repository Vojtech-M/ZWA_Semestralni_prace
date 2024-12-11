<?php
 include "./php/check_login.php";
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Author" content="Vojtěch Michal">
    <meta name="Keywords" content="motokáry">
    <meta name="description" content="Nejzábavnější motokárová dráha ve středních Čechách.">
    <title>Motokárové centrum Benešov</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/helma.png"> 
    <link rel=stylesheet href='./css/print.css' media=print>   
    <link rel="stylesheet" href="./css/layout.css">
</head>

<body>
<?php include './php/structure/header.php'; ?>    


<article> 
        <h2>O naší restauraci</h2>
        <p>Naše restaurace nabízí jedinečný zážitek s širokým výběrem pokrmů připravených z čerstvých surovin. Prostředí je vhodné pro rodinné oslavy, firemní akce a příjemné posezení po závodech.</p>
        <p>Specializujeme se na českou kuchyni, ale na našem jídelním lístku najdete i mezinárodní speciality. Součástí restaurace je také bar s nabídkou místních i světových nápojů.</p>
        <h2>Otevírací hodiny</h2>
        <ul>
            <li>Pondělí - Pátek: 10:00 - 22:00</li>
            <li>Sobota: 9:00 - 23:00</li>
            <li>Neděle: 9:00 - 21:00</li>
        </ul>
        <p>Přijďte si užít skvělý závodní i gastronomický zážitek. Těšíme se na vaši návštěvu!</p>
  

    <section class="nabidka_restaurace">
        <div class="jidelni_listek">
            <a href="./pdf/randomPDF2.pdf" target="_blank">
                <img src="./img/menu_nahled.png" alt="menu náhled" title="Prohlédnout">
            </a>
            <div class="jidelni_listek_button">
                <a href="./pdf/randomPDF2.pdf" target="_blank">Prohlédnout</a>
            </div>
        </div>

        <div class="denni_menu">
            <a href="./pdf/randomPDF" target="_blank">
                <img src="./img/menu_nahled.png" alt="menu náhled" title="Prohlédnout">
            </a>
            <div class="jidelni_listek_button">
                <a href="./pdf/randomPDF.pdf" target="_blank">Prohlédnout</a>
            </div>
        </div>
    </section>
    </article>


<?php include './php/structure/footer.php'; ?>
</body>
</html>