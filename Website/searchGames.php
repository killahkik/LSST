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

  <h1>Game Search</h1>
  <h2>2024 Season</h2>


  <form id="inputForm" action="searchGames.php" method="GET">
    <label for="search">Search for games by team:</label>
    <input type="text" id="search" name="search" required>
    <button type="submit">Search</button>
  </form>

  <div id="result">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "userdata";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    // if not set, show no results
    if (empty($searchTerm)) {
        exit;
    }

    // sql query
    $sql = "SELECT gameID, gameDate, homeName, awayName, homePts, awayPts FROM games WHERE homeName LIKE ? OR awayName LIKE ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $searchTerm = "%$searchTerm%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        // display results
        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Game ID</th><th>Game Date</th><th>Home Team</th><th>Away Team</th><th>Game Page</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['gameID'] . "</td>";
                echo "<td>" . $row['gameDate'] . "</td>";
                echo "<td>" . $row['homeName'] . "</td>";
                echo "<td>" . $row['awayName'] . "</td>";
                echo "<td>" . "<a href='gameView.php?game_ID=" . $row['gameID'] . "'>" . "View Game" . "</a>" . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No results found.";
        }

        $stmt->close();
    } else {
        echo "SQL error: " . $conn->error;
    }

    $conn->close();
    ?>
  </div>
</body>
</html>
