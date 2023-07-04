<?php
// Database connection details
$host = "localhost"; // XAMPP server hostname (usually 'localhost')
$username = "root"; // MySQL username (default is 'root')
$password = ""; // MySQL password (default is empty)
$database = "tourform"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from a form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $sub = $_POST["sub"];
    $messag = $_POST["messag"];

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO get_in_touch (fname, lname, email, sub, messag) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fname, $lname, $email, $sub, $messag);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Information stored successfully.";
    } else {
        echo "Error storing information: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

