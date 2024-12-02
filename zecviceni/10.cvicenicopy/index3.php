<?php


var_dump($_GET);
var_dump($_COOKIE);

if (isset($_GET["theme"])){   
    setcookie("theme", $_GET["theme"]); # set cookie funguje
    $theme = $_GET["theme"];


} else {
    $theme = $_COOKIE["theme"] ?? "light";

}

$lang = $_COOKIE["lang"] ?? "cs"; ## pokud neni nic v cookie tak se pouzije cs
if (isset($_GET["lang"])){
    setcookie("lang",$_GET["lang"]);
    $lang = $_GET["lang"];

}


if (isset($_COOKIE["visits"])){
    $visits = (int)$_COOKIE["visits"] + 1;

 
} else {
    $visits = 1;

}




$texts = [
    "en" => ["greeting" == "Hello"],
    "cs " =>["greeting" == "AHoj"],

]

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body.light {
            background-color: white;
            color: black
        }
        body.dark{
            background-color: black;
            color:white
        }
    </style>
</head>

<body class="<?php echo htmlspecialchars($theme) ?>">
    <h1><?= $texts["lang"]["greeting"] ?? "Language not supported."?></h1>

    <p> Téma: <?= htmlspecialchars($theme)?></p>
    <p> Jazyk <?= htmlspecialchars($lang)?></p>
    <p> Navstevy <?= htmlspecialchars($visits)?></p>
    <a href="?theme=light">Light mode </a>
    <a href="?theme=dark">Dark mode</a>

    <br>

    <a href="?lang=cs">čeština</a>
    <a href="?lang=en">angličtina/a>

</body>
</html>