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
$return_book_id = $data['return_book_id'];
$return_date = $data['return_date'];

// Update the loan record with the return date
$sql = "UPDATE Loans SET return_date = '$return_date' WHERE id = '$return_book_id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'Book returned successfully!']);
} else {
    echo json_encode(['message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>