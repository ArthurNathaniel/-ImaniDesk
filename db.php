<?php
// db.php
$servername = "localhost"; // Your host
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "imanidesk"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
