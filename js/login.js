let urlbase = "http://scubuddies.xyz";

document
    .getElementById("loginForm")
    .addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the form data
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        // Create the request payload
        const payload = {
            login: username,
            password: password,
        };

        // Send the AJAX request
        fetch(urlbase + "/LAMPAPI/Login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    // Handle error
                    alert(data.error);
                } else {
                    // Handle success
                    alert("Login successful!");
                    // Redirect or perform other actions
                    window.location.href = "contact.html";
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });

const test = document.getElementsByTagName("form");
console.log("test", test);
document
    .getElementById("registerForm")
    .addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        const newfirstName = document.getElementById("first-name").value;
        const newlastName = document.getElementById("last-name").value;
        const newlogin = document.getElementById("login").value;
        const newpassword = document.getElementById("password").value;

        const payload = {
            firstName: newfirstName,
            lastName: newlastName,
            login: newlogin,
            password: newpassword,
        };

        // Send the AJAX request
        fetch(urlbase + "/LAMPAPI/RegisterUser.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    // Handle error
                    alert(data.error);
                } else {
                    // Handle success
                    alert("Registration successful!");
                    // Redirect or perform other actions
                    window.location.href = "contact.html";
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });
