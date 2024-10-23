<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "userdata";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//if($_SERVER["REQUEST_METHOD"] == "POST") {
// Get the name from the form
    $email = $_POST['email'];
    $user = $_POST['username'];
    $pass = $_POST['password'];

// Prepare and bind
    $stmt = $conn->prepare("INSERT INTO user (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $user, $pass);

// Execute the statement
$stmt->close();

$conn->close();
?>