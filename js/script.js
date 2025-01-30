let urlbase = 'http://scubuddies.xyz';

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Get the form data
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Create the request payload
    const payload = {
        login: username,
        password: password
    };

    // Send the AJAX request
    fetch('LAMPAPI/Login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            // Handle error
            alert(data.error);
        } else {
            // Handle success
            alert('Login successful!');
            // Redirect or perform other actions
            window.location.href = "contact.html";
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

function register()
{
    let newfirstName = document.getElementById("first-name");
    let newlastName = document.getElementById("last-name");
    let newlogin = document.getElementById("login");
    let newpassword = document.getElementById("password");

    let url = urlbase + "/LAMPAPI/RegisterUser.php";

    let u = {firstname:newfirstName,lastname:newlastName,login:newlogin,password:newpassword};

    let jsonPayload = json.stringify(u);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url,true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById("regResult").innerHTML = "Registered Successfully";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("regResult").innerHTML = "failed";
	}
}