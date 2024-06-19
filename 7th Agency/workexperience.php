<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/workexperience.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <title>Application Form</title>
</head>
<body>
    <div class="application-container">
        <div class="header">
            <img class="logo" src="photo&icons/Transparent Logo.png" alt="Logo">
            <div class="login-info">Logged in as: USERNAME_XXXXX ▼</div>
        </div>
        <div class="form-container">
            <h1>Application Form</h1>
            <h2>Work Experience</h2>
            <form>
                <div class="form-group">
                    <label for="employer">EMPLOYER</label>
                    <input type="text" id="employer" name="employer">
                </div>
                <div class="form-group">
                    <label for="contact-number">CONTACT NUMBER</label>
                    <input type="tel" id="contact-number" name="contact-number">
                </div>
                <div class="form-group">
                    <label for="address">ADDRESS</label>
                    <input type="text" id="address" name="address">
                </div>
                <div class="form-group">
                    <label for="date-employed">DATE EMPLOYED</label>
                    <input type="date" id="date-employed" name="date-employed">
                </div>
                <div class="form-group">
                    <label for="position">POSITION</label>
                    <input type="text" id="position" name="position">
                </div>
                <div class="form-group">
                    <label for="reason-for-leaving">REASON FOR LEAVING</label>
                    <input type="text" id="reason-for-leaving" name="reason-for-leaving">
                </div>
            </form>
            <div class="button-div">
                <button type="button" id="previous-button" class="previous-button">Previous</button>
                <button type="button" id="next-button" class="next-button">Next</button>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('next-button').onclick = function() {
            window.location.href = 'characterreference.php';
        };
        document.getElementById('previous-button').onclick = function() {
            window.location.href = 'educationalbackground.php';
        };
    </script> 
</body>
</html>
