<?php
/*  
 Job: Reservation
    This file contains a form for user reservation. It checks if the reservation exists in the database and if the date is correct.
    The user can reserve a time slot for a certain number of people. The reservation is saved in a JSON file.
    The user can also delete and edit their reservations. Admins can delete any reservation.
    The reservations are displayed in a table with pagination. Admins can edit and delete reservations.
*/
include "./php/check_login.php";
include "./php/data_handler.php";
include "./php/reservation_validation.php";

if (!isset($_SESSION['id'])) {
    header("Location: ./login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <?php
    include "./php/structure/head.html";
    ?>
    <link rel="stylesheet" href="./css/reservations.css">
</head>
<body>
<?php include './php/structure/header.php'; ?> 

<div class ="formular">
    <form action="reservation.php" method="post">
        <div id="name">
            <label class="required_label" for="reservation_date">Datum rezervace</label>
            <input type="date" id="reservation_date" name="reservation_date" max='2030-01-01'  value="<?php echo isset($_POST['reservation_date']) ? htmlspecialchars($_POST['reservation_date']) : ''; ?>" required>
        
            <label for="timeslot" class="required_label" >Čas rezervace</label>
            <select name="timeslot" id="timeslot">
                <option value="14" <?php echo (isset($_POST['timeslot']) && $_POST['timeslot'] == '14') ? 'selected' : ''; ?>>14:00 - 15:00</option>
                <option value="15" <?php echo (isset($_POST['timeslot']) && $_POST['timeslot'] == '15') ? 'selected' : ''; ?>>15:00 - 16:00</option>
                <option value="16" <?php echo (isset($_POST['timeslot']) && $_POST['timeslot'] == '16') ? 'selected' : ''; ?>>16:00 - 17:00</option>
                <option value="17" <?php echo (isset($_POST['timeslot']) && $_POST['timeslot'] == '17') ? 'selected' : ''; ?>>17:00 - 18:00</option>
                <option value="18" <?php echo (isset($_POST['timeslot']) && $_POST['timeslot'] == '18') ? 'selected' : ''; ?>>18:00 - 19:00</option>
                <option value="19" <?php echo (isset($_POST['timeslot']) && $_POST['timeslot'] == '19') ? 'selected' : ''; ?>>19:00 - 20:00</option>
                <option value="20" <?php echo (isset($_POST['timeslot']) && $_POST['timeslot'] == '20') ? 'selected' : ''; ?>>20:00 - 21:00</option>
                <option value="21" <?php echo (isset($_POST['timeslot']) && $_POST['timeslot'] == '21') ? 'selected' : ''; ?>>21:00 - 22:00</option>
                <option value="22" <?php echo (isset($_POST['timeslot']) && $_POST['timeslot'] == '22') ? 'selected' : ''; ?>>22:00 - 23:00</option>
            </select>
        
            <label class="required_label" for="quantity">Počet lidí:</label>
            <input type="number" id="quantity"  name="quantity" min="1" max="50" tabindex="3" value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>" required>
        </div>
        <button class=""  id="reg_submit" type="submit" name="action" value="reserve"   tabindex="4">Rezervovat</button>
        
        <h5>Pole označené <span class="red_text">*</span> jsou povinná</h5>
        <h5>Rezervaci je možné vytvořit maximálně pro 50 lidí</h5>
        <h4>Cena rezervace dle: <a href="price_list.php">Ceník</a></h4>
    </form>
</div>

<?php
$file = './user_data/reservations.json';
$reservation_result = "";

// Check if the form was submitted
include "./php/reservation_form.php";
include "./php/reservation_table.php";

$userReservations = getUserReservations($_SESSION['id']);
?>
<article>
    <h2>Moje rezervace</h2>
    <?php 
    // Sort the reservations by date
    if (!empty($userReservations)) {
        usort($userReservations, function($a, $b) {
            $dateA = DateTime::createFromFormat('d.m.Y', $a['date']);
            $dateB = DateTime::createFromFormat('d.m.Y', $b['date']);
    
            if ($dateA == $dateB) {
                return $a['timeslot'] - $b['timeslot'];
            }
            return $dateA <=> $dateB;
        });
    }
    ?>

    <?php if (!empty($userReservations)): ?>
        <ul>
        <?php foreach ($userReservations as $reservation): ?>
            <li>
                <?php 
                $timeslot = $reservation['timeslot'];
                $timeslot1 = $timeslot . ":00";
                $timeslot2 = $timeslot + 1 . ":00";
                ?>
                Datum: <?php echo htmlspecialchars($reservation['date']); ?>,
                Čas: <?php echo htmlspecialchars("$timeslot1 - $timeslot2"); ?>,
                Počet lidí: <?php echo htmlspecialchars($reservation['quantity']); ?>
                <form action="reservation.php" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($reservation['id']); ?>">
                    <button type="submit" name="action" value="delete" class="remove_reservations user_managment_button">Smazat</button>
                </form>
                <?php include './php/edit_reservation_form.php'; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nemáte žádné rezervace.</p>
    <?php endif; ?>

    <div class="reservation_link">
        <a href="profil.php">Zpět na profil</a> 
    </div>
</article>

<?php include './php/structure/footer.php'; ?>
<script src="./scripts/reservation.js" type=module> </script>
</body>
</html>
