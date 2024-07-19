<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="photo&icons/Logo w_ bg.png">
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
                    <button id="applicant-login" class="applicant-button" >EXISTING APPLICANT</button>
                    <button id="admin-button" class="admin-button">ADMIN</button>                   
                </div>
                <div class="new-applicant">
                    <button id="applicant-button" class="applicant-button" >NEW APPLICANT</button> 
                </div>
            </div>
        </div>
    </div>

    <div class="popup">
        <div class="popup-content">
            <div class="close-button">
                <a href="#" class="close">&times;</a>
            </div>
            <div class="fresh">
                <h1>Are you a fresh graduate?</h1>
                <div>
                    <button id="yes" class="yes">YES</button>
                    <button id="no" class="no">NO</button>
                </div>
            </div>         
        </div>
    </div>
    <script>
    document.getElementById("applicant-button").addEventListener("click", function(){
        document.querySelector(".popup").style.display = "flex";
    });

    document.querySelector(".close").addEventListener("click", function(){
        document.querySelector(".popup").style.display = "none";
    });

    document.getElementById('admin-button').onclick = function() {
        window.location.href = 'adminlogin.php';
    };

    document.getElementById('applicant-login').onclick = function() {
        window.location.href = 'applicantlogin.php';
    };

    document.getElementById('yes').onclick = function() {
        window.location.href = 'freshgrad.php';
    };

    document.getElementById('no').onclick = function() {
        window.location.href = 'applicationform.php';
    };

</script>
   
</body>
</html>