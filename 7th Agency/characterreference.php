<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles/characterreference.css">
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
        <h2>Character Reference</h2>
        <form>
            <div id="characterDetailsContainer">
                <div class="character-details">
                    <div class="form-group">
                        <label for="character-name-1">NAME</label>
                        <input type="text" id="character-name-1" name="character-name" placeholder="NAME">
                    </div>
                    <div class="form-group">
                        <label for="location-1">TITLE</label>
                        <input type="text" id="location-1" name="location" placeholder="LOCATION">
                    </div>
                    <div class="form-group">
                        <label for="company-1">COMPANY</label>
                        <input type="text" id="company-1" name="company" placeholder="COMPANY">
                    </div>
                    <div class="form-group">
                        <label for="phone-1">PHONE</label>
                        <input type="text" id="phone-1" name="phone" placeholder="PHONE">
                    </div>
                </div>
            </div>
        </form>
        <div class="button-container">
                <button type="button" onclick="addCharacter()">Add Character Reference</button>
                <button type="button" onclick="removeCharacter()">Remove Character Reference</button>
            </div>
            <div class="button-div">
                <button type="button" id="previous-button" class="previous-button">Previous</button>
                <button type="button" id="next-button" class="next-button">Next</button>
            </div>
    </div>
</div>
<script src="script/characterreference.js"></script>
</body>
</html>
