<?php
include_once("apiFunctions.php");
session_start();
$loginmessage = "";
if (isset($_SESSION['username'])) {
    $loginmessage = "Logged in as: " . $_SESSION['username'] . ". <a href='logout.php'>Logout</a>";
} else {
    $loginmessage = "You are not logged in.";
}

// check for game id in URL
if (isset($_GET['game_ID'])) {
    $game_ID = $_GET['game_ID'];
    //print_r($game)ID);
    updateGameInfo($game_ID);
} else {
    // If no game_ID is set in the URL
    echo "<p>No page ID provided.</p>";
    $game_ID = null;
}

?>

<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="stylesheet.css">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Game View</title>
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
            <?php    
                $conn = new mysqli("localhost", "root", "", "userdata");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // get game data
                $sql = "SELECT gameDate, teamIDHome, gameStatus, gameWeek, teamIDAway, gameTime, season, teamStats, scoringPlays, homeResult, awayResult, gameLocation, arena, homePts, awayPts, currentPeriod, homeName, awayName FROM games WHERE gameID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $game_ID);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                // check results
                if ($result->num_rows > 0 && isset($_GET['game_ID'])) {
                    
                    if ($row["gameStatus"] == "Scheduled" || $row["gameStatus"] == null) {
                        echo "<div class = 'gamebox'>
                        <h1>Game has not started yet.</h1>";
                        echo "<h1>" . $row['homeName'] . " vs " . $row['awayName'] . "</h1>";
                        echo "</div>";
                        echo "<div class = 'gamebox'>";
                        echo "<h3>Current Weather</h3>";
                        $venue = getVenueForUpcoming($game_ID);
                        $weather = weatherConditions($venue);
                        echo "<h3>Temperature: " . $weather[0] . "°F</h3>";
                        echo "<h3>Rel. Humidity: ". $weather[1] ." %</h3>";
                        echo "<h3>Precipitation: ". $weather[2] ." Inches</h3>";
                        echo "</div>";
                        echo "<div class = 'gamebox'>";
                        echo "<h3>Game Date: " . date('Y-m-d', strtotime($row['gameDate'])) . "," . $row['gameTime'] . "</h3>";
                        echo "<h3>Location: " . $venue . "</h3>";
                        echo "<h3>Game Status: " . $row['gameStatus'] . "</h3>";
                        echo "<h3>Game Week: " . $row['gameWeek'] . "</h3>";
                        echo "<h3>Season: " . $row['season'] . "</h3>";
                        echo "</div>";
                        
                    } else {
                        echo "<div class = 'gamebox'>";
                            if ($row['gameStatus'] == "Final" || $row['gameStatus'] == "Completed" || $row['gameStatus'] == "Final/OT" || $row['gameStatus'] == "Final/SO") {
                                echo "<h1>Game has ended.</h1>";
                            }
                        // display data - names, date, time, location, score, stats, scoring plays
                        echo "<h1>" . $row['homeName'] . " vs " . $row['awayName'] . "</h1>";
                        echo "<h2>" . $row['homePts'] .  "-" . $row['awayPts'] . "</h2>";
                        // if game finished
                        if ($row['gameStatus'] == "Final" || $row['gameStatus'] == "Completed" || $row['gameStatus'] == "Final/OT" || $row['gameStatus'] == "Final/SO") {
                            echo "<h2>" . $row['homeResult'] . " - " . $row['awayResult'] . "</h2>";
                        }
                        echo "</div>";
                        echo "<div class = 'gamebox'>";
                        echo "<h3>Game Date: " . date('Y-m-d', strtotime($row['gameDate'])) . "," . $row['gameTime'] . "</h3>";
                        echo "<h3>Location: " . $row['gameLocation'] . ", " . $row['arena'] . "</h3>";
                        echo "<h3>Game Status: " . $row['gameStatus'] . "</h3>";
                        echo "<h3>Game Week: " . $row['gameWeek'] . "</h3>";
                        echo "<h3>Season: " . $row['season'] . "</h3>";
                        if ($row["currentPeriod"] != "Final") {
                            echo "<h3>Current Period: " . $row['currentPeriod'] . "</h3>";
                        }
                        echo "</div>";
                        echo "<div class = 'gamebox'>";
                        echo "<h3>Current Weather</h3>";
                        $weather = weatherConditions($row['gameLocation']);
                        echo "<h3>Temperature: " . $weather[0] . "°F</h3>";
                        echo "<h3>Rel. Humidity: ". $weather[1] ." %</h3>";
                        echo "<h3>Precipitation: ". $weather[2] ." Inches</h3>";
                        echo "</div>";
                        // team stats
                        $stats = json_decode($row['teamStats'], true);
                        $awayTeamStats = $stats['away'];
                        $homeTeamStats = $stats['home'];
                        echo "<div class = 'gamebox'>";
                        echo "<table border='1'>";
                        echo "<tr><th>Team</th><th>Total Yards</th><th>Penalties</th><th>Possession</th><th>Total Plays</th></tr>";
                        echo "<tr>";
                        echo "<td>" . $row['homeName'] . "</td>";
                        echo "<td>" . $homeTeamStats['totalYards'] . "</td>";
                        echo "<td>" . $homeTeamStats['penalties'] . "</td>";
                        echo "<td>" . $homeTeamStats['possession'] . "</td>";
                        echo "<td>". $awayTeamStats['totalPlays'] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row['awayName'] . "</td>";
                        echo "<td>" . $awayTeamStats['totalYards'] . "</td>";
                        echo "<td>" . $awayTeamStats['penalties'] . "</td>";
                        echo "<td>" . $awayTeamStats['possession'] . "</td>";
                        echo "<td>". $homeTeamStats['totalPlays'] . "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                        $scoringPlays = json_decode($row['scoringPlays'], true);

                        echo "<div class = 'gamebox'>";
                        echo "<h3>Home Points: " . $row['homePts'] . "</h3>";
                        echo "<h3>Away Points: " . $row['awayPts'] . "</h3>";
                        echo "<h3>Scoring Plays:</h3>";
                        foreach ($scoringPlays as $play) {
                            echo "<div class='scoring-play' style='margin-bottom: 15px; padding: 10px; border: 1px solid;'>";
                            echo "<strong>Team:</strong> " . $play['team'] . "<br>";
                            echo "<strong>Score:</strong> " . $play['score'] . "<br>";
                            echo "<strong>Period:</strong> " . $play['scorePeriod'] . " | ";
                            echo "<strong>Time:</strong> " . $play['scoreTime'] . "<br>";
                            echo "<strong>Home Score:</strong> " . $play['homeScore'] . " | ";
                            echo "<strong>Away Score:</strong> " . $play['awayScore'] . "<br>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                }
            ?>
        </div>
    </body>
</html>