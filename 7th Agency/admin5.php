<?php

include_once("databaseconnect.php");

$conn = connection();

// Handle update operation
if (isset($_POST['update'])) {
    $employerID = $_POST['employerID'];
    $employer = $_POST['employer'];
    $employerContact = $_POST['employerContact'];
    $employerAddress = $_POST['employerAddress'];
    $dateEmployed = $_POST['dateEmployed'];
    $employedPosition = $_POST['employedPosition'];
    $reasonforLeaving = $_POST['reasonforLeaving'];
    $applicantID = $_POST['applicantID'];

       // Prepare SQL statement for update
    $sql = "UPDATE work_experience SET 
    employer=?,
    employerContact=?,
    employerAddress=?,
    dateEmployed=?,
    employedPosition=?,
    reasonforLeaving=?
    WHERE employerID=?";

// Prepare statement and bind parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $employer, $employerContact, $employerAddress, $dateEmployed, $employedPosition, $reasonforLeaving, $employerID);


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
    $id = $_POST['applicantID'];

    // Validate that ID is provided and is an integer
    if (!empty($id) && filter_var($id, FILTER_VALIDATE_INT)) {
        // Check if the applicantID exists in applicant_profile table
        $check_sql = "SELECT COUNT(*) as count FROM applicant_profile WHERE applicantID=?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            // Begin transaction
            $conn->begin_transaction();

            try {
                // Delete from application_information table (child table)
                $sql1 = "DELETE FROM application_information WHERE applicantID=?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("i", $id);
                if ($stmt1->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from application_information: " . $conn->error);
                }
                $stmt1->close();

                // Delete from educational_background table (child table)
                $sql2 = "DELETE FROM educational_background WHERE applicantID=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("i", $id);
                if ($stmt2->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from educational_background: " . $conn->error);
                }
                $stmt2->close();

                // Delete from character_reference table (child table)
                $sql3 = "DELETE FROM character_reference WHERE applicantID=?";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->bind_param("i", $id);
                if ($stmt3->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from character_reference: " . $conn->error);
                }
                $stmt3->close();

                // Delete from work_experience table (child table)
                $sql4 = "DELETE FROM work_experience WHERE applicantID=?";
                $stmt4 = $conn->prepare($sql4);
                $stmt4->bind_param("i", $id);
                if ($stmt4->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from work_experience: " . $conn->error);
                }
                $stmt4->close();

                // Delete from applicant_profile table (parent table)
                $sql5 = "DELETE FROM applicant_profile WHERE applicantID=?";
                $stmt5 = $conn->prepare($sql5);
                $stmt5->bind_param("i", $id);
                if ($stmt5->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from applicant_profile: " . $conn->error);
                }
                $stmt5->close();

                // Commit transaction if all deletions succeed
                $conn->commit();
                $popup_message = "All records deleted successfully. " . $popup_message;
            } catch (Exception $e) {
                $conn->rollback(); // Rollback transaction on error
                $popup_message = $e->getMessage();
            }
        } else {
            $popup_message = "Applicant ID does not exist.";
        }
    } else {
        $popup_message = "Invalid applicant ID.";
    }
}

// SQL query to select data from the table
$sql = "SELECT * FROM work_experience";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="photo&icons/Logo w_ bg.png">
    <link rel="stylesheet" href="styles/admin1.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <title>7th Agency</title>

    <script type="text/javascript">
        function showAlert(message) {
            alert(message);
            window.location.href = 'admin5.php'; // Redirect to the same page
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
                <li><a href="admin2.php">AP Information</a></li>
                <li><a href="admin3.php">Educational</a></li>
                <li><a href="admin4.php">School</a></li>
                <li><ab href="admin5.php">Work Experience</ab></li>
                <li><a href="admin6.php">Reference</a></li>
                <li><a href="filter1.php">Filter</a></li>
            </ul>
        </div>
        
        <div class="container">
            <h1>Admin Dashboard</h1>
            <h2>Work Experience</h2>

            <form method="POST" action="">
                <label for="applicantID">Applicant ID: </label>
                <input type="text" id="applicantID" name="applicantID" required>
                <input type="submit" name="delete" value="Delete">
            </form>

            <div class="table-container">
                <table>
                <tr>
                    <th>Employer ID</th>
                    <th>Employer</th>
                    <th>Employer Contact</th>
                    <th>Employer Address</th>
                    <th>Date Employed</th>
                    <th>Employed Position</th>
                    <th>Reason For Leaving</th>
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
                                    <td>".$row["employerID"]."<input type='hidden' name='employerID' value='".$row["employerID"]."'></td>
                                    <td><input type='text' name='employer' value='".$row["employer"]."'></td>
                                    <td><input type='text' name='employerContact' value='".$row["employerContact"]."'></td>
                                    <td><input type='text' name='employerAddress' value='".$row["employerAddress"]."'></td>
                                    <td><input type='date' name='dateEmployed' value='".$row["dateEmployed"]."'></td>
                                    <td><input type='text' name='employedPosition' value='".$row["employedPosition"]."'></td>
                                    <td><input type='text' name='reasonforLeaving' value='".$row["reasonforLeaving"]."'></td>
                                    <td>".$row["applicantID"]."<input type='hidden' name='applicantID' value='".$row["applicantID"]."'></td>
                                    <td>
                                        <input type='submit' name='update' value='Update'>
                                    </td>
                                </form>


                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No results found</td></tr>";
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