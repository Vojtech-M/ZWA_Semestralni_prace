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
    <link rel="stylesheet" href="./css/layout.css">
</head>

<body>
<?php include './php/structure/header.php'; ?> 
    <section>
    <h3 class="draha_heading">Pronájmy motokár / cena za 10 min jízdy</h3>
        <div class="draha">

        <div class="rental-option">
            <div class="time-slot">Motokáry sport birel</div>
            <div class="price">250 Kč <br> </div>
        </div>
        
        <div class="rental-option">
            <div class="time-slot">Motokáry racing Birel (závodní licence)</div>
            <div class="price">280 Kč <br></div>
        </div>
    </div>

    <h3 class="draha_heading">Ceník firemních a soukromých akcí / cena za 1 hodinu</h3>
    <div class="draha">
        <div class="rental-option">
            <div class="time-slot">PO - PÁ OD 14:00 DO 23:00 HOD.</div>
            <div class="price">16 000 Kč <br> <span>bez DPH *</span></div>
        </div>

        <div class="rental-option">
            <div class="time-slot">SO - NE A SVÁTKY OD 14:00 DO 23:00 HOD.</div>
            <div class="price">20 000 Kč <br> <span>bez DPH *</span></div>
        </div>
    </section>

    <?php include './php/structure/footer.php'; ?>
</body>
</html>