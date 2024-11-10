<?php
session_start();
$loginmessage = "";
if (isset($_SESSION['username'])) {
    $loginmessage = "Logged in as: " . $_SESSION['username'] . ". <a href='logout.php'>Logout</a>";
} else {
    $loginmessage = "You are not logged in.";
}
include('updateTeamScores.php');
$loadPhpTeamFunction = updateNFLTeams();
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
  <h1>Team Search</h1>
  <script>
      window.onload = myOnloadFunction;
      function myOnloadFunction(){
          <?php $loadPhpTeamFunction; ?>
      }
      // Function to submit the form using AJAX
      function submitForm(event) {
          event.preventDefault(); // Prevent default form submission

          // Create a new FormData object to hold the form data
          const formData = new FormData(document.getElementById("inputForm"));

          // Send an AJAX request to process.php
          fetch("processTeams.php", {
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
      function loadPlayers(teamId) {
          const detailsDiv = document.getElementById(`details-${teamId}`);

          // Check if details are already visible
          if (detailsDiv.style.display === "none") {
              // Fetch the details only if they are not loaded yet
              fetch(`get_details.php?team_id=${teamId}`)
                  .then(response => response.text())
                  .then(data => {
                      detailsDiv.innerHTML = data;
                      detailsDiv.style.display = "block";
                  })
                  .catch(error => console.error("Error fetching details:", error));
          } else {
              // Hide the details if they are already visible
              detailsDiv.style.display = "none";
          }
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