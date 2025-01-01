<?php
/**
 * Job: Display the price list
 * This file contains a list of prices for renting go-karts and organizing private events.
 * 
 * User can redirect to the reservation page by clicking on the reservation button.
 */

require "./php/check_login.php";
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <?php include "./php/structure/head.html"; ?>
    <link rel="stylesheet" href="./css/price_list.css">
</head>

<body>
<?php include './php/structure/header.php'; ?> 
    <section>
    <h3 class="draha_heading">Pronájmy motokár / cena za 10 min jízdy</h3>
    <div class="draha">
        <div class="rental-option">
            <div class="time-slot">Motokáry sport birel</div>
            <div class="price">250 Kč</div>
        </div>
        
        <div class="rental-option">
            <div class="time-slot">Motokáry racing Birel (závodní licence)</div>
            <div class="price">280 Kč</div>
        </div>
    </div>

    <h3 class="draha_heading">Ceník firemních a soukromých akcí / cena za 1 hodinu</h3>
    <div class="draha">
        <div class="rental-option">
            <div class="time-slot">PO - PÁ OD 14:00 DO 23:00 HOD.</div>
            <div class="price">16 000 Kč <span>bez DPH *</span></div>
        </div>

        <div class="rental-option">
            <div class="time-slot">SO - NE A SVÁTKY OD 14:00 DO 23:00 HOD.</div>
            <div class="price">20 000 Kč <span>bez DPH *</span></div>
        </div>
    </div>
    </section>

    <div class="center-container">
    <div class="reservation_link">
        <?php if (isset($_SESSION['id'])) { ?> 
            <a href="reservation.php">REZERVACE</a>       
        <?php } else { ?>
            <a href="login.php">REZERVACE</a>
        <?php } ?>
    </div>
</div>
    <?php include './php/structure/footer.php'; ?>
</body>
</html>