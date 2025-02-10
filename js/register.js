let urlbase = "http://scubuddies.xyz";

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
                    alert(data.error);
                } else {
                    //alert("Registration successful!");
                    localStorage.setItem("userId", data.id);
                    localStorage.setItem("firstName", newfirstName);
                    localStorage.setItem("lastName", newlastName);
                    window.location.href = "contact.html";
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });

document.getElementById("password").addEventListener("input", function () {
    const password = this.value;
    const passwordCriteria = document.getElementById("password-criteria");

    if (password.length > 0) {
        passwordCriteria.classList.remove("hidden");
    } else {
        passwordCriteria.classList.add("hidden");
    }

    const upperCase = document.getElementById("uppercase");
    const lowerCase = document.getElementById("lowercase");
    const number = document.getElementById("number");
    const length = document.getElementById("character-length");
    const special = document.getElementById("special-character");

    const lowerCaseLetters = /[a-z]/g;
    const upperCaseLetters = /[A-Z]/g;
    const numbers = /[0-9]/g;
    const specialCharacters = /[!@#$%^&*]/g;

    toggleCriteria(password.match(lowerCaseLetters), lowerCase);
    toggleCriteria(password.match(upperCaseLetters), upperCase);
    toggleCriteria(password.match(numbers), number);
    toggleCriteria(password.match(specialCharacters), special);
    toggleCriteria(password.length >= 8, length);

    const submitButton = document.getElementById("signup-btn");
    if (
        upperCase.classList.contains("valid") &&
        lowerCase.classList.contains("valid") &&
        number.classList.contains("valid") &&
        length.classList.contains("valid") &&
        special.classList.contains("valid")
    ) {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
});

document
    .getElementById("signup-btn")
    .addEventListener("click", function (event) {
        if (this.disabled) {
            event.preventDefault();
            alert("Please make sure all criteria are met before submitting.");
        }
    });

function toggleCriteria(condition, element) {
    if (condition) {
        element.classList.remove("invalid");
        element.classList.add("valid");
    } else {
        element.classList.remove("valid");
        element.classList.add("invalid");
    }
}

function showPassword(){

    let p = document.getElementById("password");

    if(p.type === "password"){
        p.type = "text";
    }
    else{
        p.type = "password";
    }

}