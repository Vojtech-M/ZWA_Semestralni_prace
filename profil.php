<?php
include './php/check_login.php';
include "./php/lib.php";

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $user = getDataById($user_id);
}


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
    <title>Motokárové centrum Benešov</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/helma.png"> 
</head>
<body>

<?php include './php/structure/header.php'; ?>

<article>
    <div class="left-text">
        <h1>Profil uživatele</h1>
        <p>Jméno: <?php echo htmlspecialchars($user['firstname']); ?></p>
        <p>Příjmení: <?php echo htmlspecialchars($user['lastname']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Telefonní číslo: <?php echo htmlspecialchars($user['phone']); ?></p>
    </div>
    <div class="right-text">
        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profilový obrázek">
    </div>
</article>

<article>
      
        <h2>Moje rezervace</h2>
        <?php if (!empty($userReservations)): ?>
            <ul>
                <?php foreach ($userReservations as $reservation): ?>
                    <li>
                        Datum: <?php echo htmlspecialchars($reservation['date']); ?>,
                        Čas: <?php echo htmlspecialchars($reservation['time']); ?>,
                        Počet lidí: <?php echo htmlspecialchars($reservation['quantity']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nemáte žádné rezervace.</p>
        <?php endif; ?>
</article>

<article>
        <!-- Regular user view -->
        <form method="post" enctype="multipart/form-data">
            <label for="firstname">Jméno:</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>"><br>
            <?php if (isset($errors['firstname'])): ?>
                <div class="error"><?php echo $errors['firstname']; ?></div>
            <?php endif; ?>

            <label for="lastname">Příjmení:</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>"><br>
            <?php if (isset($errors['lastname'])): ?>
                <div class="error"><?php echo $errors['lastname']; ?></div>
            <?php endif; ?>

            <label for="phone">Telefonní číslo:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br>
            <?php if (isset($errors['phone'])): ?>
                <div class="error"><?php echo $errors['phone']; ?></div>
            <?php endif; ?>

            <label for="profile_picture">Profilový obrázek:</label>
            <input type="file" name="profile_picture" id="profile_picture"><br>
            <?php if (isset($errors['profile_picture'])): ?>
                <div class="error"><?php echo $errors['profile_picture']; ?></div>
            <?php endif; ?>

            <input type="submit" value="Uložit změny">
        </form>
</article>
<?php if ($user["role"] == 'admin'): ?>
        <!-- Admin view -->
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

    <article>
        <!-- CRUD -->
        <section>
            <!-- CREATE -->
            <h2>Přidat nového uživatele</h2>
            <form action="" method="post">
                <label>
                    Jméno:
                    <input type="text" name="firstname" required>
                </label>
                <label>
                    Příjmení:
                    <input type="text" name="lastname" required>
                </label>
                <label>
                    Email:
                    <input type="email" name="email" required>
                </label>
                <label>
                    Telefon:
                    <input type="text" name="phone" required>
                </label>
                <label>
                    Heslo:
                    <input type="text" name="passwd" required>
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
        </article>


    <?php endif; ?>
</article>
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
                users = data; 
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
            li.textContent = `${user.email} (${user.role}) ${user.id}`;

            // Apply red font for admin users
            if (user.role === 'admin') {
                li.classList.add('admin-user');
            }

            // Add delete button
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.addEventListener("click", () => {
                if (confirm("Are you sure to add admin rights to this user?")) {
                    deleteUser(user.email);
                }
            });
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
        fetch('./php/delete_user.php', {
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
        fetch('./php/add_admin.php', {
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

    // function editUser(email){



    // }
</script>
</body>
</html>