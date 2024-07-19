<?php
include_once("databaseconnect.php");

$conn = connection();

// Form data processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = $conn->real_escape_string($_POST['name']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $zip = $conn->real_escape_string($_POST['zip-code']);
    $phone = ($_POST['homephone']);
    $cellphone = $conn->real_escape_string($_POST['cellphone']);
    $email = $conn->real_escape_string($_POST['email']);
    $ssn = $conn->real_escape_string($_POST['ssn']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $age = $conn->real_escape_string($_POST['age']);
    $citizenship = $conn->real_escape_string($_POST['citizenship']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $marital_status = $conn->real_escape_string($_POST['marital-status']);

    // Application Information
    $position = $conn->real_escape_string($_POST['position']);
    $date_available = $conn->real_escape_string($_POST['date-available']);
    $desired_salary = $conn->real_escape_string($_POST['desired-salary']);
    $employment_desired = $conn->real_escape_string($_POST['employment']);

    // Work Experience
    $work_name = $conn->real_escape_string($_POST['work-name']);
    $work_contact = $conn->real_escape_string($_POST['work-contact']);
    $work_address = $conn->real_escape_string($_POST['work-address']);
    $work_date_available = $conn->real_escape_string($_POST['work-date-employed']);
    $work_position = $conn->real_escape_string($_POST['work-position']);
    $work_reason_leaving = $conn->real_escape_string($_POST['work-reason-leaving']);

    // Insert data into applicant_profile
    $stmt = $conn->prepare("INSERT INTO applicant_profile (fullName, address, city, region, zip, homePhone, cellPhone, emailAddress, sssNumber, birthDate, age, citizenship, sex, maritalStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisssssisss", $name, $address, $city, $state, $zip, $phone, $cellphone, $email, $ssn, $birthdate, $age, $citizenship, $gender, $marital_status);

    if ($stmt->execute()) {
        $applicantID = $stmt->insert_id; // Get the last inserted ID for foreign key use

        // Insert data into application_information
        $stmt = $conn->prepare("INSERT INTO application_information (desiredPosition, availWDate, desiredSalary, desiredEmployment, applicantID) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $position, $date_available, $desired_salary, $employment_desired, $applicantID);
        $stmt->execute();

       // Insert data into educational_background and school_details
        $stmt_school = $conn->prepare("INSERT INTO school_details (schoolName, location) VALUES (?, ?) ON DUPLICATE KEY UPDATE schoolID=schoolID");
        $stmt_select_schoolID = $conn->prepare("SELECT schoolID FROM school_details WHERE schoolName = ? AND location = ?");
        $stmt_educ = $conn->prepare("INSERT INTO educational_background (yearsAttended, degree, major, applicantID, schoolID) VALUES (?, ?, ?, ?, ?)");

        foreach ($_POST['school-name'] as $index => $school_name) {
            $school_name = $conn->real_escape_string($school_name);
            $location = $conn->real_escape_string($_POST['location'][$index]);
            $years_attended = $conn->real_escape_string($_POST['years-attended'][$index]);
            $degree_received = $conn->real_escape_string($_POST['degree-received'][$index]);
            $major = $conn->real_escape_string($_POST['major'][$index]);

            // Insert into school_details
            $stmt_school->bind_param("ss", $school_name, $location);
            $stmt_school->execute();
            
            // Select the schoolID
            $stmt_select_schoolID->bind_param("ss", $school_name, $location);
            $stmt_select_schoolID->execute();
            $stmt_select_schoolID->bind_result($schoolID);
            $stmt_select_schoolID->fetch();
            $stmt_select_schoolID->free_result();

            // Insert into educational_background
            $stmt_educ->bind_param("sssis", $years_attended, $degree_received, $major, $applicantID, $schoolID);
            $stmt_educ->execute();
        }

            // Close statements
            $stmt_school->close();
            $stmt_select_schoolID->close();
            $stmt_educ->close();




        // Insert data into character_reference
        $stmt = $conn->prepare("INSERT INTO character_reference (refName, refTitle, refCompany, refPhone, applicantID) VALUES (?, ?, ?, ?, ?)");
        foreach ($_POST['character-name'] as $index => $ref_name) {
            $ref_name = $conn->real_escape_string($ref_name);
            $ref_title = $conn->real_escape_string($_POST['title'][$index]);
            $ref_company = $conn->real_escape_string($_POST['company'][$index]);
            $ref_phone = $conn->real_escape_string($_POST['phone'][$index]);
            $stmt->bind_param("ssssi", $ref_name, $ref_title, $ref_company, $ref_phone, $applicantID);
            $stmt->execute();
        }

        // Redirect to thankyou.php after successful submission
        header("Location: thankyou.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="photo&icons/Logo w_ bg.png">
    <link rel="stylesheet" href="styles/applicationform.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <title>Application Form</title>
</head>
<body>
    <div class="application-container">
        <header>
            <img class="logo" src="photo&icons/Transparent Logo.png" alt="Logo">
            <h1>Application Form</h1>
        </header>
        <form action="" method="post">
            <section class="application-info">
                <h2>Application Information</h2>
                <div class="container">
                    <div class="empdesired-group">
                        <div class="radio-group">
                            <label>EMPLOYMENT DESIRED</label>
                            <input type="radio" id="full-time" name="employment" value="FT" required> Full-Time
                            <input type="radio" id="part-time" name="employment" value="PT" required> Part-Time
                            <input type="radio" id="seasonal" name="employment" value="S" required> Seasonal
                        </div>                
                    </div>
                    <div class="empinfo-group">
                        <div class="form-group">
                            <label for="position">POSITION APPLYING FOR</label>
                            <input type="text" id="position" name="position" required>
                        </div>
                        <div class="form-group">
                            <label for="date-available">DATE AVAILABLE FOR WORK</label>
                            <input type="date" id="date-available" name="date-available" required>
                        </div>
                        <div class="form-group">
                            <label for="desired-salary">DESIRED SALARY</label>
                            <input type="number" id="desired-salary" name="desired-salary" required>
                        </div>
                    </div>
                </div>
                
            </section>
            <section class="applicant-info">
                <h2>Personal Information</h2>
                <div class="container">
                    <div>
                        <div class="form-group">
                            <label for="name">APPLICANT'S NAME [SURNAME, FIRST NAME, MIDDLE NAME]</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="az-div">
                        <div class="form-group">
                            <label for="address">ADDRESS</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <div class="formzip-group">
                            <label for="zip-code">ZIP CODE</label>
                            <input type="text" id="zip-code" name="zip-code" required>
                        </div>
                    </div>
                    <div class="az-div">
                        <div class="form-group">
                            <label for="city">CITY</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="state">REGION</label>
                            <select id="state" name="state" required>
                                <option value="" disabled selected></option>
                                <option value="I">Region I</option>
                                <option value="II">Region II</option>
                                <option value="III">Region III</option>
                                <option value="NCR">NCR</option>
                                <option value="IV-A">Region IV A</option>
                                <option value="IV-B">Region IV B</option>
                                <option value="V">Region V</option>
                                <option value="VI">Region VI</option>
                                <option value="VII">Region VII</option>
                                <option value="VIII">Region VIII</option>
                                <option value="IX">Region IX</option>
                                <option value="X">Region X</option>
                                <option value="XI">Region XI</option>
                                <option value="XII">Region XII</option>
                                <option value="XIII">Region XIII</option>
                                <option value="CAR">CAR</option>
                                <option value="BARMM">BARMM</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ssn">SOCIAL SECURITY NUMBER</label>
                            <input type="text" id="ssn" name="ssn" required>
                        </div>
                    </div>
                    <div class="az-div">
                        <div class="form-group">
                            <label for="birthdate">DATE OF BITH</label>
                            <input type="date" id="birthdate" name="birthdate" required>
                        </div>
                        <div class="form-group">
                            <label for="marital-status">MARITAL STATUS</label>
                            <select id="marital-status" name="marital-status" required>
                                <option value="" disabled selected></option>
                                <option value="S">Single</option>
                                <option value="M">Married</option>
                                <option value="E">Engaged</option>
                                <option value="W">Widowed</option>
                                <option value="SE">Separated</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="citizenship">CITIZENSHIP</label>
                            <input type="text" id="citizenship" name="citizenship" required>
                        </div>
                        <div class="formas-group">
                            <div class="form-group">
                                <label for="age">AGE</label>
                                <input type="number" id="age" name="age" required>
                            </div>                     
                            <div class="form-group">
                                <label for="gender">SEX</label>
                                <select id="gender" name="gender" required>
                                    <option value="" disabled selected></option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                    <option value="I">Intersex</option>
                                </select>
                            </div>
                        </div>           
                    </div>
                    <div class="az-div">
                        <div class="formas-group">
                            <div class="form-group">
                                <label for="homephone">HOME PHONE</label>
                                <input type="tel" id="homephone" name="homephone">
                            </div>
                            <div class="form-group">
                                <label for="cellphone">CELLPHONE</label>
                                <input type="tel" id="cellphone" name="cellphone" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">EMAIL ADDRESS</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="education">
            <h2>Education</h2>
            <div class="container" id="schoolDetailsContainer">
                <div class="az-div school-details">
                    <div class="form-group">
                        <label for="school-name-1">SCHOOL NAME</label>
                        <input type="text" id="school-name-1" name="school-name[]" autocomplete="off" oninput="searchSchool(this)" required>
                        <div id="schoolDropdown-1" class="dropdown"></div>
                    </div>
                    <div class="form-group">
                        <label for="location-1">LOCATION</label>
                        <input type="text" id="location-1" name="location[]" required>
                    </div>
                    <div class="form-group">
                        <label for="years-attended-1">YEARS ATTENDED</label>
                        <input type="text" id="years-attended-1" name="years-attended[]" required>
                    </div>
                    <div class="form-group">
                        <label for="degree-received-1">DEGREE RECEIVED</label>
                        <input type="text" id="degree-received-1" name="degree-received[]">
                    </div>
                    <div class="form-group">
                        <label for="major-1">MAJOR</label>
                        <input type="text" id="major-1" name="major[]">
                    </div>
                </div>
        </div>
        <button type="button" onclick="addSchoolDetails()">Add School</button>
        <button type="button" onclick="removeSchoolDetails()">Remove School</button>
    </section>
                
            <section class="references">
                <h2>Character Reference</h2>
                <div class="container" id="characterDetailsContainer">
                    <div class="az-div character-details">
                        <div class="form-group">
                            <label for="character-name-1">NAME</label>
                            <input type="text" id="character-name-1" name="character-name[]" required>
                        </div>
                        <div class="form-group">
                            <label for="title-1">TITLE</label>
                            <input type="text" id="title-1" name="title[]" required>
                        </div>
                        <div class="form-group">
                            <label for="company-1">COMPANY</label>
                            <input type="text" id="company-1" name="company[]" required>
                        </div>
                        <div class="form-group">
                            <label for="phone-1">PHONE</label>
                            <input type="text" id="phone-1" name="phone[]" required>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addCharacter()">Add Character Reference</button>
                <button type="button" onclick="removeCharacter()">Remove Character Reference</button>
            </section>

            <input type="submit" value="Submit">
        </form>
    </div>
    <script src="script/educationalbackground.js"></script>
    <script src="script/characterreference.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script/dropdown.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var age = document.getElementById('age').value;
            if (parseInt(age) >= 65) {
                alert("Form cannot be submitted for applicants 65 years old and above.");
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
</body>
</html>

