<?php
$servername = "localhost";
$username = "testuser";
$password = "testpass";
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Perform a simple query
$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<br>Tables in database:<br>";
    while($row = $result->fetch_assoc()) {
        echo $row["Tables_in_$dbname"] . "<br>";
    }
} else {
    echo "No tables found in the database.";
}

$conn->close();
?>