<?php
// Set the HTTP response headers to allow CORS
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin (not recommended for production)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allow the specified HTTP methods
header("Access-Control-Allow-Headers: Content-Type"); // Allow specific headers in requests

// Check if the HTTP request method is an OPTIONS request
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    // This is a preflight request, respond with a 200 OK status
    header("HTTP/1.1 200 OK");
    exit();
}

// Integrated database connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'formData';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the HTTP request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Prepare and execute an SQL query to insert data into the database
    $sql = "INSERT INTO userdata (name, email, subject, message) VALUES (?, ?, ?, ?)";
    
    // Check for SQL errors
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            // Form data submitted successfully
            echo "Form data submitted successfully!";
        } else {
            // Handle the execution error
            echo "Execution Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        // Handle the prepare error
        echo "Prepare Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle unsupported HTTP methods
    echo "Unsupported HTTP Method: " . $_SERVER["REQUEST_METHOD"];
}
?>
