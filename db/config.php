<?php
$servername = "localhost";
$username = "root";  // Default username for MySQL in XAMPP
$password = "";  // Default password for MySQL in XAMPP (empty)
$dbname = "cigarette_tracker";  // Your database name

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
