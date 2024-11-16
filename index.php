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
    <link rel="icon" type="image/png" sizes="32x32" href="./img/helma.png"> 
    <link rel=stylesheet href='./css/print.css' media="print">     <!-- Při použítí @media nebo media ='print'-->
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


<!-- 

prototyp si pamatuje z ceho vznikl


udelat si objekt

XMLHttpRequest

nepouuivat DOM content loaded

prvek muze mit vice class oddelenych mezerou 

pozor na innerHTML



<div class="tri_texty">
    <div class="small_text">
        <h3>AJAX</h3>
        <p>800 m</p>

    </div>
    <div class="small_text">
        <h3> Restaurace</h3>
        <p>až pro 80 lidí </p>
    </div>
</div>






    ?php   
   if ((5+2==4) == true): ?>
   This will show if the expression is true.
   php else: 
   Otherwise this will show.
   php endif; 
 <SMAZAT DO ODEVZDÁNÍ  (naját důvod to tady nechat :D) ################################################################
 <div class="black_betty">
        <h3> Stránka vznikla za poslechu🤘: (PS: SMAZAT)  </h3>   -SMAZAT DO ODEVZDÁNÍ 
        <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/7uSsHbBFFAnkRQR1rDwP3L?utm_source=generator" 
            width="1000" height="352" allowfullscreen="" 
            allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" 
            loading="lazy"></iframe>        
    </div>       
SMAZAT DO ODEVZDÁNÍ  ################################################################
    <div class="druhy_motokar">
        <h2> druhy motokar </h2>
            <div class="pagination">
                <a href="#">&laquo;</a>
                <a href="tech.html">1</a>
                <a class="active" href="#">2</a>
                <a href="#">3</a>
                <a href="#">4</a>
                <a href="#">5</a>
                <a href="#">6</a>
                <a href="#">&raquo;</a>
        </div>
    </div>

    
    <section class="info_banner">
        <div class="banner-content">
            <h2> Každý čtvrtek sleva na jízdy !</h2>
        </div>
    </section>




     <section class="description">
        <div class="description">
            <div class="description_text">
                <h2>Tohle je další článek</h2>
                <p>Motokárové centrum nabízí závodnický zážitek na jedné z nejzajímavějších tratí v regionu. 
                    Na ploše 8.000 m² vás čeká více než 40 zatáček s průměrnou rychlostí kolem 40 km/h. 
                    Celková délka dráhy je 1.000 m, krátká varianta měří 800 m a samostatný dětský okruh má 200 m.</p>
            </div>
        </div>
        <div class="stand_image">
            <img src="./img/bitmapa.png" alt="okruh" >
        </div>
    </section>



     <footer class="footer">
        <div class="copyright">
            <p>Copyright © 2024 Vojtěch Michal</p>
        </div>
        
        <div class="documentation_page">
            <a class="links" href="dokumentace.html">Dokumentace</a>
        </div>

        <div class="footer-text">
            <p>Motokárové centrum Benešov</p>
        </div>
    </footer>
        -->








