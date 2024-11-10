<?php
session_start();
$loginmessage = "";
if (isset($_SESSION['username'])) {
    $loginmessage = "Logged in as: " . $_SESSION['username'] . ". <a href='logout.php'>Logout</a>";
} else {
    $loginmessage = "You are not logged in.";
    $_SESSION['username'] = null;
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
        <!-- Top 5 Trending Teams Part-->
        <div class="trending-teams">
            <h2>Top 5 Trending Teams This Week</h2>
            <?php
            // Database connection details
            $servername = "localhost";
            $username = "root";      // Your database username
            $password = "";          // Your database password
            $dbname = "userData";    // Your database name
            // Connect to the database
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // Query to get the top 5 teams based on weekly popularity
            $sql = "SELECT team_name, weekly_popularity FROM teams ORDER BY weekly_popularity DESC LIMIT 5";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['team_name']) . " - Popularity: " . htmlspecialchars($row['weekly_popularity']) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "No trending teams available.";
            }

            // Close the database connection
            $conn->close();
            ?>
        </div>
        <div class="followedPlayers">
            <?php
            // Database connection details
            $servername = "localhost";
            $username = "root";      // Your database username
            $password = "";          // Your database password
            $dbname = "userData";  // Your database name

            // Connect to the database
            $conn = new mysqli($servername, $username, $password, $dbname);
            $userID= $_SESSION['username'];
            $sql1 = "SELECT * FROM user WHERE username LIKE ?";
            $stmt1 = $conn->prepare($sql1);
            $searchParam1 = "%" . $userID . "%";
            $stmt1->bind_param("s", $searchParam1);
            $stmt1->execute();
            $result = $stmt1->get_result();
            if ($result->num_rows > 0) {
                while ($row1 = $result->fetch_assoc()) {
                    $userID= $row1['id'];
                }
            }
            $stmt1->close();
            $sql = "SELECT * FROM followedPlayers WHERE id LIKE ?";
            $stmt = $conn->prepare($sql);
            $searchParam = "%" . $userID . "%";
            $stmt->bind_param("s", $searchParam);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "ID: " . $userID . "<br>";
                    echo "Team: " . $row['playerTeam'] . "<br>";
                    echo "Name: " . $row['playerName'] . "<br>";
                    echo "Number: " . $row['playerNumber'] . "<br>";
                    echo "Position: " . $row['playerPosition'] . "<br><br>";
                }

            }else {
                echo "No results found for '" . htmlspecialchars($userID) . "'";
            }
            $stmt-> close();
            $conn->close();
            ?>
        </div>

    </body>
    </html>
