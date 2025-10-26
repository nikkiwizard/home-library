<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "testuser";
$password = "testpass";
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Get the posted data
$data = json_decode(file_get_contents("php://input"), true);
$title = $data['title'];
$author = $data['author'];
$published_year = $data['published_year'];
$genre = $data['genre'];

// Insert the new book into the database
$sql = "INSERT INTO Books (title, author, published_year, genre) VALUES ('$title', '$author', '$published_year', '$genre')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'Book added successfully!']);
} else {
    echo json_encode(['message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>