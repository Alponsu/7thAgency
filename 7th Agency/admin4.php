<?php
include_once("databaseconnect.php");

$conn = connection();

// Handle form submission for adding a new school
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $schoolName = $_POST['schoolName'];
    $location = $_POST['location'];

    // Insert the new school into the database
    $stmt = $conn->prepare("INSERT INTO school_details (schoolName, location) VALUES (?, ?)");
    $stmt->bind_param("ss", $schoolName, $location);
    $stmt->execute();
    $stmt->close();

    // Refresh the page to see the new entry
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// SQL query to select data from the table
$sql = "SELECT * FROM school_details";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="photo&icons/Logo w_ bg.png">
    <link rel="stylesheet" href="styles/admin1.css">
    <link rel="stylesheet" href="styles/modal.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <title>7th Agency</title>
    
</head>
<body>
    <div class="applicant-container">
        <div class="header">
            <ul>
                <img class="logo" src="photo&icons/Transparent Logo.png" alt="Logo">
                <li><a href="index.php">Home</a></li>
                <li><a href="admin1.php">AP Profile</a></li>
                <li><a href="admin2.php">AP Information</a></li>
                <li><a href="admin3.php">Educational</a></li>
                <li><ab href="admin4.php">School</ab></li>
                <li><a href="admin5.php">Work Experience</a></li>
                <li><a href="admin6.php">Reference</a></li>
                <li><a href="filter1.php">Filter</a></li>
            </ul>
        </div>
        
        <div class="container">
            <h1>Admin Dashboard</h1>
            <h2>School Details</h2>
            <a href="#modal"><button id="addSchoolBtn">Add School</button></a> 

            <!-- Pop-Up Form -->
            <div id="modal" class="modal">
                <div class="modal-content">
                    <a href="#" class="close">&times;</a>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add">
                        <label for="schoolName">School Name:</label>
                        <input type="text" id="schoolName" name="schoolName" required>
                        <label for="location">Location:</label>
                        <input type="text" id="location" name="location" required>
                        <button type="submit">Add School</button>
                    </form>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <tr>
                        <th>School ID</th>
                        <th>School Name</th>
                        <th>Location</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>".$row["schoolID"]."</td>
                                    <td>".$row["schoolName"]."</td>
                                    <td>".$row["location"]."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No results found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
