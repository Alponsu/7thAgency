<?php
//ITO ADMIN6
// Database connection parameters
$db_servername = "localhost";
$db_username = "root";
$db_password = "Admin_123";
$db_name = "project";
$conn = "";

// Create connection
try {
    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo "<script type='text/javascript'>alert('Could not connect: " . $e->getMessage() . "');</script>";
    exit;
}
// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'checkApplicantID') {
        $id = $_POST['applicantID'];
        $response = ['success' => false, 'message' => ''];

        if (!empty($id) && filter_var($id, FILTER_VALIDATE_INT)) {
            $check_sql = "SELECT COUNT(*) as count FROM applicant_profile WHERE applicantID=?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("i", $id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                $check_ref_sql = "SELECT COUNT(*) as count FROM character_reference WHERE applicantID=?";
                $check_ref_stmt = $conn->prepare($check_ref_sql);
                $check_ref_stmt->bind_param("i", $id);
                $check_ref_stmt->execute();
                $result_ref = $check_ref_stmt->get_result();
                $row_ref = $result_ref->fetch_assoc();

                if ($row_ref['count'] < 3) {
                    $response['success'] = true;
                } else {
                    $response['message'] = "Applicant already has 3 references.";
                }
            } else {
                $response['message'] = "Applicant ID does not exist.";
            }
        } else {
            $response['message'] = "Invalid applicant ID.";
        }

        echo json_encode($response);
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'addReference') {
        $applicantID = $_POST['applicantID'];
        $refName = $_POST['refName'];
        $refTitle = $_POST['refTitle'];
        $refCompany = $_POST['refCompany'];
        $refPhone = $_POST['refPhone'];

        if (!empty($applicantID) && !empty($refName) && !empty($refTitle) && !empty($refCompany) && !empty($refPhone)) {
            $sql = "INSERT INTO character_reference (applicantID, refName, refTitle, refCompany, refPhone) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $applicantID, $refName, $refTitle, $refCompany, $refPhone);

            if ($stmt->execute()) {
                echo "Reference added successfully.";
            } else {
                echo "Error adding reference: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Missing parameters.";
        }
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'deleteReference') {
        $referenceID = $_POST['referenceID'];

        if (!empty($referenceID) && preg_match('/^[a-zA-Z0-9]{1,3}-[a-zA-Z0-9]{4}$/', $referenceID)) {
            $delete_ref_sql = "DELETE FROM character_reference WHERE referenceID=?";
            $delete_ref_stmt = $conn->prepare($delete_ref_sql);
            $delete_ref_stmt->bind_param("s", $referenceID);

            if ($delete_ref_stmt->execute()) {
                echo "Reference ID $referenceID deleted successfully.";
            } else {
                echo "Error deleting reference: " . $conn->error;
            }

            $delete_ref_stmt->close();
        } else {
            echo "Invalid Reference ID.";
        }
        exit;
    }
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
$sql = "SELECT * FROM character_reference";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    }

    function deleteReference() {
        var referenceID = prompt("Enter Reference ID to delete:");

        if (referenceID.trim() === "") {
            alert("Please enter Reference ID.");
            return;
        }

        // Send an AJAX request to delete the reference
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                showAlert(xhr.responseText); // Show alert with the response message
                window.location.reload(); // Refresh page after deletion
            }
        };
        xhr.send("deleteReferenceID=" + referenceID);
    }
    function handleCreate() {
    var applicantID = document.getElementById('applicantID').value;

    if (!applicantID || isNaN(applicantID)) {
        alert("Invalid applicant ID.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                var refName = prompt("Enter Reference Name:");
                var refTitle = prompt("Enter Reference Title:");
                var refCompany = prompt("Enter Reference Company:");
                var refPhone = prompt("Enter Reference Phone:");

                if (refName && refTitle && refCompany && refPhone) {
                    addReference(applicantID, refName, refTitle, refCompany, refPhone);
                } else {
                    alert("Please provide all reference details.");
                }
            } else {
                alert(response.message);
            }
        }
    };
    xhr.send("action=checkApplicantID&applicantID=" + applicantID);
}

function addReference(applicantID, refName, refTitle, refCompany, refPhone) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert("Reference added successfully.");
            window.location.reload();
        }
    };
    xhr.send("action=addReference&applicantID=" + applicantID + "&refName=" + refName + "&refTitle=" + refTitle + "&refCompany=" + refCompany + "&refPhone=" + refPhone);
}

function deleteReference() {
    var referenceID = prompt("Enter Reference ID to delete:");

    if (!referenceID.trim()) {
        alert("Please enter Reference ID.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
            window.location.reload();
        }
    };
    xhr.send("action=deleteReference&referenceID=" + referenceID);
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
                <li><a href="admin5.php">Work Experience</a></li>
                <li><ab href="admin6.php">Reference</ab></li>
                <li><a href="filter1.php">Filter</a></li>
            </ul>
        </div>
        
        <div class="container">
            <h1>Admin Dashboard</h1>
            <h2>Character Reference</h2>

            <form method="POST" action="">
                <label for="applicantID">Applicant ID: </label>
                <input type="text" id="applicantID" name="applicantID" required>
                <input type="submit" name="delete" value="Delete">
                <button type="button" onclick="handleCreate()">Create</button>
                <button type="button" onclick="deleteReference()">Delete Reference ID</button>
                
            </form>
            
            <div class="table-container">
                <table>
                <tr>
                        <th>Reference ID</th>
                        <th>Reference Name</th>
                        <th>Reference Title</th>
                        <th>Reference Company</th>
                        <th>Reference Phone</th>
                        <th>Applicant ID</th>
                    </tr>

                    <?php
                    if ($popup_message != '') {
                        echo "<script type='text/javascript'>showAlert('$popup_message');</script>";
                    }

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>".$row["referenceID"]."</td>
                                    <td>".$row["refName"]."</td>
                                    <td>".$row["refTitle"]."</td>
                                    <td>".$row["refCompany"]."</td>
                                    <td>".$row["refPhone"]."</td>
                                    <td>".$row["applicantID"]."</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No results found</td></tr>";
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