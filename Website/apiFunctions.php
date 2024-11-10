<?php
// include_once("apiFunctions.php");


// update/insert games for a given week and season into database
function getGames($week, $season){
    $curl = curl_init();
    $seasonType = "reg";

    // API URL with dynamic parameters
    $url = "https://tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com/getNFLGamesForWeek?week=$week&seasonType=$seasonType&season=$season";

    // cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com",
            "x-rapidapi-key: ec84d842dcmshe4d04e0ec4d508ep19ffa1jsn71581cd24e76"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        die("cURL Error #:" . $err);
    }

    $data = json_decode($response, true);

    // Debug: Print the entire response
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";

    // Database connection
    $conn = new mysqli("localhost", "root", "", "userdata");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // insert/update games into database
    $sql = "
        INSERT INTO games (gameID, gameDate, espnID, teamIDHome, gameStatus, gameWeek, teamIDAway, gameTime, season) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            gameDate = VALUES(gameDate),
            espnID = VALUES(espnID),
            teamIDHome = VALUES(teamIDHome),
            gameStatus = VALUES(gameStatus),
            gameWeek = VALUES(gameWeek),
            teamIDAway = VALUES(teamIDAway),
            gameTime = VALUES(gameTime),
            season = VALUES(season)
    ";

    $stmt = $conn->prepare($sql);

    // Loop through the data and bind parameters
    foreach ($data['body'] as $game) {
            $gameID = $game['gameID'];
            $gameDate = $game['gameDate'];
            $espnID = $game['espnID'];
            $teamIDHome = $game['teamIDHome'];
            $gameStatus = $game['gameStatus'];
            $gameWeek = $game['gameWeek'];
            $teamIDAway = $game['teamIDAway'];
            $gameTime = $game['gameTime'];
            $season = $game['season'];

            // Bind parameters and execute
            $stmt->bind_param(
                "sssssisss", 
                $gameID, 
                $gameDate, 
                $espnID, 
                $teamIDHome, 
                $gameStatus, 
                $gameWeek, 
                $teamIDAway, 
                $gameTime, 
                $season
            );
            $stmt->execute();
        }

    //echo "Data inserted/updated successfully.";

    $stmt->close();
    $conn->close();


    // Example usage:
    //getGames(1, 2024);

}

// update/insert players into database (Get Player List)
function getPlayers(){
    $curl = curl_init();

    // cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com/getNFLPlayerList",        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com",
            "x-rapidapi-key: ec84d842dcmshe4d04e0ec4d508ep19ffa1jsn71581cd24e76"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        die("cURL Error #:" . $err);
    }

    $data = json_decode($response, true);

    // Debug: Print the entire response
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";

    // Database connection
    $conn = new mysqli("localhost", "root", "", "userdata");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // insert/update players into database
    $sql = "
        INSERT INTO players (playerID, espnName, weight, height, age, jerseyNum, team, teamID, lastGamePlayed, pos, espnHeadshot) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            playerID = VALUES(playerID),
            espnName = VALUES(espnName),
            weight = VALUES(weight),
            height = VALUES(height),
            age = VALUES(age),
            jerseyNum = VALUES(jerseyNum),
            team = VALUES(team),
            teamID = VALUES(teamID),
            lastGamePlayed = VALUES(lastGamePlayed),
            pos = VALUES(pos),
            espnHeadshot = VALUES(espnHeadshot)
    ";

    $stmt = $conn->prepare($sql);

    foreach ($data['body'] as $player) {
        $playerID = $player['playerID'];
        $espnName = $player['espnName'];
        $weight = $player['weight'];
        $height = $player['height'];
        $age = $player['age'];
        $jerseyNum = $player['jerseyNum'];
        $team = $player['team'];
        $teamID = $player['teamID'];
        $lastGamePlayed = $player['lastGamePlayed'];
        $pos = $player['pos'];
        $espnHeadshot = $player['espnHeadshot'];
    
        // Bind parameters and execute
        $stmt->bind_param(
            "isisiisisss", 
            $playerID, 
            $espnName, 
            $weight, 
            $height, 
            $age, 
            $jerseyNum, 
            $team, 
            $teamID, 
            $lastGamePlayed, 
            $pos, 
            $espnHeadshot
        );
        $stmt->execute();
        }

    //echo "Data inserted/updated successfully.";

    $stmt->close();
    $conn->close();


    // Example usage:
    //getGames(1, 2024);

}


// update/insert teams into database (Get NFL Teams)



// update/insert team scores/ data for a match (Get NFL Game Box Score)



// update/insert player stats (Get Player Information)


// gets player info from database and generates html to display it 
function displayPlayerInfo($playerID){
    // get info for playerID from database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "userdata";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // get player data
    $sql = "SELECT playerID, team, teamID, espnName, jerseyNum, pos, espnHeadshot FROM players WHERE playerID = $playerID";
    $result = $conn->query($sql);

    // check results
    if ($result->num_rows > 0 && isset($_GET['playerID'])) {
        // output
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Player Team</th>
                    <th>Team ID</th>
                    <th>Player Name</th>
                    <th>Player Number</th>
                    <th>Player Position</th>
                </tr>";

        $row = $result->fetch_assoc();
        echo "<tr>
            <td>" . $row["playerID"] . "</td>
            <td>" . $row["team"] . "</td>
            <td>" . $row["teamID"] . "</td>
            <td>" . $row["espnName"] . "</td>
            <td>" . $row["jerseyNum"] . "</td>
            <td>" . $row["pos"] . "</td>
        </tr>";
        

        echo "</table>";

        // show image with $espnHeadshot
        echo "<img src='" . $row["espnHeadshot"] . "' alt='Player Image' style='width: 200px; height: 200px;'>";
    } else {
        echo "0 results";
    }

    $conn->close();
}


?>