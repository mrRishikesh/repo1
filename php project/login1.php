
<?php
// Database configuration
$host = 'localhost';
$dbName = 'tourform';
$username = 'root'; // Default username for XAMPP is 'root'
$password = ''; // Default password for XAMPP is empty

// Establish a database connection
try {
    $connection = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Handle sign up form submission
if (isset($_POST['txt'], $_POST['email'], $_POST['pswd'])) {
    $username = $_POST['txt'];
    $email = $_POST['email'];
    $password = $_POST['pswd'];

    // Check if the user is already registered
    try {
        $stmt = $connection->prepare("SELECT * FROM logininfo WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // User is already registered, display a message and prevent signing up again
            header('location: signed.html');
            exit();
        } else {

    // Prepare and execute the INSERT statement
    try {
        $stmt = $connection->prepare("INSERT INTO logininfo (username, email, hashed_password) VALUES (:username, :email, :hashedPassword)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $stmt->bindParam(':hashedPassword', $hashedPassword);

        if ($stmt->execute()) {
            // Redirect to a success page or perform any other actions
            header('Location: signedup.html');
            exit();
        } else {
            // Display an error message or perform any other actions
            echo "Error: Unable to sign up.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
    }
    catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
}

// Handle login form submission
if (isset($_POST['email'], $_POST['pswd'])) {
    $email = $_POST['email'];
    $password = $_POST['pswd'];

    // Prepare and execute the SELECT statement
    try {
        $stmt = $connection->prepare("SELECT * FROM logininfo WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $row['hashed_password'];
            if (password_verify($password, $hashedPassword)) {
                // User authenticated, redirect to the logged-in page or perform any other actions
                header('Location: success.html');
                exit();
            }
        }

        // Invalid credentials, display an error message or perform any other actions
        header("Location: error.html");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Close the database connection
// $connection->closeCursor();  //connection will be closed automatically no need to explicitly define it
?>



