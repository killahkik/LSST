<?php
$servername = "localhost";
$username = "root";      // Your database username
$password = "";          // Your database password
$dbname = "userdata";  // Your database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the necessary data is received
if (isset($_POST['user_id']) && isset($_POST['playerTeam']) && isset($_POST['playerName']) && isset($_POST['playerNumber']) && isset($_POST['playerPosition'])) {
    $id = $_POST['user_id'];
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
        $stmt->close();
        header("Location: searchPlayers.php?status=success");
        exit;
    } else {
        // Error occurred - Redirect with error message
        $stmt->close();
        header("Location: searchPlayers.php?status=error");
        exit;
    }
} else {
    echo "Invalid data.";
    // show which data failed
    if (!isset($_POST['user_id'])) {
        echo "user_id is not set.";
    } 
    if (!isset($_POST["playerTeam"])) {
        echo "playerTeam is not set.";
    }
    if (!isset($_POST["playerName"])) {
        echo "playerName is not set.";
    }
    if (!isset($_POST["playerNumber"])) {
        echo "playerNumber is not set.";
    }
    if (!isset($_POST["playerPosition"])) {
        echo "playerPosition is not set.";
    }
}

$conn->close();
?>