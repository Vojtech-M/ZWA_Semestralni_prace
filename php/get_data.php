<?php
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
function saveDataToJsonFile($filePath, $data) {
    // Načtení existujících dat ze souboru JSON (pokud existuje)
    if (file_exists($filePath)) {
        $jsonData = file_get_contents($filePath);
        $jsonArray = json_decode($jsonData, true);
    } else {
        $jsonArray = [];
    }

    // Přidání nových dat do pole
    $jsonArray[] = $data;

    // Převod pole zpět na JSON a uložení do souboru
    file_put_contents($filePath, json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>