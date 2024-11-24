<?php
session_start();

// Check if the user is logged in and is admin
if (!isset($_SESSION['firstname']) || $_SESSION['firstname'] !== 'admin') {
    header('Location: prihlaseni.php'); // Redirect to login if not logged in or not admin
    exit();
}

// Load user data from JSON
$usersFile = './user_data/users.json';
$users = json_decode(file_get_contents($usersFile), true);

// Calculate statistics
$totalUsers = is_array($users) ? count($users) : 0;

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Author" content="Vojtěch Michal">
    <meta name="Keywords" content="motokáry">
    <meta name="description" content="Admin stránka pro správu uživatelů.">
    <title>Motokárové centrum Benešov - Admin</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>

<?php include './php/structure/header.php'; ?>

<article>
    <h1>Admin Dashboard</h1>
    <p>Počet uživatelů: <?php echo $totalUsers; ?></p>

    <h2>Seznam uživatelů</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Jméno</th>
                <th>Příjmení</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($users)) {
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($user['firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</article>

<?php include './php/structure/footer.php'; ?>

</body>
</html>
