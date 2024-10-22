<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Author" content="Vojtěch Michal">
    <meta name="Keywords" content="motokáry"><meta>
    <meta description="Nejzábavnější motokárová dráha ve středních Čechách.">
    <title>Motokárové centrum Benešov</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/helma.png"> 
</head>
<body>
    <header>
        <nav>
            <div class="left-links">
                <a href="index.php"><img src="./img/logo1.png" height="130" width="240" alt="logo"/></a>
                </a>
            </div>
            <div class="right-links">
                <a class="links" href="cenik.php">Ceník</a>
                <a class="links" href="restaurace.php">Restaurace</a>
                <a class="links" href="prihlaseni.php">Přihlášení</a>
                <a class="links" href="registrace.php">registrace</a>
            </div>
        </nav>
    </header>

    <section class="info_banner">
        <div class="banner-content">
            <h2> Každý čtvrtek sleva na jízdy !</h2>
        </div>
    </section>




    <h2>tohle je prihlseni</h2>

    <!-- P5idat validaci-->
    <section class="registrace">
        <div class ="formular">
            <!--  poslání pomocí POST  action="prihlaseni.php" method="post" -->
            <form>
                <div id="name">
                    <label for="firstname" class="custom_text"> </span>*Jméno</label>
                    <input type="text" id="firstname" name="firstname" value="" required placeholder="Tomáš"  tabindex="1">
                    <label for="lastname">Příjmení</label>
                    <input type="text" id="lastname" name="lastname" value="" required placeholder="Novák"  tabindex="2">
                </div>
                
                <div id="passwd">
                    <label for="pass1_field">Heslo</label>
                    <input id="pass1_field" type="password" name="passwd" required placeholder="Heslo" tabindex="7">
                </div>
                <br>
                <input id="reg_submit" type="submit" value="Přihlásit se" tabindex="10">
                <h5> Něco je povinný</h5>
            </form>
        </div>
    </section>

    <section class="reservations">
        <div class="book">
            <h2>Neváhejte a udělejte si rezervaci na dráze !</h2>
        </div>

    </section>





<footer class="footer">
    <div class="footer-text">
        <p>Motokárové centrum Benešov</p>
    </div>
</footer>
</body>
</html>