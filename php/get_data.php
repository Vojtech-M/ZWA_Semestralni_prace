<?php
/**
 * 
 * Funkce pro získávání dat z databáze uživatelů a rezervací
 * 
 * 
 */


/**
 * 
 * Načtení uživatelů ze souboru
 * 
 * 
 * @param int $id - ID uživatele
 * @return array - pole uživatelů
 * 
 */
function getDataById($id) {
    $usersFile = './user_data/users.json';
    $users = json_decode(file_get_contents($usersFile), true);

    foreach ($users as $user) {
        if ($user['id'] == $id) {
            return $user;
        }
    }
    return null;
}

/**
 * 
 * Uložení dat do souboru JSON
 * 
 * @param string $filePath - cesta k souboru
 * @param array $data - data k uložení
 * 
 * @return void
 */
function saveDataToJsonFile($filePath, $data) {
    // Načtení existujících dat ze souboru JSON (pokud existuje)
    if (file_exists($filePath)) {
        $jsonData = file_get_contents($filePath);
        $jsonArray = json_decode($jsonData, true);
    } else {
        $jsonArray = [];
    }

    // Přidání nového uživatele do pole
    $jsonArray[] = $data;

    // Převod pole zpět na JSON a uložení do souboru
    file_put_contents($filePath, json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


/**
 * 
 * Funkce pro načtení rezervací z JSON souboru
 * 
 * @return array - pole rezervací
 * 
 */
function loadReservations() {
    $file = './user_data/reservations.json';
    if (file_exists($file)) {
        $jsonData = file_get_contents($file);
        return json_decode($jsonData, true);
    } else {
        return [];
    }
}


/**
 * 
 * Funkce pro načtení uživatelů z JSON souboru
 * 
 * @return array - pole uživatelů
 * 
 */
function getUserReservations($id) {
    $reservations = loadReservations();
    $userReservations = [];
    foreach ($reservations as $reservation) {
        if ($reservation['user_id'] == $id) {
            $userReservations[] = $reservation;
        }
    }
    return $userReservations;
}
?>