<?php
include_once("databaseconnect.php");

$conn = connection();

// Handle update operation
if (isset($_POST['update'])) {
    $applicationID = $_POST['applicationID'];
    $desiredPosition = $_POST['desiredPosition'];
    $availWDate = $_POST['availWDate'];
    $desiredSalary = $_POST['desiredSalary'];
    $desiredEmployment = $_POST['desiredEmployment'];

    // Prepare SQL statement with placeholders
    $sql = "UPDATE application_information SET 
      desiredPosition=?, 
      availWDate=?, 
      desiredSalary=?, 
      desiredEmployment=? 
      WHERE applicationID=?";

    // Prepare and bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $desiredPosition, $availWDate, $desiredSalary, $desiredEmployment, $applicationID);

    try {
        // Execute the update statement
        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Record updated successfully');</script>";
        } else {
            throw new Exception("Error updating record: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "<script type='text/javascript'>alert('Invalid Input: " . $e->getMessage() . "');</script>";
    }

    // Close statement
    $stmt->close();
}

// Handle delete operation
$popup_message = '';
if (isset($_POST['delete'])) {
    $applicationID = $_POST['applicationID'];

    // Validate that applicationID is provided and is an integer
    if (!empty($applicationID)) {
        // Check if the applicationID exists in application_information table
        $check_sql = "SELECT COUNT(*) as count FROM application_information WHERE applicationID=?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $applicationID);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            // Begin transaction
            $conn->begin_transaction();

            try {
                // Delete from application_information table
                $sql1 = "DELETE FROM application_information WHERE applicationID=?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("s", $applicationID);
                if ($stmt1->execute()) {
                    $popup_message .= "Record deleted successfully.";
                } else {
                    throw new Exception("Error deleting from application_information: " . $conn->error);
                }
                $stmt1->close();

                // Commit transaction if deletion succeeds
                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback(); // Rollback transaction on error
                $popup_message = $e->getMessage();
            }
        } else {
            $popup_message = "Application ID does not exist.";
        }
    } else {
        $popup_message = "Invalid application ID.";
    }
}

// Fetch all data from application_information table
$sql = "SELECT * FROM application_information";
$result = $conn->query($sql);

// Check if search button is pressed
if (isset($_POST['search'])) {
    $search_id = $_POST['applicantID'];

    // Validate ID
    if (!empty($search_id) && filter_var($search_id, FILTER_VALIDATE_INT)) {
        // Prepare SQL statement to fetch specific record
        $search_sql = "SELECT * FROM application_information WHERE applicationID=?";
        $search_stmt = $conn->prepare($search_sql);
        $search_stmt->bind_param("i", $search_id);
        $search_stmt->execute();
        $search_result = $search_stmt->get_result();

        // Check if record exists
        if ($search_result->num_rows > 0) {
            // Output data of each row
            $result = $search_result; // Replace original result with searched result
        } else {
            echo "<script type='text/javascript'>alert('No records found for Applicant ID: $search_id');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('Invalid Application ID');</script>";
    }
}

if (isset($_POST['show'])) {

        // Prepare SQL statement to fetch specific record
        $show_sql = "SELECT * FROM application_information";
        $show_stmt = $conn->prepare($show_sql);
        $show_stmt->execute();
        $show_result = $show_stmt->get_result();

        // Check if record exists
        if ($show_result->num_rows > 0) {
            
            $result = $show_result; // Replace original result with searched result
        } else {
            echo "<script type='text/javascript'>alert('No records found');</script>";
        }
  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/admin1.css">
    <link rel="icon" href="photo&icons/Logo w_ bg.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <title>7th Agency</title>
    <style>
        .form-inline {
            display: inline;
        }
    </style>
    <script type="text/javascript">
        function showAlert(message) {
            alert(message);
            window.location.href = 'admin2.php'; // Redirect to the same page
        }
    </script>
</head>
<body>
    <div class="applicant-container">
        <div class="header">
            <ul>
                <img class="logo" src="photo&icons/Transparent Logo.png" alt="Logo">
                <li><a href="index.php">Home</a></li>
                <li><a href="admin1.php">AP Profile</a></li>
                <li><ab href="admin2.php">AP Information</ab></li>
                <li><a href="admin3.php">Educational</a></li>
                <li><a href="admin4.php">School</a></li>
                <li><a href="admin5.php">Work Experience</a></li>
                <li><a href="admin6.php">Reference</a></li>
                <li><a href="filter1.php">Filter</a></li>
            </ul>
        </div>
        
        <div class="container">
            <h1>Admin Dashboard</h1>
            <h2>Application Information</h2>

            <form method="POST" action=""class="form-inline">
                <label for="applicantID">Applicant ID: </label>
                <input type="text" id="applicantID" name="applicantID" required>    
                <input type="submit" name="search" value="Search">        
            </form>
            <form method="POST" action=""class="form-inline">
                <input type="submit" name="show" value="Show All">          
            </form>


            <div class="table-container">
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Desired Position</th>
                        <th>Available Working Date</th>
                        <th>Desired Salary</th>
                        <th>Desired Employment</th>
                        <th>Applicant ID</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                    if ($popup_message != '') {
                        echo "<script type='text/javascript'>showAlert('$popup_message');</script>";
                    }

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <form method='POST' action=''>
                                        <td>".$row["applicationID"]."<input type='hidden' name='applicationID' value='".$row["applicationID"]."'></td>
                                        <td><input type='text' name='desiredPosition' value='".$row["desiredPosition"]."'></td>
                                        <td><input type='date' name='availWDate' value='".$row["availWDate"]."'></td>
                                        <td><input type='number' name='desiredSalary' value='".$row["desiredSalary"]."'></td>
                                        <td><input type='text' name='desiredEmployment' value='".$row["desiredEmployment"]."'></td>
                                        <td>".$row["applicantID"]."<input type='hidden' name='applicantID' value='".$row["applicantID"]."'></td>
                                        <td><input type='submit' name='update' value='Update'> <input type='submit' name='delete' value='Delete'></td>
                                        
                                    </form>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No results found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
