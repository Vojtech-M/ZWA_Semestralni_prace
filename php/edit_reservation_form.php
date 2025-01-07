<form action="reservation.php" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($reservation['id']); ?>">
    <label>
        Datum:
        <input type="date" name="date" value="<?php 
            $originalDate = $reservation['date'];
            $dateObject = DateTime::createFromFormat('d.m.Y', $originalDate);
            $formattedDate = $dateObject->format('Y-m-d');
            echo htmlspecialchars($formattedDate);?>" required>
    </label>
    <label>
        Čas:
        <select name="timeslot" required>
        <option value="" disabled <?php echo empty($reservation['timeslot']) ? 'selected' : ''; ?>>Select timeslot</option>
        <option value="14" <?php echo ($reservation['timeslot'] == 14 ? "selected" : ""); ?>>14:00 - 15:00</option>
        <option value="15" <?php echo ($reservation['timeslot'] == 15 ? "selected" : ""); ?>>15:00 - 16:00</option>
        <option value="16" <?php echo ($reservation['timeslot'] == 16 ? "selected" : ""); ?>>16:00 - 17:00</option>
        <option value="17" <?php echo ($reservation['timeslot'] == 17 ? "selected" : ""); ?>>17:00 - 18:00</option>
        <option value="18" <?php echo ($reservation['timeslot'] == 18 ? "selected" : ""); ?>>18:00 - 19:00</option>
        <option value="19" <?php echo ($reservation['timeslot'] == 19 ? "selected" : ""); ?>>19:00 - 20:00</option>
        <option value="20" <?php echo ($reservation['timeslot'] == 20 ? "selected" : ""); ?>>20:00 - 21:00</option>
        <option value="21" <?php echo ($reservation['timeslot'] == 21 ? "selected" : ""); ?>>21:00 - 22:00</option>
        <option value="22" <?php echo ($reservation['timeslot'] == 22 ? "selected" : ""); ?>>22:00 - 23:00</option>
    </select>
    </label>
    <label>
        Počet lidí:
        <input type="number" name="quantity" min="1" max="50" value="<?php echo htmlspecialchars($reservation['quantity']); ?>" required>
    </label>
    <button type="submit" name="action" value="edit_reservation">Uložit</button>
</form>