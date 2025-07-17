<?php

// Database connection details
$host = 'your_database_host';  // Replace with your actual database host
$username = 'your_database_username';  // Replace with your actual database username
$password = 'your_database_password';  // Replace with your actual database password
$database = 'redstore_db';

// Establish a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["loginSubmit"])) {
        // Handle login form submission
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Validate the credentials against the database
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo json_encode(array("success" => true, "message" => "Login successful"));
        } else {
            echo json_encode(array("success" => false, "message" => "Invalid credentials"));
        }
    } elseif (isset($_POST["registerSubmit"])) {
        // Handle registration form submission
        $regUsername = $_POST["regUsername"];
        $email = $_POST["email"];
        $regPassword = $_POST["regPassword"];

        // Check if the username is already taken
        $checkUsernameQuery = "SELECT * FROM users WHERE username = '$regUsername'";
        $checkUsernameResult = $conn->query($checkUsernameQuery);

        if ($checkUsernameResult->num_rows > 0) {
            echo json_encode(array("success" => false, "message" => "Username already taken"));
            exit;
        }

        // Add the new user to the database
        $insertUserQuery = "INSERT INTO users (username, email, password) VALUES ('$regUsername', '$email', '$regPassword')";
        if ($conn->query($insertUserQuery) === TRUE) {
            echo json_encode(array("success" => true, "message" => "Registration successful"));
        } else {
            echo json_encode(array("success" => false, "message" => "Error registering user"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Invalid request"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}

// Close the database connection
$conn->close();
