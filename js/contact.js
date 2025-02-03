const urlBase = "http://scubuddies.xyz/LAMPAPI";
let userId = localStorage.getItem("userId");
let editingContactId = null;

if (!userId) {
    alert("You must be logged in!");
    window.location.href = "index.html";
}

window.onload = () => {
    searchContacts("");
};

document
    .getElementById("searchBtn")
    .addEventListener("click", function (event) {
        event.preventDefault();
        const query = document.getElementById("searchInput").value.trim();
        searchContacts(query);
    });

function searchContacts(query) {
    const payload = {
        search: query,
        userId: parseInt(userId),
    };

    fetch(`${urlBase}/SearchContact.php`, {
        method: "POST", // Using JSON POST
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
    })
        .then((res) => res.json())
        .then((data) => {
            console.log("Search Response:", data);
            if (data.error) {
                alert("Error searching contacts: " + data.error);
            } else {
                displayContacts(data.results || []);
            }
        })
        .catch((err) => console.error("Search fetch error:", err));
}

function displayContacts(contacts) {
    const tbody = document.getElementById("contactsBody");
    tbody.innerHTML = "";

    if (contacts.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6">No contacts found.</td></tr>`;
        return;
    }

    contacts.forEach((contact) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${contact.FirstName}</td>
            <td>${contact.LastName}</td>
            <td>${contact.Email}</td>
            <td>${contact.Phone}</td>
            <td>${contact.FavoriteSpot}</td>
            <td class="table-actions">
                <button onclick='openEditModal(${JSON.stringify(
                    contact
                )})'>Edit</button>
                <button onclick='deleteContact(${contact.ID})'>Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

document
    .getElementById("addContactForm")
    .addEventListener("submit", function (event) {
        event.preventDefault();
        saveContact();
    });

function openAddModal() {
    editingContactId = null;
    clearModalFields();
    document.getElementById("modalTitle").textContent = "Add Contact";
    document.getElementById("contactModal").style.display = "block";
}

function openEditModal(contact) {
    editingContactId = contact.ID; // Means we'll update
    document.getElementById("modalTitle").textContent = "Edit Contact";

    // Fill form fields
    document.getElementById("firstNameInput").value = contact.FirstName;
    document.getElementById("lastNameInput").value = contact.LastName;
    document.getElementById("emailInput").value = contact.Email;
    document.getElementById("phoneInput").value = contact.Phone;
    document.getElementById("favSpotInput").value = contact.FavoriteSpot;

    document.getElementById("contactModal").style.display = "block";
}

function saveContact() {
    const firstName = document.getElementById("firstNameInput").value.trim();
    const lastName = document.getElementById("lastNameInput").value.trim();
    const email = document.getElementById("emailInput").value.trim();
    const phone = document.getElementById("phoneInput").value.trim();
    const favoriteSpot = document.getElementById("favSpotInput").value.trim();

    if (!firstName || !lastName || !email || !phone || !favoriteSpot) {
        alert("All fields are required.");
        return;
    }

    if (!editingContactId) {
        const payload = {
            firstName,
            lastName,
            email,
            phone,
            favoriteSpot,
            userId: parseInt(userId),
        };

        fetch(`${urlBase}/AddContact.php`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.error) {
                    alert("Error adding contact: " + data.error);
                } else {
                    closeModal();
                    searchContacts("");
                }
            })
            .catch((err) => console.error("Add contact fetch error:", err));
    } else {
        const payload = {
            id: editingContactId,
            firstName,
            lastName,
            email,
            phone,
            favoriteSpot,
            userId: parseInt(userId),
        };

        fetch(`${urlBase}/UpdateContact.php`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.error) {
                    alert("Error updating contact: " + data.error);
                } else {
                    closeModal();
                    searchContacts("");
                }
            })
            .catch((err) => console.error("Update contact fetch error:", err));
    }
}

function deleteContact(contactId) {
    if (!confirm("Are you sure you want to delete this contact?")) return;

    const payload = {
        id: contactId,
        userId: parseInt(userId),
    };

    fetch(`${urlBase}/DeleteContact.php`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.error) {
                alert("Error deleting contact: " + data.error);
            } else {
                searchContacts("");
            }
        })
        .catch((err) => console.error("Delete contact fetch error:", err));
}

function doLogout() {
    localStorage.clear();
    window.location.href = "index.html";
}

function closeModal() {
    document.getElementById("contactModal").style.display = "none";
}

function clearModalFields() {
    document.getElementById("firstNameInput").value = "";
    document.getElementById("lastNameInput").value = "";
    document.getElementById("emailInput").value = "";
    document.getElementById("phoneInput").value = "";
    document.getElementById("favSpotInput").value = "";
}
