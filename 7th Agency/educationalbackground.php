<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles/educationalbackground.css">
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
        <h2>Educational Background</h2>
        <form>
            <div id="schoolDetailsContainer">
                <div class="school-details">
                    <div class="form-group">
                        <label for="school-name-1">SCHOOL NAME</label>
                        <input type="text" id="school-name-1" name="school-name" placeholder="SCHOOL NAME">
                    </div>
                    <div class="form-group">
                        <label for="location-1">LOCATION</label>
                        <input type="text" id="location-1" name="location" placeholder="LOCATION">
                    </div>
                    <div class="form-group">
                        <label for="years-attended-1">YEARS ATTENDED</label>
                        <input type="text" id="years-attended-1" name="years-attended" placeholder="YEARS ATTENDED">
                    </div>
                    <div class="form-group">
                        <label for="degree-received-1">DEGREE RECEIVED</label>
                        <input type="text" id="degree-received-1" name="degree-received" placeholder="DEGREE RECEIVED">
                    </div>
                    <div class="form-group">
                        <label for="major-1">MAJOR</label>
                        <input type="text" id="major-1" name="major" placeholder="MAJOR">
                    </div>
                </div>
            </div>
        </form>
        <div class="button-container">
                <button type="button" onclick="addSchoolDetails()">Add School</button>
                <button type="button" onclick="removeSchoolDetails()">Remove School</button>
            </div>
            <div class="button-div">
                <button type="button" id="previous-button" class="previous-button">Previous</button>
                <button type="button" id="next-button" class="next-button">Next</button>
            </div>
    </div>
</div>
<script src="script/educationalbackground.js"></script>
</body>
</html>
