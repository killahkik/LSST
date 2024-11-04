<?php
session_start();
$loginmessage = "";
if (isset($_SESSION['username'])) {
    $loginmessage = "Logged in as: " . $_SESSION['username'] . ". <a href='logout.php'>Logout</a>";
} else {
    $loginmessage = "You are not logged in.";
}
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="stylesheet.css">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LSST</title>
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

</body>
</html>