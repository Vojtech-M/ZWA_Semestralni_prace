<div class="form_field">
    <!-- firstname and lastname -->
    <label for="firstname" class="required_label">Jméno</label>
        <input type="text" id="firstname" name="firstname" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*" value="<?php echo htmlspecialchars($firstname); ?>" placeholder="Tomáš">
    <?php if (isset($errors['firstname'])): ?>
        <div class="error" id="firstNameError"><?= htmlspecialchars($errors['firstname']) ?></div>
    <?php endif; ?>
        <div class="error" id="firstNameError"></div>

    <label for="lastname"  class="required_label">Příjmení</label>
        <input type="text" id="lastname" name="lastname" pattern="[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]*" value="<?php echo htmlspecialchars($lastname); ?>" placeholder="Novák">
    <?php if (isset($errors['lastname'])): ?>
        <div class="error"><?= htmlspecialchars($errors['lastname']) ?></div>
    <?php endif; ?>
        <div class="error" id="lastNameError"></div>
</div>

<div class="form_field">
    <!-- email and phone -->
    <label for="email_field" class="required_label">Email</label>
        <input id="email_field" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="example@mail.com">
    <?php if (isset($errors['email'])): ?>
        <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
    <?php endif; ?>
        <div class="error" id="emailError"></div>
        <label for="phone_field" class="phone_label">Telefonní číslo</label>
        <input id="phone_field" type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="606136603" pattern="[0-9]{9}" title="Telefonní číslo musí obsahovat 9 čísel">
    <?php if (isset($errors['phone'])): ?>
        <div class="error"><?= htmlspecialchars($errors['phone']) ?></div>
    <?php endif; ?>                   
        <div class="error" id="phone_fieldError"></div>
        
</div>

<div class="form_field">
    <!-- password -->
    <label for="pass1_field" class="required_label">Heslo</label>
        <input type="password" id="pass1_field" name="password" placeholder="Heslo" required> 
        <img src="./img/icons/closed_eye.png" class="password-toggle" alt="Toggle password visibility" role="button" aria-label="Show password">
        <div class="error" id="pass1Error"></div>
        <label for="pass2_field" class="required_label">Heslo znovu</label>
        <input id="pass2_field" type="password" name="password2" required placeholder="Heslo znovu" >
    <?php if (isset($errors['password'])): ?>
        <div class="error" id="pass2Error"><?= htmlspecialchars($errors['password']) ?></div>
    <?php endif; ?>
        <div class="error" id="pass2Error"></div>
</div>  
    
<div class="form_field">
    <!-- image -->
    <label for="myFile">Profilový obrázek</label>
    <input type="file" id="myFile" name="file" tabindex="8">
</div>
    <?php if (isset($errors['image'])): ?>
        <div class="error"><?= htmlspecialchars($errors['image']) ?></div>
    <?php endif; ?>

    <div class="error">
        <?php echo isset($fileUploadError) ? htmlspecialchars($fileUploadError) : ''; ?>
    </div>
<p>Políčka označená <span class="red_text">*</span> jsou povinná</p>
