<?php
// Database connection parameters
$db_servername = "localhost";
$db_username = "root";
$db_password = "Admin_123";
$db_name = "project";

// Initialize variables
$login_error = '';
$loggedin = false;
$fullName = '';
$application_info = [];

// Handle form submission for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Create connection
    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the email and applicantID from the form
    $email = $_POST['email'];
    $applicantID = $_POST['applicantID']; // Assuming applicantID is entered as the password

    // Prepare and bind parameters
    $stmt = $conn->prepare("SELECT applicantID, fullName FROM applicant_profile WHERE emailAddress = ? AND applicantID = ?");
    $stmt->bind_param("ss", $email, $applicantID);

    // Execute the statement
    $stmt->execute();

    // Store the result
    $result = $stmt->get_result();

    // Check if a row is found
    if ($result->num_rows > 0) {
        // Login successful, fetch fullName
        $row = $result->fetch_assoc();
        $fullName = $row['fullName'];

        // Fetch application info
        $applicantID = $row['applicantID'];
        $stmt = $conn->prepare("SELECT desiredPosition, desiredSalary, desiredEmployment, availWDate FROM application_information WHERE applicantID = ?");
        $stmt->bind_param("i", $applicantID);
        $stmt->execute();
        $result = $stmt->get_result();
        $application_info = $result->fetch_all(MYSQLI_ASSOC);

        $loggedin = true;
    } else {
        // Login failed, set error message
        $login_error = "Invalid email or password.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

// Handle form submission for application
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply'])) {
    // Create connection
    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Extract form data
    $applicantID = $_POST['applicantID'];
    $employment = $_POST['employment'];
    $position = $_POST['position'];
    $dateAvailable = $_POST['date-available'];
    $desiredSalary = $_POST['desired-salary'];

    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO application_information (applicantID, desiredEmployment, desiredPosition, availWDate, desiredSalary) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $applicantID, $employment, $position, $dateAvailable, $desiredSalary);

    // Execute the statement
    if ($stmt->execute()) {
        // Insert successful, redirect to thankyou.php with applicantID as parameter
        header("Location: thankyou.php?applicantID=" . urlencode($applicantID));
        exit();
    } else {
        // Insert failed, handle error if needed
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/adminlogin.css">
    <link rel="icon" href="photo&icons/Logo w_ bg.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <title>7th Agency</title>
    <style>
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            text-align: center;
            position: relative;
            color: black;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            color: black;
            cursor: pointer;
        }

        p {
            margin-bottom: 20px;
            font-size: 1rem;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Login</h1>
            <p>Sign in to continue</p>
            <div id="error-message"><?php echo isset($login_error) ? $login_error : ''; ?></div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="login" value="1">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="hello@reallygreatsite.com" required>
                <label for="applicantID">Applicant ID</label>
                <input type="text" id="applicantID" name="applicantID" placeholder="Enter your applicant ID" required>
                <button type="submit">Log in</button>
            </form>
        </div>
    </div>

    <?php if ($loggedin): ?>
    <!-- Welcome Modal -->
    <div id="welcomeModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Welcome, <?php echo $fullName !== '' ? $fullName : 'Applicant'; ?>!</h2>
            <p>Your application details:</p>
            <?php if (!empty($application_info)): ?>
                <?php foreach ($application_info as $info): ?>
                    <p><strong>Desired Position:</strong> <?php echo isset($info['desiredPosition']) ? $info['desiredPosition'] : 'Not available'; ?></p>
                    <p><strong>Desired Salary:</strong> <?php echo isset($info['desiredSalary']) ? $info['desiredSalary'] : 'Not available'; ?></p>
                    <p><strong>Desired Employment:</strong> <?php echo isset($info['desiredEmployment']) ? $info['desiredEmployment'] : 'Not available'; ?></p>
                    <p><strong>Availability Date:</strong> <?php echo isset($info['availWDate']) ? $info['availWDate'] : 'Not available'; ?></p>
                    <!-- Display other application details -->
                    <hr>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No application information found.</p>
            <?php endif; ?>
            <button id="openApplicationModal">Apply for New Position</button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Application Modal -->
    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Application Form</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="apply" value="1">
                <input type="hidden" name="applicantID" value="<?php echo isset($_POST['applicantID']) ? $_POST['applicantID'] : ''; ?>">
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
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var welcomeModal = document.getElementById("welcomeModal");
            var applicationModal = document.getElementById("applicationModal");
            var openApplicationModalBtn = document.getElementById("openApplicationModal");
            var closeButtons = document.querySelectorAll(".close");

            // Close modals when close button is clicked
            closeButtons.forEach(function(closeBtn) {
                closeBtn.onclick = function() {
                    welcomeModal.style.display = "none";
                    applicationModal.style.display = "none";
                };
            });

            // Open application modal when button is clicked
            if (openApplicationModalBtn) {
                openApplicationModalBtn.onclick = function() {
                    applicationModal.style.display = "block";
                    welcomeModal.style.display = "none"; // Close welcome modal if open
                };
            }

            // Close modal if user clicks outside of the modal
            window.onclick = function(event) {
                if (event.target == welcomeModal) {
                    welcomeModal.style.display = "none";
                }
                if (event.target == applicationModal) {
                    applicationModal.style.display = "none";
                }
            };
        });
    </script>
</body>
</html>
