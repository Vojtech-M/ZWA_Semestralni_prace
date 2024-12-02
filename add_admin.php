<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit();
    }

    $usersFile = './user_data/users.json';
    if (!file_exists($usersFile)) {
        echo json_encode(['success' => false, 'message' => 'User data file not found']);
        exit();
    }

    $users = json_decode(file_get_contents($usersFile), true);
    if ($users === null) {
        echo json_encode(['success' => false, 'message' => 'Error loading user data']);
        exit();
    }

    $userFound = false;
    foreach ($users as &$user) {
        if ($user['email'] === $email) {
            $user['role'] = 'admin';
            $userFound = true;
            break;
        }
    }

    if (!$userFound) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }

    if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving user data']);
    }
}
?>

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

            // Add delete button
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.addEventListener("click", () => deleteUser(user.email));
            li.appendChild(deleteButton);

            // Add admin button
            const adminButton = document.createElement("button");
            adminButton.textContent = "Add Admin";
            adminButton.addEventListener("click", () => addAdmin(user.email));
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
                        user.role = 'admin';
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