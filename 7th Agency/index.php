<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <title>7th Agency</title>
</head>
<body>
    <div class="content">
        <div class="registration-box">
            <div class="logo-div">
                <img class="logo" src="photo&icons/Transparent Logo.png" >
            </div>
            <div class="register">
                <div class="register-text">
                Already Registered? Log in here.
                </div>
                <div class="button-div">
                    <button id="admin-button" class="admin-button">ADMIN</button>
                    <button id="applicant-button" class="applicant-button" >APPLICANT</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('admin-button').onclick = function() {
            window.location.href = 'adminlogin.php';
        };

        document.getElementById('applicant-button').onclick = function() {
            window.location.href = 'personalinformation1.php';
        };
    </script>    
</body>
</html>