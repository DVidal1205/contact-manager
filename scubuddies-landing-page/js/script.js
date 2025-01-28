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
    fetch('LAMPAPILogin.php', {
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
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});