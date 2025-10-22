<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// Try connecting to MySQL
$conn = new mysqli($servername, $username, $password);

// Create a database if it doesn’t exist
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

echo "<h1>PHP + MySQL inside Docker!</h1>";

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    echo "<p>Connected to MySQL successfully!</p>";
}
?>
