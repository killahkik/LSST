<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "userdata";
$valid = true;
$message = "";

// connect and check connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// get form data
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $user = $_POST['username'];
    $pass = $_POST['password'];
    //print_r($_POST);

    if(empty($email) || empty($user) || empty($pass)) {
        $message = "Please fill out all fields";
        $valid = false;
    }
    if(strlen($user) < 6 || strlen($pass) < 6) {
        $message = "Username and password must be at least 6 characters";
        $valid = false;
    }
    if(strlen($user) > 20 || strlen($pass) > 20) {
        $message = "Username and password must be less than 20 characters";
        $valid = false;
    }
    // if username already exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?") ;
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->get_result();
    if($stmt->num_rows > 0) {
        $message = "Username already exists";
        $valid = false;
    }
    
    if($valid) {
    $stmt = $conn->prepare("INSERT INTO user (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $user, $pass);
    $stmt->execute();
    $message = "Registration successful";
    }

    if (strlen($user) == 0 && strlen($pass) == 0 && strlen($email) == 0) {
        $message = "";
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="stylesheet.css">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LSST Registration</title>
    </head>
    <body>
        <div class="nav">
            <a href="Home.html">Home</a>
            <a href="login.php">Login</a>
            <a href="submit.php">Register</a>
            <a href="searchplayers.html">Players</a>
            <a href="searchteams.html">Teams</a>
            <a href="searchgames.html">Games</a>
        </div>
        <div style="margin: 20px">
            <form action="submit.php" method="post">
                <label for="name">Username:</label>
                <input type="text" id="username" name="username" required>
                <br>
                <label for="email" style="margin-left: 34.5px">Email:</label>
                <input style="margin-top: 20px" type="email" id="email" name="email" required>
                <br>
                <label for="password">Password:</label>
                <input style="margin-top: 20px; margin-left: 4px" type="password" id="password" name="password" required>
                <br>
                <input style="margin-top: 20px; margin-left: 85px" type="submit" value="Submit">
            </form>
            <p><?php echo $message; ?></p>
        </div>
    </body>
</html>