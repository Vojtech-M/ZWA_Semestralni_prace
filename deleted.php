<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: prihlaseni.php'); // Redirect to login if not logged in
    exit();
}

// Load user data from JSON
$usersFile = './user_data/users.json';
$users = json_decode(file_get_contents($usersFile), true);

$userData = null;
if (is_array($users)) {
    foreach ($users as $key => $user) {
        if ($user['email'] === $_SESSION['email']) {
            $userData = $user;
            $userIndex = $key; // Store the index for updating
            break;
        }
    }
}

if ($userData === null) {
    echo "User data not found!";
    exit();
}

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userData['firstname'] = $_POST['firstname'] ?? $userData['firstname'];
    $userData['lastname'] = $_POST['lastname'] ?? $userData['lastname'];
    $userData['email'] = $_POST['email'] ?? $userData['email'];
    $userData['phone'] = $_POST['phone'] ?? $userData['phone'];
    $userData['profile_picture'] = $_POST['profile_picture'] ?? $userData['profile_picture'];

    // Update the user data in the JSON file
    $users[$userIndex] = $userData;
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

    // Redirect to profile page to show updated data
    header('Location: profile.php');
    exit();
}


function deleteUser($userId)
{

    $usersFile = './user_data/users.json';
    $users = json_decode(file_get_contents($usersFile), true);

    foreach ($users as $key => $user) {
        if ($user['id'] == $userId) {
            unset($users[$key]);
            break;
        }
    }

    // Re-index the array to ensure there are no gaps
    $users = array_values($users);
    $newJson = json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents($filePath, $newJson);

    echo "User with ID $userId has been deleted.";
    header("Location: profil.php");
}





?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Author" content="Vojtěch Michal">
    <meta name="Keywords" content="motokáry">
    <meta name="description" content="Nejzábavnější motokárová dráha ve středních Čechách.">
    <title>Motokárové centrum Benešov - Profil</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="icon" id="favicon" type="image/png" href="./img/helma.png"> 
</head>
<body>

<?php include './php/structure/header.php'; ?>

<article>
    <div class="left-text">
        <h1>Profil uživatele</h1>
        <p>Jméno: <?php echo htmlspecialchars($userData['firstname']); ?></p>
        <p>Příjmení: <?php echo htmlspecialchars($userData['lastname']); ?></p>
        <p>Email: <?php echo htmlspecialchars($userData['email']); ?></p>
        <p>Telefonní číslo: <?php echo htmlspecialchars($userData['phone']); ?></p>
    </div>
    <div class="right-text">
        <img src="<?php echo htmlspecialchars($userData['profile_picture']); ?>" width="500" alt="Profilový obrázek">
    </div>

    <?php if ($_SESSION['email'] !== 'admin@admin.cz'): ?>
        <!-- Regular user view -->
        <form method="post">
            <label for="firstname">Jméno:</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($userData['firstname']); ?>"><br>

            <label for="lastname">Příjmení:</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($userData['lastname']); ?>"><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($userData['email']); ?>"><br>

            <label for="phone">Telefonní číslo:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>"><br>

            <label for="profile_picture">Profilový obrázek (URL):</label>
            <input type="text" name="profile_picture" id="profile_picture" value="<?php echo htmlspecialchars($userData['profile_picture']); ?>"><br>

            <input type="submit" value="Uložit změny">
        </form>
    </article>
    <?php else: ?>
        <!-- Admin view -->
</article>
<article>
    <div>
        <h2>Seznam uživatelů</h2>
        <ul id="userList"></ul>
        <button id="loadMore">Načíst více uživatelů</button>
        <button id="AddUser">Přidatt uživatele</button>
    </div>
   
</article>
<article>
<div class="reservation_link">
<a href="rezervace.php">Správa rezervací</a> 
    </div>
    </article>
    <?php endif; ?>

<?php include './php/structure/footer.php'; ?>

<script>
   document.addEventListener("DOMContentLoaded", function () {
    const userList = document.getElementById("userList");
    const loadMoreButton = document.getElementById("loadMore");

    let users = []; // Array to hold all user data
    let loadedUsersCount = 0; // Counter for users already loaded
    const usersPerPage = 5; // Number of users to load per click

    // Load users with AJAX
    function fetchUsers() {
        fetch('./user_data/users.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error("Failed to fetch: " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data)) {
                    throw new Error("The fetched data is not an array.");
                }
                users = data; // Store all user data
                loadUsers(); // Load the first batch of users
            })
            .catch(error => {
                console.error("Error loading user data:", error);
                userList.innerHTML = `<li>Error: ${error.message}</li>`;
            });
    }

    // Load the next batch of users
    function loadUsers() {
        const nextUsers = users.slice(loadedUsersCount, loadedUsersCount + usersPerPage);
        nextUsers.forEach(user => {
            const row = document.createElement("tr");

            // Create and append cells for user data
            row.innerHTML = `
                <td>${user.firstname}</td>
                <td>${user.lastname}</td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td><img src="${user.profile_picture}" width="50" height="50" alt="Profile"></td>
                <td><form action=\"editForm.php\" method=\"get\"><input type=\"hidden\" name=\"edit\"\" style=\"text-decoration: none\" /><input type=\"submit\" value=\"Edit\" /></form></td>
                <td><form action=\"editForm.php\" method=\"get\"><input type=\"hidden\" name=\"remove\"\" style=\"text-decoration: none\" /><input type=\"submit\" value=\"Remove\" /></form></td>
                <td><form action=
                 <td>
                <form action="change_rights_user.php" method="post">
                    <select name="subject" onchange="this.form.submit()">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </form>
                 <td>
                    <form action="delete_user.php" method="post">
                        <input type="hidden" name="user_id" value="${user.id}">
                        <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                    </form>
                </td>
            </td>
            `;
            userList.appendChild(row);
        });
        loadedUsersCount += nextUsers.length;

        // Hide the button if all users are loaded
       //if (loadedUsersCount >= users.length) {
        //    loadMoreButton.style.display = "none";
       // }
    }

    // Initialize
    fetchUsers();

    // Load more users on button click
    loadMoreButton.addEventListener("click", loadUsers);
});

</script>


</body>
</html>
