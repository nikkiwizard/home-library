<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Create the Books table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS Books (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    published_year INT(4),
    genre VARCHAR(100)
);";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error;
}

// Fetch books from the database
$sql = "SELECT * FROM Books";
$result = $conn->query($sql);

echo "<h1>Welcome to Your Home Library!</h1>";

// Button to open the modal
echo '<button id="addBookBtn">Add Book</button>';

// Modal for adding a book
echo '<div id="bookModal" style="display:none;">
        <div style="
            background-color:white; 
            padding:20px; 
            border:1px solid #ccc; 
            width:300px; 
            margin: 100px auto;">
            
            <h2>Add Book</h2>
            <form id="addBookForm">
                <label for="title">Title:</label><br>
                <input type="text" id="title" name="title" required><br>
                <label for="author">Author:</label><br>
                <input type="text" id="author" name="author" required><br>
                <label for="published_year">Published Year:</label><br>
                <input type="number" id="published_year" name="published_year" required><br>
                <label for="genre">Genre:</label><br>
                <input type="text" id="genre" name="genre"><br><br>
                <input type="submit" value="Add Book">
                <button type="button" id="closeModal">Close</button>
            </form>
        </div>
      </div>';

// Display the books in a table
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Published Year</th>
                <th>Genre</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["title"] . "</td>
                <td>" . $row["author"] . "</td>
                <td>" . $row["published_year"] . "</td>
                <td>" . $row["genre"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No books found in the database.";
}

$conn->close();
?>

<script>
document.getElementById('addBookBtn').onclick = function() {
    document.getElementById('bookModal').style.display = 'block';
};

document.getElementById('closeModal').onclick = function() {
    document.getElementById('bookModal').style.display = 'none';
};

document.getElementById('addBookForm').onsubmit = function(event) {
    event.preventDefault(); // Prevent form submission

    const title = document.getElementById('title').value;
    const author = document.getElementById('author').value;
    const published_year = document.getElementById('published_year').value;
    const genre = document.getElementById('genre').value;

    // Send data to the server using fetch
    fetch('add_book.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ title, author, published_year, genre }),
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload(); // Reload the page to see the new book
    })
    .catch((error) => {
        console.error('Error:', error);
    });
};
</script>