<?php
    function connection(){
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "Admin_123";
        $dbname = "project";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else{
            return $conn;
        }
    }
?>