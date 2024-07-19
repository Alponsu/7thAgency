<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="photo&icons/Logo w_ bg.png">
    <title>Document</title>
</head>
<body>
<?php
session_start();
include_once("databaseconnect.php");

$conn = connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $email = trim($email);
    $password = trim($password);

    // Prepare statement
    $stmt = $conn->prepare('SELECT * FROM login_info WHERE email = ?');
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify password
        if ($user && $password === $user['password']) {
            $_SESSION['email'] = $email;
            header("Location: admin1.php");
            exit();
        } else {
            header("Location: adminlogin.php?error=1");
            exit();
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

// Close connection
$conn->close();
?>
</body>
</html>
