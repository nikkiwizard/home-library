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

// Create the Loans table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS Loans (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    book_id INT(11) NOT NULL,
    borrower_name VARCHAR(255) NOT NULL,
    loan_date DATE NOT NULL,
    return_date DATE,
    FOREIGN KEY (book_id) REFERENCES Books(id)
);";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error;
}

// Fetch books from the database
$sql = "SELECT * FROM Books";
$result = $conn->query($sql);

echo "<h1>Welcome to Your Home Library!</h1>";

// Button to open the modal for adding a book
echo '<button id="addBookBtn">Add Book</button>';

// Modal for adding a book
echo '<div id="bookModal" style="display:none;">
        <div style="background-color:white; padding:20px; border:1px solid #ccc; width:300px; margin: 100px auto;">
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

// Modal for loaning a book
echo '<div id="loanModal" style="display:none;">
        <div style="background-color:white; padding:20px; border:1px solid #ccc; width:300px; margin: 100px auto;">
            <h2>Loan Book</h2>
            <form id="loanBookForm">
                <input type="hidden" id="book_id" name="book_id" required>
                <label for="borrower_name">Borrower Name:</label><br>
                <input type="text" id="borrower_name" name="borrower_name" required><br>
                <label for="loan_date">Loan Date:</label><br>
                <input type="date" id="loan_date" name="loan_date" required><br><br>
                <input type="submit" value="Loan Book">
                <button type="button" id="closeLoanModal">Close</button>
            </form>
        </div>
      </div>';

// Modal for returning a book
echo '<div id="returnModal" style="display:none;">
        <div style="background-color:white; padding:20px; border:1px solid #ccc; width:300px; margin: 100px auto;">
            <h2>Return Book</h2>
            <form id="returnBookForm">
                <input type="hidden" id="return_book_id" name="return_book_id" required>
                <label for="return_date">Return Date:</label><br>
                <input type="date" id="return_date" name="return_date" required><br><br>
                <input type="submit" value="Return Book">
                <button type="button" id="closeReturnModal">Close</button>
            </form>
        </div>
      </div>';

// Display the books in a table
if ($result->num_rows > 0) {
    echo "<h2>Books in Library</h2>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Published Year</th>
                <th>Genre</th>
                <th>Action</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["title"] . "</td>
                <td>" . $row["author"] . "</td>
                <td>" . $row["published_year"] . "</td>
                <td>" . $row["genre"] . "</td>
                <td><button onclick='openLoanModal(" . $row["id"] . ")'>Loan Book</button></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No books found in the database.";
}

// Fetch loans from the database, excluding returned books
$sql = "SELECT Loans.id, Books.title, Loans.borrower_name, Loans.loan_date 
        FROM Loans 
        JOIN Books ON Loans.book_id = Books.id 
        WHERE Loans.return_date IS NULL"; // Exclude loans with a return date
$loan_result = $conn->query($sql);

if ($loan_result->num_rows > 0) {
    echo "<h2>Current Loans</h2>";
    echo "<table border='1'>
            <tr>
                <th>Loan ID</th>
                <th>Book Title</th>
                <th>Borrower Name</th>
                <th>Loan Date</th>
                <th>Action</th>
            </tr>";
    while($loan_row = $loan_result->fetch_assoc()) {
        echo "<tr>
                <td>" . $loan_row["id"] . "</td>
                <td>" . $loan_row["title"] . "</td>
                <td>" . $loan_row["borrower_name"] . "</td>
                <td>" . $loan_row["loan_date"] . "</td>
                <td><button onclick='openReturnModal(" . $loan_row["id"] . ")'>Return Book</button></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No books are currently loaned out.";
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

document.getElementById('closeLoanModal').onclick = function() {
    document.getElementById('loanModal').style.display = 'none';
};

document.getElementById('closeReturnModal').onclick = function() {
    document.getElementById('returnModal').style.display = 'none';
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

function openLoanModal(bookId) {
    document.getElementById('book_id').value = bookId; // Set the book ID in the hidden input
    document.getElementById('loanModal').style.display = 'block'; // Show the loan modal
}

document.getElementById('loanBookForm').onsubmit = function(event) {
    event.preventDefault(); // Prevent form submission

    const book_id = document.getElementById('book_id').value;
    const borrower_name = document.getElementById('borrower_name').value;
    const loan_date = document.getElementById('loan_date').value;

    // Send data to the server using fetch
    fetch('loan_book.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ book_id, borrower_name, loan_date }),
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload(); // Reload the page to see the new loan
    })
    .catch((error) => {
        console.error('Error:', error);
    });
};

function openReturnModal(loanId) {
    document.getElementById('return_book_id').value = loanId; // Set the loan ID in the hidden input
    document.getElementById('returnModal').style.display = 'block'; // Show the return modal
}

document.getElementById('returnBookForm').onsubmit = function(event) {
    event.preventDefault(); // Prevent form submission

    const return_book_id = document.getElementById('return_book_id').value;
    const return_date = document.getElementById('return_date').value;

    // Send data to the server using fetch
    fetch('return_book.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ return_book_id, return_date }),
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload(); // Reload the page to see the updated loans
    })
    .catch((error) => {
        console.error('Error:', error);
    });
};
</script>