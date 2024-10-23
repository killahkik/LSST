<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "userdata";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the name from the form
$name = $_POST['name'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (name) VALUES (?)");
$stmt->bind_param("s", $name);

// Execute the statement
$stmt->execute();

echo "New record created successfully";

$stmt->close();
$conn->close();
?>