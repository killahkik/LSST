<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "userData";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['team_id'])) {
    $teamId = $_GET['team_id'];

    // Fetch related data from another table (e.g., `user_details`)
    $sql = "SELECT * FROM players WHERE teamID = ?";
    $stmt = $conn->prepare($sql);
    //this grabs from the _get of url into teamId and not confused for sql teamID
    $stmt->bind_param("i", $teamId);
    $stmt->execute();
    $result = $stmt->get_result();
    //checks if there are any number of rows in the teamid for sql then you can grab and access the data inside
    if ($result->num_rows > 0) {
        echo "<ul>";
        //loops to grab every player, number, and position they are in and puts it in a list
        while ($row = $result->fetch_assoc()) {
            echo "<li> Player:<a href=\"playerView.php?playerID=" . $row['playerID'] . "\"> " . $row['espnName'] ."</a>, Number: " . $row['jerseyNum'] .", Position: ". $row['pos'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No additional details found.";
    }

    $stmt->close();
} else {
    echo "Invalid user ID.";
}

$conn->close();
?>

