<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/personalinformation1.css">
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
            <h2>Personal Information</h2>
            <form>
                <div class="employment-group">
                    <p class="emp-p">EMPLOYMENT DESIRED</p>
                    <div>
                        <input type="radio" id="full-time" name="emp-desired" value="FULL-TIME">
                        <label for="full-time">FULL TIME</label>
                    </div>
                    <div>
                        <input type="radio" id="part-time" name="emp-desired" value="PART-TIME">
                        <label for="part-time">PART-TIME</label>
                    </div>
                    <div>
                        <input type="radio" id="seasonal" name="emp-desired" value="SEASONAL">
                        <label for="emp-desired">SEASONAL</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="position-apply">POSITION APPLYING FOR</label>
                    <input type="text" id="position-apply" name="position-apply">
                </div>
                <div class="form-group">
                    <label for="date-available">DATE AVAILABLE FOR WORK</label>
                    <input type="text" id="date-available" name="date-available">
                </div>
                <div class="form-group">
                    <label for="desired-salary">DESIRED SALARY</label>
                    <input type="text" id="desired-salary" name="desired-salary">
                </div>
                <div class="form-group">
                    <label for="last-name">LAST NAME</label>
                    <input type="text" id="last-name" name="last-name">
                </div>
                <div class="form-group">
                    <label for="first-name">FIRST NAME</label>
                    <input type="text" id="first-name" name="first-name">
                </div>
                <div class="form-group">
                    <label for="middle-name">MIDDLE NAME (OPTIONAL)</label>
                    <input type="text" id="middle-name" name="middle-name">
                </div>
                <div class="address-group">
                    <label for="address">ADDRESS</label>
                    <input type="text" id="address" name="address">
                </div>
                <div class="form-group">
                    <label for="zip-code">ZIP CODE</label>
                    <input type="text" id="zip-code" name="zip code">
                </div>
                <div class="form-group">
                    <label for="city">CITY</label>
                    <input type="text" id="city" name="city">
                </div>
                <div class="form-group">
                    <label for="state">STATE</label>
                    <input type="text" id="state" name="state">
                </div>
            </form>
            <div class="button-div">
                <button type="button" id="next-button" class="next-button">Next</button>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('next-button').onclick = function() {
            window.location.href = 'personalinformation2.php';
        };

    </script> 
</body>
</html>