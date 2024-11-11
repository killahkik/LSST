<?php
session_start();
$loginmessage = "";
$usersName = "";
if (isset($_SESSION['username'])) {
    $loginmessage = "Logged in as: " . $_SESSION['username'] . ". <a href='logout.php'>Logout</a>";
    $usersName = $_SESSION['username'];
} else {
    $loginmessage = "You are not logged in.";
    $_SESSION['username'] = null;
    $usersName = null;
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
            <h2>Top 5 Performing Teams</h2>
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
            $sql = "SELECT * FROM teams";
            $result = $conn->query($sql);
            $rows=[];
            if ($result->num_rows > 0) {
                //echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    $difference= abs($row['wins']-$row['loses']);
                    $row['difference'] = $difference;
                    $rows[] = $row;
                    //echo "<li>" . htmlspecialchars($row['team_name']) . " - Popularity: " . htmlspecialchars($row['weekly_popularity']) . "</li>";
                }
                //debugging code to check if array was properly filled
                /*
                echo "<pre>";
                print_r($rows);
                echo "</pre>";
                */
                //echo "</ul>";
            }

            // Sort rows by 'difference' in descending order
            usort($rows,function ($w, $l){
                if($w['difference'] == $l['difference']){
                    return 0;
                }
                return ($w['difference'] > $l['difference']) ? -1 : 1;
            });
            $top5 = array_slice($rows, 0, 5);
            foreach ($top5 as $index => $row) {
                echo "Rank " . ($index + 1) . ": " . $row['teamName'] . ", wins = " . $row['wins'] . ", losses = " . $row['loses'] . "<br><br>";
            }

            // Close the database connection
            $conn->close();
            ?>
        </div>

        <div class="followedPlayers">
            <h2> <?php echo $usersName?> Followed Player List:</h2>
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
                    //echo "ID: " . $userID . "<br>";
                    $playerID = $row['playerName'];
                    echo "Team: " . $row['playerTeam'] . "<br>";
                    echo "Name: " . $row['playerName'] . "<br>";
                    echo "Number: " . $row['playerNumber'] . "<br>";
                    echo "Position: " . $row['playerPosition'] . "<br><br>";
                }

            }else {
                if ($_SESSION['username'] != null){
                echo "No Followed players for User: '" . htmlspecialchars($userID) . "'";
                } else {
                    echo "Please login to view followed players.";
                }
            }
            $stmt-> close();
            $conn->close();
            ?>

        </div>

    </body>
    </html>
