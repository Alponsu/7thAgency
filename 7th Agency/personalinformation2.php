<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/personalinformation2.css">
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
            <h2>Personal Information</h2>
            <form>
                <div class="form-group">
                    <label for="dob">DATE OF BIRTH</label>
                    <input type="date" id="dob" name="dob">
                </div>
                <div class="form-group">
                    <label for="marital-status">MARITAL STATUS</label>
                    <select id="marital-status" name="marital-status">
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="widowed">Widowed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="citizenship">CITIZENSHIP</label>
                    <input type="text" id="citizenship" name="citizenship">
                </div>
                <div class="form-group">
                    <label for="age">AGE</label>
                    <input type="number" id="age" name="age">
                </div>
                <div class="form-group">
                    <label for="sex">SEX</label>
                    <select id="sex" name="sex">
                        <option value="m">Male</option>
                        <option value="f">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="home-phone">HOME PHONE (OPTIONAL)</label>
                    <input type="tel" id="home-phone" name="home-phone">
                </div>
                <div class="form-group">
                    <label for="cellphone">CELLPHONE NO.</label>
                    <input type="tel" id="cellphone" name="cellphone">
                </div>
                <div class="form-group">
                    <label for="email">EMAIL ADDRESS</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="ssn">SOCIAL SECURITY NUMBER</label>
                    <input type="text" id="ssn" name="ssn">
                </div>
            </form>
            <div class="button-div">
                    <button id="previous-button" class="previous-button">Previous</button>
                    <button id="next-button" class="next-button">Next</button>
            </div>
         </div>
    </div>
    <script>
        document.getElementById('previous-button').onclick = function() {
            window.location.href = 'personalinformation1.php';
        };
        document.getElementById('next-button').onclick = function() {
            window.location.href = 'educationalbackground.php';
        };
    </script> 
</body>
</html>
