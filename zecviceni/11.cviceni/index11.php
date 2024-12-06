<?php
/*
// Cesta k souboru s uživateli
$filePath = 'users.json';
// Načtení obsahu souboru
$jsonData = file_get_contents($filePath);
// Kontrola, zda se soubor načetl
if ($jsonData === false) {
    die("Nepodařilo se načíst soubor $filePath.");
}
// Převod JSON dat na PHP pole (asociativní)
$users = json_decode($jsonData, true);
// Kontrola, zda se JSON dekódoval
if ($users === null) {
    die("Nepodařilo se dekódovat JSON data.");
}
*/
require 'lib.php';

// print_r(getUser("ckr"));

// Stránkování
$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$users = listUsers($limit, $offset);
$totalUsers = count(listUsers());
$totalPages = ceil($totalUsers / $limit);

// CRUD operace
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $avatar = $_POST['avatar'];
        addUser($name, $email, $avatar);
    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $avatar = $_POST['avatar'];
        editUser($id, $name, $email, $avatar);
    } elseif ($action === 'delete') {
        $id = $_POST['id'];
        deleteUser($id);
    }


    // Redirect pro zabraneni znovuodeslani - PostRedirectGet
    // header('Location: ' . $_SERVER['PHP_SELF']);
    header('Location: /');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Seznam uživatelů</title>
        <style>
            ul { list-style: none; padding: 0; }
            li { margin-bottom: 10px;}
            label { display: block; margin-bottom: 10px; }
            .pages li { display: inline;}
            .avatar { font-size: 1.5rem; margin-right: 10px; }
        </style>
    </head>
    <body>
        <!-- LIST -->
        <section>
            <h1>Seznam uživatelů</h1>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li>
                        <span class="avatar"><?= htmlspecialchars($user['avatar']) ?></span>
                        <strong><?= htmlspecialchars($user['name']) ?></strong>
                        (ID: <?= htmlspecialchars($user['id']) ?>) -
                        <a href="mailto:<?= htmlspecialchars($user['email']) ?>">
                            <?= htmlspecialchars($user['email']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- CRUD -->
        <section>
            <!-- CREATE -->
            <h2>Přidat nového uživatele</h2>
            <form action="" method="post">
                <label>
                    Jméno:
                    <input type="text" name="name" required>
                </label>
                <label>
                    Email:
                    <input type="email" name="email" required>
                </label>
                <label>
                    Avatar:
                    <input type="text" name="avatar" required>
                </label>
                <button type="submit" name="action" value="add">Přidat</button>
            </form>

            <!-- UPDATE -->
            <h2>Upravit uživatele</h2>
            <form action="" method="post">
                <label>
                    ID uživatele:
                    <input type="text" name="id" required>
                </label>
                <label>
                    Jméno:
                    <input type="text" name="name">
                </label>
                <label>
                    Email:
                    <input type="email" name="email">
                </label>
                <label>
                    Avatar:
                    <input type="text" name="avatar">
                </label>
                <button type="submit" name="action" value="update">Upravit</button>
            </form>

            <!-- DELETE -->
            <h2>Smazat uživatele</h2>
            <form action="" method="post">
                <label>
                    ID uživatele:
                    <input type="text" name="id" required>
                </label>
                <button type="submit" name="action" value="delete">Smazat</button>
            </form>
        </section>

        <!-- PAGINATION -->
        <section class="pages">
            <p>Stránky:</p>
            <ul>
                <?php if ($page > 1): ?>
                    <li><a href="?page=<?= $page - 1 ?>">Předchozí stránka</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li>
                        <a href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li><a href="?page=<?= $page + 1 ?>">Další stránka</a></li>
                <?php endif; ?>
            </ul>
        </section>
    </body>
</html>