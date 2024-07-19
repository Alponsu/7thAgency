<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="photo&icons/Logo w_ bg.png">
    <link rel="stylesheet" href="styles/adminlogin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <title>7th Agency</title>
    <script>
        function showError(message) {
            const errorDiv = document.getElementById('error-message');
            errorDiv.innerText = message;
            errorDiv.style.display = 'block';

            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            emailInput.classList.add('invalid');
            passwordInput.classList.add('invalid');
        }

        function hideError() {
            const errorDiv = document.getElementById('error-message');
            errorDiv.style.display = 'none';

            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            emailInput.classList.remove('invalid');
            passwordInput.classList.remove('invalid');
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                showError('Invalid email or password.');
            }
        }
    </script>
    <style>
        .invalid {
            border-color: red;
        }

        #error-message {
            color: red;
            display: none;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img class="logo" src="photo&icons/Transparent Logo.png" alt="Logo">
            <h1>Login</h1>
            <p>Sign in to continue</p>
            <div id="error-message">Invalid email or password.</div>
            <form action="login.php" method="post" onsubmit="hideError()">
                <label for="email">EMAIL</label>
                <input type="email" id="email" name="email" placeholder="hello@reallygreatsite.com" required>
                <label for="password">PASSWORD</label>
                <input type="password" id="password" name="password" placeholder="******" required>
                <button type="submit">Log in</button>
            </form>
        </div>
    </div>
</body>
</html>
