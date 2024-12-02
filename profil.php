<?php
session_start();

// Check if the user is logged in
if (empty($_SESSION['role'])) {
    header('Location: prihlaseni.php');
}

// Load user data from JSON
$usersFile = './user_data/users.json';
$users = json_decode(file_get_contents($usersFile), true);

$userData = null;
if (is_array($users)) {
    foreach ($users as $user) {
        if ($user['email'] === $_SESSION['email']) {
            $userData = $user;
            break;
        }
    }
}

if ($userData === null) {
    echo "User data not found!";
}

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userData['firstname'] = $_POST['firstname'] ?? $userData['firstname'];
    $userData['lastname'] = $_POST['lastname'] ?? $userData['lastname'];
    $userData['email'] = $_POST['email'] ?? $userData['email'];
    $userData['phone'] = $_POST['phone'] ?? $userData['phone'];
    $userData['profile_picture'] = $_POST['profile_picture'] ?? $userData['profile_picture'];

    // Update the user data in the JSON file
    foreach ($users as &$user) {
        if ($user['email'] === $_SESSION['email']) {
            $user = $userData;
            break;
        }
    }
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

    // Redirect to profile page to show updated data
}

// Handle AJAX request to delete user
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    $emailToDelete = $input['email'] ?? null;

    if ($emailToDelete) {
        $users = array_filter($users, function($user) use ($emailToDelete) {
            return $user['email'] !== $emailToDelete;
        });
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not provided']);
    }
    exit();
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

    <?php if ($_SESSION['role'] !== 'admin'): ?>
        <!-- Regular user view -->
        <form method="post" enctype="multipart/form-data">
            <label for="firstname">Jméno:</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($userData['firstname']); ?>"><br>

            <label for="lastname">Příjmení:</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($userData['lastname']); ?>"><br>

            <label for="phone">Telefonní číslo:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>"><br>

            <label for="profile_picture">Profilový obrázek:</label>
            <input type="file" name="profile_picture" id="profile_picture"><br>

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
    </div>
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

    function loadUsers() {
        const fragment = document.createDocumentFragment();
        const end = Math.min(loadedUsersCount + usersPerPage, users.length);

        for (let i = loadedUsersCount; i < end; i++) {
            const user = users[i];
            const li = document.createElement("li");
            li.textContent = `${user.email} (${user.role})`;

            // Apply red font for admin users
            if (user.role === 'admin') {
                li.classList.add('admin-user');
            }

            // Add delete button
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.addEventListener("click", () => deleteUser(user.email));
            li.appendChild(deleteButton);

            // Add admin button
            const adminButton = document.createElement("button");
            adminButton.textContent = "Add Admin";
            adminButton.addEventListener("click", () => {
                if (confirm("Are you sure to add admin rights to this user?")) {
                    addAdmin(user.email);
                }
            });
            li.appendChild(adminButton);

            fragment.appendChild(li);
        }

        userList.appendChild(fragment);
        loadedUsersCount = end;

        if (loadedUsersCount >= users.length) {
            loadMoreButton.style.display = "none";
        } else {
            loadMoreButton.style.display = "block";
        }
    }

    function deleteUser(email) {
        fetch('./delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to delete user: " + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                users = users.filter(user => user.email !== email);
                userList.innerHTML = '';
                loadedUsersCount = 0;
                loadUsers();
            } else {
                console.error("Error deleting user:", data.message);
            }
        })
        .catch(error => {
            console.error("Error deleting user:", error);
        });
    }

    function addAdmin(email) {
        fetch('./add_admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to add admin: " + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                users = users.map(user => {
                    if (user.email === email) {
                        return { ...user, role: 'admin' };
                    }
                    return user;
                });
                userList.innerHTML = '';
                loadedUsersCount = 0;
                loadUsers();
            } else {
                console.error("Error adding admin:", data.message);
            }
        })
        .catch(error => {
            console.error("Error adding admin:", error);
        });
    }

    loadMoreButton.addEventListener("click", loadUsers);
    fetchUsers();
});
</script>


</body>
</html>