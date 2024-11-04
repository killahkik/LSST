<?php
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

// Check if the form data is available
if (isset($_POST['userInput'])) {
    // Retrieve and sanitize the user input
    $userInput = $_POST['userInput'];
    // Prepare and execute a query to search for the string in the 'name' column
    $sql = "SELECT * FROM teams WHERE teamName LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchParam = "%" . $userInput . "%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
// Get the result and display the matching rows
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $teamId = $row['teamId'];
            echo "Team: " . $row['teamName'] . "<br>";
            echo "Wins: " . $row['wins'] . "<br>";
            echo "Loses: " . $row['loses'] . "<br>";
            echo "<button onclick='loadPlayers($teamId)'>Show Details</button>";
            echo "<div id='details-$teamId' style='display: none;'></div>";  // Placeholder for extra details
            echo "</div><br>";

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