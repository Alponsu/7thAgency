
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
            <legend>Group by Desired Position</legend>
            <div class="tooltiptext">Display the average desired salary of applicants seeking for full-time employment per desired position having an average greater than 50,000 and sort them by average salary.</div>
            <input type="checkbox" name="desiredPosition" value="FT"> Seeking for full-time employment <br>
            <input type="checkbox" name="ave_greater_than_50000" value="50000"> Having an average greater than 50,000<br>
        </fieldset>
        
        <fieldset class="tooltip">
            <legend>Group by Age and Region</legend>
            <div class="tooltiptext">Count applicants who are single and aged over 20 years. Group them by age and region, and sort the results by age.</div>
            <input type="checkbox" name="maritalStatus" value="S"> Marital Status is Single <br>
            <input type="checkbox" name="age" value="20"> Age is above 20 yrs old<br>
        </fieldset>
        
        <fieldset class="tooltip">
            <legend>Group by Employed Position</legend>
            <div class="tooltiptext">Count the number of applicants whose last employment was more than 5 years ago. Group and sort the results by employed position.</div>
            <input type="checkbox" name="employmentduration" value="5"> Last Employment was <br> more than 5 years ago<br>
        </fieldset>
        
        <fieldset class="tooltip">
            <legend>Group by Employed Position</legend>
            <div class="tooltiptext">Count applicants whose last employment was over 5 years ago and whose position includes 'Software'. Group by position, include only positions with at least one applicant, and sort by position.</div>
            <input type="checkbox" name="employmentduration2" value="5"> Last Employment was more than 5 years ago <br>
            <input type="checkbox" name="employedPosition2" value="%Software%"> Employed position includes 'Software'<br>
            <input type="checkbox" name="count_greater_than_0" value="1"> Having at least one applicant per Employed Position<br>
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


        $queries = [];

        // Group by Desired Position
        if (isset($_POST['desiredPosition']) && isset($_POST['ave_greater_than_50000'])) {
            $queries[] = "SELECT desiredPosition, AVG(desiredSalary) AS averageSalary 
                          FROM application_information
                          WHERE desiredEmployment = 'FT' 
                          GROUP BY desiredPosition 
                          HAVING AVG(desiredSalary) > 50000 
                          ORDER BY averageSalary; ";
        }

        // Group by Age and Region
        if (isset($_POST['maritalStatus']) && isset($_POST['age'])) {
            $queries[] = "SELECT age, region, COUNT(*) AS applicantsCount 
                          FROM applicant_profile 
                          WHERE maritalStatus = 'S' AND age > 20 
                          GROUP BY age, region 
                          ORDER BY age;";
        }

        // Group by Employed Position
        if (isset($_POST['employmentduration']))
            $queries[] = "SELECT employedPosition, COUNT(*) AS totalApplicants 
                          FROM work_experience
                          WHERE DATEDIFF(CURDATE(), dateEmployed) / 365 > 5
                          GROUP BY employedPosition 
                          ORDER BY employedPosition;";

       
        // Group by Employed Position
        if (isset($_POST['employmentduration2'])&& isset($_POST['employedPosition2']) & isset($_POST['count_greater_than_0'])) {
            $queries[] = "SELECT employedPosition, COUNT(*) AS totalApplicants 
                          FROM work_experience 
                          WHERE DATEDIFF(CURDATE(), dateEmployed) / 365 > 5 AND employedPosition LIKE '%Software%' 
                          GROUP BY employedPosition 
                          HAVING COUNT(*) >= 1 
                          ORDER BY employedPosition;";
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
        <ab href="moderate_filter.php">Moderate</ab>
        <a href="difficult_filter.php">Difficult</a>
    </div>
</body>
</html>
