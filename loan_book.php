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
$book_id = $data['book_id'];
$borrower_name = $data['borrower_name'];
$loan_date = $data['loan_date'];

// Insert the new loan into the database
$sql = "INSERT INTO Loans (book_id, borrower_name, loan_date) VALUES ('$book_id', '$borrower_name', '$loan_date')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'Book loaned successfully!']);
} else {
    echo json_encode(['message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>