<?php
/**
 * @author Vojtěch Michal
 * 
 * Job: Display the main page
 * This file contains the main page of the website. It displays the main content and the header and footer.
 * 
 */

include "./php/check_login.php";
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <?php include "./php/structure/head.html"; ?>
    <link rel="stylesheet" href="./css/front_page.css">
</head>
<body>
<!-- Included header with navigation -->
<?php include './php/structure/header.php'; ?>

<div class="hero-image">
    <div class="hero-heading ">
        <h2 class>Rychlost</h2>
        <div class="hero-text">
            <p>je naše vášeň!</p>
        </div>
    </div>
</div>

<section class="features">
    <div class="small_text">
        <img src="./img/icons/trat_ikona.svg" alt="ikona trati">
        <h3>Dráha</h3>
        <p>800 m</p>
    </div>

    <div class="small_text">
        <img src="./img/restaurace_ikona.svg" alt="ikona restaurace">
        <h3> Restaurace</h3>
        <p>Až pro 80 lidí </p>
    </div>

    <div class="small_text">
    <img src="./img/icons/konfety_ikona.svg" alt="ikona konfety">
        <h3>Firemní akce</h3>
        <p>Netradiční setkání</p>
    </div>
</section>

<article>
    <div class="left-text">
        <h3>Náš okruh</h3>
        <p>Náš okruh s 11 vzrušujícími zatáčkami vás nenechá na pochybách o skutečném závodním zážitku! Od širokých pasáží po technické a úzké úseky, každý z nich přináší novou výzvu a zvyšuje adrenalin. Zatímco se budete snažit najít dokonalou stopu, zažijete pocit vzrušení a rychlosti. Přijďte si to vyzkoušet a objevte, co v sobě máte! </p>
    </div>
    <div class="right-text">
        <img src="./img/circuit.png" alt="mapa okruhu">
    </div>
</article>

<article>
    <div class="left-text">
        <img src="./img/gokarts.jpg" alt="motokáry historie">
    </div>
    <div class="right-text">
        <h3>Trocha historie. </h3>
        <p>Začátek motokár v Benešově se datuje do roku 1996, kdy Petr Chovančík na místním autodromu zorganizoval první závody pro veřejnost. Postupem času se areál rozrůstal, vybavení zlepšovat a stále více lidí nacházelo vášeň pro tento adrenalinový sport. Dnes je náš kartingový areál oblíbeným místem nejen pro rekreační jezdce, ale i pro závodníky, kteří chtějí zdokonalit své dovednosti.</p>
    </div>
</article>

<!-- call for action -->
<div class="reservations">
    <div class="reservation_text">
        <h2>Neváhejte</h2>
        <h3>udělejte si rezervaci na dráze!</h3>
    </div>
    
    <div class="reservation_link">
        <?php if (isset($_SESSION['id'])) { ?> 
            <a href="reservation.php">REZERVACE</a>       
        <?php } else { ?>
            <a href="login.php">REZERVACE</a>
        <?php } ?>
    </div>
</div>

<!-- Included footer -->
<?php include './php/structure/footer.php'; ?>
</body>
</html>
