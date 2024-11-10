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

    /* SQL to create the 'games' table in the 'userdata' database:
    CREATE TABLE games (
        gameID VARCHAR(255) PRIMARY KEY,
        gameDate DATETIME,
        espnID VARCHAR(255),
        teamIDHome VARCHAR(255),
        gameStatus VARCHAR(50),
        gameWeek INT,
        teamIDAway VARCHAR(255),
        gameTime TIME,
        season VARCHAR(50)
    );
*/
}

// update/insert players for a given team into database (Get NFL Team Roster)



// update/insert teams into database (Get NFL Teams)



// update/insert team scores/ data for a match (Get NFL Game Box Score)



// update/insert player stats (Get Player Information)





?>