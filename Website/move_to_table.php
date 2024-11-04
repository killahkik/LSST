<?php
$servername = "localhost";
$username = "root";      // Your database username
$password = "";          // Your database password
$dbname = "userData";  // Your database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the necessary data is received
if (isset($_POST['id']) && isset($_POST['playerTeam']) && isset($_POST['playerName']) && isset($_POST['playerNumber']) && isset($_POST['playerPosition'])) {
    $id = $_POST['id'];
    $playerName = $_POST['playerTeam'];
    $playerTeam = $_POST['playerName'];
    $playerNumber = $_POST['playerNumber'];
    $playerPosition = $_POST['playerPosition'];
    // Insert into the different table (e.g., `archived_users`)
    $sql = "INSERT INTO followedplayers (id, playerTeam, playerName, playerNumber, playerPosition) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issis",$id, $playerName, $playerTeam, $playerNumber, $playerPosition);

    if ($stmt->execute()) {
        // Record moved successfully - Redirect with success message
        header("Location: searchPlayers.php?status=success");
        exit;
    } else {
        // Error occurred - Redirect with error message
        header("Location: searchPlayers.php?status=error");
        exit;
    }

    $stmt->close();
} else {
    echo "Invalid data.";
}

$conn->close();
?>