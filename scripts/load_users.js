// Tento soubor obsahuje kód pro načtení uživatelů ze souboru users.json a jejich zobrazení v tabulce.
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
            
            // Create table row element
            const tr = document.createElement("tr");
            tr.dataset.userId = user.id; // Store user ID in data attribute
    
            // Apply red font for admin users
            if (user.role === 'admin') {
                tr.classList.add('admin-user');
            }
    
            // Create individual table cell elements and append them to the row
            const tdId = document.createElement("td");
            tdId.textContent = user.id;
    
            const tdEmail = document.createElement("td");
            tdEmail.textContent = user.email;
    
            const tdRole = document.createElement("td");
            tdRole.textContent = user.role;
    
            const tdFirstname = document.createElement("td");
            tdFirstname.textContent = user.firstname;
    
            const tdLastname = document.createElement("td");
            tdLastname.textContent = user.lastname;
    
            const tdPhone = document.createElement("td");
            tdPhone.textContent = user.phone;
    
            // Append each cell to the row
            tr.appendChild(tdId);
            tr.appendChild(tdEmail);
            tr.appendChild(tdRole);
            tr.appendChild(tdFirstname);
            tr.appendChild(tdLastname);
            tr.appendChild(tdPhone);
    
            // Finally, append the row to the table body
            userTable.appendChild(tr);
        }
    
        // Update the loaded user count
        loadedUsersCount = end;
    
        // Hide the load more button if all users are loaded
        if (loadedUsersCount >= users.length) {
            loadMoreButton.style.display = "none";
        } else {
            loadMoreButton.style.display = "block";
        }
    }
    loadMoreButton.addEventListener("click", printUsers);
    fetchUsers();
});