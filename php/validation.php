<?php

function validateInputs($firstname, $lastname, $email, $phone, $password, $password2) {
    $errors = [];
    $errors["firstname"] = validateName($firstname);
    $errors["lastname"] = validateName($lastname);
    $errors["email"] = validateEmail($email);
    $errors["phone"] = validatePhone($phone);
    $errors["password"] = validatePassword($password, $password2);
    return $errors;
}

/*
* Job: Validate user input
* This file contains functions for validating user input.
*/
function validateName($name, $minLength = 3, $maxLength = 50) {
    // Check length
    if (strlen($name) < $minLength || strlen($name) > $maxLength) {
        return "Jméno musí být mezi $minLength a $maxLength znaky dlouhé.";
    }
    // Check for only valid characters (letters with no spaces or special characters)
    if (!preg_match("/^[ěščřžýáíéóúůďťňĎŇŤŠČŘŽÝÁÍÉÚŮĚÓa-zA-Z]+$/", $name)) {
        return "Jméno může obsahovat pouze písmena bez mezer nebo speciálních znaků.";
    }
    // If everything is valid, return null or true
    return null;
}

/*
* Validate email
* @param string $email
* @return string|null
*/
function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Neplatný formát e-mailu.";
    }
    return null;
}

/*
* Check if the email is unique
* @param string $email
* @param int|null $currentUserId
* @return bool
*/
function check_email($email, $currentUserId = null) {
    foreach (loadUsers() as $user) {
        if ($user['email'] == $email && $user['id'] != $currentUserId) {
            //array_push($errors, "Uživatel s tímto emailem již existuje!");
            return "Uživatel s tímto emailem již existuje!";
            break;
        }
    }
    return null;
}
/**
 * Validate phone number
 * @param string $phone
 * @return string|null
 */
function validatePhone($phone) {
    // Check for 9 digits (Czech phone number format)   
    if (empty($phone)) {
        return null;
    }
    if (!preg_match("/^\d{9}$/", $phone)) {
        return "Telefonní číslo musí mít 9 číslic.";
    }
    return null;
}

/**
 * 
 * Validate password
 * @param string $password
 * @param string $confirmPassword
 * @return string|null
 * 
 */
function validatePassword($password, $confirmPassword) {
    // Password must be at least 8 characters long
    if (strlen($password) < 8) {
        return "Heslo musí mít alespoň 8 znaků.";
    }
    // Passwords must match
    if ($password !== $confirmPassword) {
        return "Hesla se neshodují.";
    }
    // Check for at least one uppercase letter, one lowercase letter, and one number
    if (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/\d/", $password)) {
        return "Heslo musí obsahovat alespoň jedno velké písmeno, jedno malé písmeno a jedno číslo.";
    }
    return null;
}

function validate_current_password($currentPassword, $userPassword) {
    // Compare the plain-text password with the stored hashed password
    if (!password_verify($currentPassword, $userPassword)) {
        return "Aktuální heslo není správné.";
    }
    return null;
}


/**
 * Check if the ID exists
 * @param int $idToCheck
 * @return bool
 */
function doesIDExist($idToCheck) {
    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['id'] === $idToCheck) {
            return true; // ID found
        }
    }
    return "ID uživatele neexistuje"; // ID not found
}






?>