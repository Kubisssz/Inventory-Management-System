<?php
// Database connection parameters
$servername = "127.0.0.1";  // DB server (localhost for local server)
$username = "root";         // DB username (default for XAMPP is 'root')
$password = "";             // DB password (default for XAMPP is empty)
$dbname = "inventory_db";   // Make sure this matches the database name you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
