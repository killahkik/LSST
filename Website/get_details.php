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
    $sql = "SELECT * FROM players WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $teamId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li> Player: " . $row['playerName'] .", Number: " . $row['playerNumber'] .", Position: ". $row['playerPosition'] . "</li>";
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

