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
//if($_SERVER["REQUEST_METHOD"] == "POST") {
// Get the name from the form
    $user = $_GET['username'];
    $email = $_GET['email'];
    $pass = $_GET['password'];

    $sql = "INSERT INTO user (email, username, password) 
            VALUES ('$email', '$user', '$pass')";

    if($conn-> query($sql)==TRUE){
        echo"user inputs stored in table";
    }else{
        echo "Error adding into table: " . $conn->error;
    }

$conn->close();
?>

