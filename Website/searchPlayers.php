<?php
session_start();
$loginmessage = "";
if (isset($_SESSION['username'])) {
    $loginmessage = "Logged in as: " . $_SESSION['username'] . ". <a href='logout.php'>Logout</a>";
    $_POST['username'] = $_SESSION['username'];
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
    <a href="searchPlayers.php">Players</a>
    <a href="searchTeams.php">Teams</a>
    <a href="searchGames.php">Games</a>
    <div class="loginmessage">
        <?php echo $loginmessage; ?>
    </div>
  </div>
<h1>Player Search</h1>
<script>
  // Function to submit the form using AJAX
  function submitForm(event) {
    event.preventDefault(); // Prevent default form submission

    // Create a new FormData object to hold the form data
    const formData = new FormData(document.getElementById("inputForm"));

    // Send an AJAX request to process.php
    fetch("processPlayers.php", {
      method: "POST",
      body: formData
    })
            .then(response => response.text())
            .then(data => {
              // Display the response in the result div
              document.getElementById("result").innerHTML = data;
            })
            .catch(error => console.error("Error:", error));
  }
</script>

<form id="inputForm" onsubmit="submitForm(event)">
  <label for="userInput">Enter something:</label>
  <input type="text" id="userInput" name="userInput" required>
  <button type="submit">Submit</button>
</form>
<div id="result"></div>

</body>
</html>
