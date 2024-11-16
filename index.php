<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    die();
}
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
    <link rel="icon" id="favicon" type="image/png" href="./img/helma.png"> 
</head>
<body>

<noscript><p>No JS. there to see </p></noscript>
<?php include './php/structure/header.php'; ?>

<div class="hero-image">
    <div class="hero-heading ">
        <h2 class>Rychlost</h2>
        <div class="hero-text">
            <p>je naše vášeň!</p>
        </div>
    </div>
</div>
    <a  href="./zecviceni/test.html">REZERVACE</a> <!--smazat -->
    <a  href="./zecviceni/naseptava.html">naseptava</a> <!--smazat -->
    <a href="./zecviceni/8.cviceni/index.html">tttsfsdtt</a>

<section class="features">
    <div class="small_text">
        <img src="./img/trat_ikona.svg" height="128" width="128" alt="ikona trati">
        <h3>Dráha</h3>
        <p>800 m</p>

    </div>
    <div class="small_text">
        <img src="./img/restaurace2_ikona.svg" height="128" width="128" alt="ikona trati">
        <h3> Restaurace</h3>
        <p>Až pro 80 lidí </p>
    </div>
    <div class="small_text">
    <img src="./img/konfety_ikona.svg" height="128" width="128" alt="ikona trati">
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
        <img src="./img/bitmapa.png" width="500" alt="okruh" >
    </div>
</article>

<article>
    <div class="left-text">
        <img src="./img/gokarts.jpg" alt="motokáry start" >
    </div>
    <div class="right-text">
        <h3>Trocha historie. </h3>
        <p>Začátek motokár v Benešově se datuje do roku 1996, kdy Petr Chovančík na místním autodromu zorganizoval první závody pro veřejnost. Postupem času se areál rozrůstal, vybavení zlepšovat a stále více lidí nacházelo vášeň pro tento adrenalinový sport. Dnes je náš kartingový areál oblíbeným místem nejen pro rekreační jezdce, ale i pro závodníky, kteří chtějí zdokonalit své dovednosti.</p>
    </div>
</article>

<div class="reservations">
    <div class="reservation_text">
    <h2>Neváhejte</h2>
    <h3>udělejte si rezervaci na dráze!</h3>
    </div>
    
    <div class="reservation_link">
    <?php if ($username): ?> <!-- prihlaseny / neprihlaseny-->
            <a href="rezervace.php">REZERVACE</a>       
        <?php else: ?>
            <a href="prihlaseni.php">REZERVACE</a>
        <?php endif; ?>
    </div>
</div>

<?php include './php/structure/footer.php'; ?>
<!-- Dám script nakonec-->
<!--<script src="./scripts/hello.js" type=module></script> -->
</body>
</html>









