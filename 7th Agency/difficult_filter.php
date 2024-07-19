<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="photo&icons/Logo w_ bg.png">
    <link rel="stylesheet" href="styles/admin1.css">
    <link rel="stylesheet" href="styles/tooltip.css">
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
            window.location.href = 'admin1.php'; // Redirect to the same page
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
                <li><a href="admin6.php">Reference</a></li>
            </ul>
        </div>
    <br>
    <h1>Records Filters</h1>
    <form method="POST" action="">
    <div class="fieldset-container">
        <fieldset class="tooltip">
            <legend>Filter by Employment Type, Desired Salary, and Availability</legend>
            <div class="tooltiptext">Display the full names and regions of applicants who aim for part-time or seasonal employment with a desired salary exceeding P30,000. The average days must be more than a week before applicants are available for work, sorted by their names.</div>
            <input type="checkbox" name="employment_type" value="PT_S"> Part Time or Seasonal<br>
            <input type="checkbox" name="desired_salary" value="30000"> Desired Salary Exceeds $30,000<br>
            <input type="checkbox" name="availdate" value="7"> Available Date for Work (more than a week)<br>
        </fieldset>

        <fieldset class="tooltip">
            <legend>Filter by Character Reference, Sex, and Employment Type</legend>
            <div class="tooltiptext">Count the number of references greater than or equal to 2 provided by each male applicant who applies for a full-time job. Display the applicant's ID, fullname, desired position, and their past position, sort by their applicantID descending.</div>
            <input type="checkbox" name="count_char_ref" value="2"> 2 or More Character Reference<br>
            <input type="checkbox" name="sex" value="male"> Male Applicants<br>
            <input type="checkbox" name="employment_type" value="FT"> Full Time<br>
        </fieldset>

        <fieldset class="tooltip">
            <legend>Filter by School and No. of Attendee</legend>
            <div class="tooltiptext">Count how many applicants share the same school at the college level (Bachelor's degree), only if more than 1 applicant attended. Group them and sort by the school name.</div>
            <input type="checkbox" name="schools" value="school"> School ID<br>
            <input type="checkbox" name="no_attendees" value="attendee"> No. of School Attendees (more than 1)<br>
        </fieldset>
        </div> 
        <div class="fieldset-container">
        <input type="submit" name="filter" value="Apply Filters">
        </div> 
    </form>
    

    <?php
    if (isset($_POST['filter'])) {
        include_once("databaseconnect.php");

        $conn = connection();

        // Initialize SQL queries
        $queries = [];

        // Filter by Employment Type, Desired Salary, and Availability
        if (isset($_POST['employment_type']) && isset($_POST['desired_salary']) && isset($_POST['availdate'])) {
            $queries[] = "SELECT P.fullName, P.region, AVG(DATEDIFF(CURDATE(), I.availWDate)) AS 'averageDays'
                          FROM applicant_profile AS P, application_information AS I 
                          WHERE P.applicantID = I.applicantID AND I.desiredSalary > 30000 AND I.desiredEmployment IN ('PT', 'S')
                          GROUP BY P.fullName, P.region
                          HAVING AVG(DATEDIFF(CURDATE(), I.availWDate)) > 7
                          ORDER BY P.fullName";
        }

        // Filter by Character Reference, Sex, and Employment Type
        if (isset($_POST['count_char_ref']) && isset($_POST['sex']) && isset($_POST['employment_type'])) {
            $queries[] = "SELECT P.applicantID, P.fullname, I.desiredPosition, W.employedPosition, COUNT(C.applicantID) AS 'refCount'
                          FROM applicant_profile AS P, application_information AS I, work_experience AS W, character_reference AS C
                          WHERE (P.applicantID = I.applicantID) AND (P.applicantID = W.applicantID) AND (P.applicantID = C.applicantID) AND P.sex = 'M' AND I.desiredEmployment = 'FT'
                          GROUP BY P.applicantID, P.fullname, I.desiredPosition, W.employedPosition
                          HAVING COUNT(C.applicantID) >= 2
                          ORDER BY P.applicantID DESC"; 
        }

        // Filter by School and No. of Attendee
        if (isset($_POST['schools']) && isset($_POST['no_attendees'])) {
            $queries[] = "SELECT E.schoolID, S.schoolName, COUNT(S.schoolID) AS 'applicantCount'
                          FROM educational_background AS E, school_details AS S
                          WHERE S.schoolID = E.schoolID AND E.degree LIKE '%Bachelor%'
                          GROUP BY E.schoolID, S.schoolName
                          HAVING COUNT(S.schoolID) > 1
                          ORDER BY S.schoolName";
        } 
        ?>

        <div class="table_container">
            <table>
                <?php
                foreach ($queries as $query) {
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {
                        echo "<table border='1'><tr>";
                        while ($field_info = $result->fetch_field()) {
                            echo "<th>{$field_info->name}</th>"; // Use th for headers
                        }
                        echo "</tr>";
    
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $cell) {
                                echo "<td>$cell</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No results found for query: $query";
                    }
                }
                // Close connection
                $conn->close();
                ?>
            </table>
        </div>
        <?php } ?>
    </div>
     <div class="floating-menu">
        <a href="filter1.php">Simple</a>
        <a href="moderate_filter.php">Moderate</a>
        <ab href="difficult_filter.php">Difficult</ab>
    </div>

</body>
</html>