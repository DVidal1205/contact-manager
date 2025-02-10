const urlBase = "http://scubuddies.xyz/LAMPAPI";
let userId = localStorage.getItem("userId");
let editingContactId = null;

if (!userId) {
    alert("You must be logged in!");
    window.location.href = "index.html";
}

/*
window.onload = () => {
    searchContacts("");
};
*/

document.getElementById("searchInput").addEventListener("input", function (e) {
    searchContacts(e.target.value.trim());
});

function searchContacts(query) {
    const payload = {
        search: query,
        userId: parseInt(userId),
    };

    fetch(`${urlBase}/SearchContact.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.error) {
                alert("Error searching contacts: " + data.error);
            } else {
                displayContacts(data.results || []);
            }
        })
        .catch((err) => console.error(err));
}

function displayContacts(contacts) {
    const tbody = document.getElementById("contactsBody");
    tbody.innerHTML = "";

    if (contacts.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6">No contacts found.</td></tr>`;
        return;
    }

    contacts.forEach((c) => {
        const row = document.createElement("tr");
        row.innerHTML = `
      <td>${c.FirstName}</td>
      <td>${c.LastName}</td>
      <td>${c.Email}</td>
      <td>${c.Phone}</td>
      <td>${c.FavoriteSpot}</td>
      <td class="table-actions">
        <button onclick='openEditModal(${JSON.stringify(c)})'>Edit</button>
        <button onclick='deleteContact(${c.ID})'>Delete</button>
      </td>
    `;
        tbody.appendChild(row);
    });
}

function openAddModal() {
    editingContactId = null;
    clearModalFields();
    document.getElementById("modalTitle").textContent = "Add Contact";
    document.getElementById("contactModal").style.display = "block";
}

function openEditModal(contact) {
    editingContactId = contact.ID;
    document.getElementById("modalTitle").textContent = "Edit Contact";

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

    if (!isValidEmail(email)) {
        //alert("Please enter a valid email.");
        let msg = document.getElementById("email-alert");
        msg.classList.toggle("hideAlert");
        return;
    }

    if (!isValidPhone(phone)) {
        //alert("Please enter a valid phone number (7-15 digits).");
        let msg = document.getElementById("pNumber-alert");
        msg.classList.toggle("hideAlert");
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
            headers: { "Content-Type": "application/json" },
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
            .catch((err) => console.error(err));
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
            headers: { "Content-Type": "application/json" },
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
            .catch((err) => console.error(err));
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
        headers: { "Content-Type": "application/json" },
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
        .catch((err) => console.error(err));
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

function isValidEmail(email) {
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}

function isValidPhone(phone) {
    const pattern = /^\+?\d{7,15}$/;
    return pattern.test(phone);
}
