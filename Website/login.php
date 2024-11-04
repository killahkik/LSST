<?php
session_start();
$loginmessage = "";
if (isset($_SESSION['username'])) {
    $loginmessage = "Logged in as: " . $_SESSION['username'] . ". <a href='logout.php'>Logout</a>";
} else {
    $loginmessage = "You are not logged in.";
}
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost', 'root', '', 'userdata');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // check if user is in database
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // verify password
        if ($password == $user['password']) {
            $_SESSION['username'] = $user['username'];
            $message = "Login successful.";
        } else {
            $message = "Invalid password or username.";
        }
    } else {
        $message = "Invalid password or username.";
    }

    $conn->close();
}
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
            <form action="login.php" method="post">
                <label for="name">Username:</label>
                <input type="text" id="username" name="username" required>
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