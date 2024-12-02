<?php
ob_start();

function deleteUser($userId)
{
    $filePath = '../json/users.json';  // Adjust path if needed

    $json = file_get_contents($filePath);
    $users = json_decode($json, true);

    // Loop through users and find the user with the provided ID
    foreach ($users as $key => $user) {
        if ($user['id'] == $userId) {
            unset($users[$key]);  // Remove user from the array
            break;
        }
    }

    // Re-index the array to ensure no gaps in the array
    $users = array_values($users);

    // Convert the updated user list back to JSON
    $newJson = json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // Save the updated data back to the JSON file
    file_put_contents($filePath, $newJson);

    echo "User with ID $userId has been deleted.";
    header("Location: ../profil.php"); // Redirect to profile page or the user list
}

if (isset($_GET['id'])) {
    deleteUser($_GET['id']);  // Call function to delete the user
} else {
    echo "No user ID provided.";
}

ob_end_flush();
?>
