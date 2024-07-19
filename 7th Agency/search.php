<?php
include_once("databaseconnect.php");

$conn = connection();

if (isset($_POST['query'])) {
    $query = $conn->real_escape_string($_POST['query']);
    $sql = "SELECT schoolName, location FROM school_details WHERE schoolName LIKE '%$query%' OR location LIKE '%$query%' LIMIT 10";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="dropdown-item" data-schoolname="' . $row['schoolName'] . '" data-location="' . $row['location'] . '">';
            echo $row['schoolName'] . ' (' . $row['location'] . ')';
            echo '</div>';
        }
    }
}

$conn->close();
?>
