document.addEventListener("DOMContentLoaded", function () {
    const userTable = document.getElementById("userTableBody"); // Table body for users
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

                // Sort users alphabetically by lastname, then firstname
                users = data.sort((a, b) => {
                    if (a.lastname === b.lastname) {
                        return a.firstname.localeCompare(b.firstname);
                    }
                    return a.lastname.localeCompare(b.lastname);
                });

                printUsers(); // Load the first batch of users
            })
            .catch(error => {
                console.error("Error loading user data:", error);
                userTable.innerHTML = `<tr><td colspan="6">Error: ${error.message}</td></tr>`;
            });
    }

    function printUsers() {
        const end = Math.min(loadedUsersCount + usersPerPage, users.length);

        for (let i = loadedUsersCount; i < end; i++) {
            const user = users[i];
            const tr = document.createElement("tr");
            tr.dataset.userId = user.id; // Store user ID in data attribute

            // Apply red font for admin users
            if (user.role === 'admin') {
                tr.classList.add('admin-user');
            }
            // Create table cells
            tr.innerHTML = `
                <td>${user.id}</td>
                <td>${user.email}</td>
                <td>${user.role}</td>
                <td>${user.firstname}</td>
                <td>${user.lastname}</td>
                <td><button class="table_button delete_user_button">Delete</button></td>
                <td>
            `;
// <button class="table_button edit_user_button">Edit</button>
            // Add delete functionality
            const deleteButton = tr.querySelector(".delete_user_button");
            deleteButton.addEventListener("click", () => deleteUser(user.id, tr));

            userTable.appendChild(tr);
        }
        loadedUsersCount = end;

        if (loadedUsersCount >= users.length) {
            loadMoreButton.style.display = "none";
        } else {
            loadMoreButton.style.display = "block";
        }
    }


    function deleteUser(userId, tableRow) {
        // Create a form element
        const form = document.createElement("form");
        form.method = "post";
        form.action = "";

        // Create an input element for the user ID
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "id";
        input.value = userId;

        // Create an input element for the action
        const actionInput = document.createElement("input");
        actionInput.type = "hidden";
        actionInput.name = "action";
        actionInput.value = "delete";

        // Append the inputs to the form
        form.appendChild(input);
        form.appendChild(actionInput);

        // Append the form to the body and submit it
        document.body.appendChild(form);

        // Remove the table row from the DOM
        tableRow.remove();

        form.submit();
    }

    loadMoreButton.addEventListener("click", printUsers);
    fetchUsers();
});