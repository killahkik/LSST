<?php
session_start();
$userID ="";
if (isset($_SESSION['username'])) {
    $userID = $_SESSION['username'];
} else {
    $loginmessage = "You are not logged in.";
}
// Database connection details
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
//grab id from username and save it
$sql1 = "SELECT * FROM user WHERE username LIKE ?";
$stmt1 = $conn->prepare($sql1);
$searchParam1 = "%" . $userID . "%";
$stmt1->bind_param("s", $searchParam1);
$stmt1->execute();

// Get the result and fetch the matching row
$result = $stmt1->get_result();
if ($result->num_rows > 0) {
    while ($row1 = $result->fetch_assoc()) {
        $userID= $row1['id'];
    }
} else {
    echo "No results found for this user.";
    echo "username is ". $username;
}
// Check if the form data is available
if (isset($_POST['userInput'])) {
    // Retrieve and sanitize the user input
    $userInput = $_POST['userInput'];
    // Prepare and execute a query to search for the string in the 'name' column
    $sql = "SELECT * FROM players WHERE playerName LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchParam = "%" . $userInput . "%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
// Get the result and display the matching rows
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $userID . "<br>";
            echo "Team: " . $row['playerTeam'] . "<br>";
            echo "Name: " . $row['playerName'] . "<br>";
            echo "Number: " . $row['playerNumber'] . "<br>";
            echo "Position: " . $row['playerPosition'] . "<br><br>";
            // Form to send data to another table
            echo "<form action='move_to_table.php' method='POST'>";
            echo "<input type='hidden' name='user_id' value='" . $userID . "'>"; // Include logged-in user's ID
            echo "<input type='hidden' name='playerTeam' value='" . $row['playerTeam'] . "'>";
            echo "<input type='hidden' name='playerName' value='" . $row['playerName'] . "'>";
            echo "<input type='hidden' name='playerNumber' value='" . $row['playerNumber'] . "'>";
            echo "<input type='hidden' name='playerPosition' value='" . $row['playerPosition'] . "'>";

            echo "<button type='submit'>Move to Other Table</button>";
            echo "</form><br>";
        }

    } else {
        echo "No results found for '" . htmlspecialchars($userInput) . "'";
    }

    $stmt->close();
} else {
    echo "Please provide a search term.";
}
$conn->close();
?>