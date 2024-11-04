<?php
session_start();
$loginmessage = "";
if (isset($_SESSION['username'])) {
    $loginmessage = "Logged in as: " . $_SESSION['username'] . ". <a href='logout.php'>Logout</a>";
} else {
    $loginmessage = "You are not logged in.";
}
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
    // if username/email already exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $message = "Username already registered";
        $valid = false;
    }
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $message = "Email already registered";
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
            <a href="Home.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="searchplayers.php">Players</a>
            <a href="searchteams.php">Teams</a>
            <a href="searchgames.php">Games</a>
            <div class="loginmessage">
                <?php echo $loginmessage; ?>
            </div>
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