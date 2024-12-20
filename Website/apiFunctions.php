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
                "sssssssss", 
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
    updateTeamNames();
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

    // database connection
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
        $playerID = @$player['playerID'];
        $espnName = @$player['espnName'];
        $weight = @$player['weight'];
        $height = @$player['height'];
        $age = @$player['age'];
        $jerseyNum = @$player['jerseyNum'];
        $team = @$player['team'];
        $teamID = @$player['teamID'];
        $lastGamePlayed = @$player['lastGamePlayed'];
        $pos = @$player['pos'];
        $espnHeadshot = @$player['espnHeadshot'];
    
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

function addBettingToTables(){
    $conn = new mysqli("localhost", "root", "", "userdata");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "CREATE TABLE BETTING ( gameID VARCHAR(255) PRIMARY KEY, userName VARCHAR(255), homeBet VARCHAR(50), awayBet VARCHAR(50),gameStatus VARCHAR(50));";
    $stmt1 = $conn->prepare($sql);
    $stmt1->execute();

    $sql = "ALTER TABLE userdata.`user` ADD money ";
    $stmt1 = $conn->prepare($sql);
    $stmt1->execute();

    $sql = "update `user` set money =200 ";
    $stmt1 = $conn->prepare($sql);
    $stmt1->execute();

}
//addBettingToTables();
// update/insert team scores/ data for a match (Get NFL Game Box Score)
function updateGameInfo($game_ID) {
        $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com/getNFLBoxScore?gameID=$game_ID",
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
        echo "cURL Error #:" . $err;
    }

    $data = json_decode($response, true);
    $body = $data['body'];
    // Debug: print all data
    //print_r($data);

    // database connection
    $conn = new mysqli("localhost", "root", "", "userdata");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // insert/update game info into database
    $sql = "
        INSERT INTO games (gameID, gameStatus, teamStats, scoringPlays, homeResult, awayResult, teamIDHome, teamIDAway, gameLocation, arena, homePts, awayPts, currentPeriod) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            gameID = VALUES(gameID),
            gameStatus = VALUES(gameStatus),
            teamStats = VALUES(teamStats),
            scoringPlays = VALUES(scoringPlays),
            homeResult = VALUES(homeResult),
            awayResult = VALUES(awayResult),
            teamIDHome = VALUES(teamIDHome),
            teamIDAway = VALUES(teamIDAway),
            gameLocation = VALUES(gameLocation),
            arena = VALUES(arena),
            homePts = VALUES(homePts),
            awayPts = VALUES(awayPts),
            currentPeriod = VALUES(currentPeriod)
    ";

    $stmt = $conn->prepare($sql);

    if (!isset($body["gameStatus"])) {
        $stmt->close();
        $conn->close();
        return;
    }
    if ($body['gameStatus'] == "Scheduled") {
        $stmt->close();
        $conn->close();
        return;
    }
    $gameID = $body['gameID'];
    $gameStatus = $body['gameStatus'];
    $teamStats = json_encode($body['teamStats']);
    $scoringPlays = json_encode($body['scoringPlays']);
    $homeResult = $body['homeResult'];
    $awayResult = $body['awayResult'];
    $teamIDHome = $body['teamIDHome'];
    $teamIDAway = $body['teamIDAway'];
    $gameLocation = $body['gameLocation'];
    $arena = $body['arena'];
    $homePts = $body['homePts'];
    $awayPts = $body['awayPts'];
    $currentPeriod = $body['currentPeriod'];




    // Bind parameters and execute
    $stmt->bind_param(
        "ssssssssssiis", 
        $gameID, 
        $gameStatus,
        $teamStats,
        $scoringPlays,
        $homeResult,
        $awayResult,
        $teamIDHome,
        $teamIDAway,
        $gameLocation,
        $arena,
        $homePts,
        $awayPts,
        $currentPeriod
    );
    $stmt->execute();
    

    //echo "Data inserted/updated successfully.";

    $stmt->close();
    $conn->close();


    // Example usage:
    //getGames(1, 2024);

}



// update/insert player stats (Get Player Information), use this for player view for detailed info


// gets player info from database and generates html to display it 
//TODO: remove this func and have the page use SQL data instead of generating HTML
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

// update team names from teams table into games table
function updateTeamNames() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "userdata";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "
        UPDATE games
        JOIN teams AS homeTeam ON games.teamIDHome = homeTeam.teamID
        JOIN teams AS awayTeam ON games.teamIDAway = awayTeam.teamID
        SET games.homeName = homeTeam.teamName,
            games.awayName = awayTeam.teamName
    ";
    $result = $conn->query($sql);

    $conn->close();

}

function getVenueForUpcoming( $gameID ) {
    // use the gameID to get the venue from api
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com/getNFLGameInfo?gameID=$gameID",
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
        echo "cURL Error #:" . $err;
    }

    $data = json_decode($response, true);
    $body = $data['body'];
    $venue = $body['venue'];
    return $venue;
}

function locationToCoordinates($location) {
    $location = urlencode($location);
    $url = "https://api.opencagedata.com/geocode/v1/json?q=$location&key=24c6a206f91d4ca6859276a29424ce65";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($result, true);
    $lat = $data['results'][0]['geometry']['lat'];
    $lng = $data['results'][0]['geometry']['lng'];
    return [$lat, $lng];
}
    
function weatherConditions($location){
    $coordinates = locationToCoordinates($location);
    $latitude = $coordinates[0];
    $longitude = $coordinates[1];
    $url = "https://api.open-meteo.com/v1/forecast?current=temperature_2m,relative_humidity_2m,precipitation,weather_code&temperature_unit=fahrenheit&wind_speed_unit=mph&precipitation_unit=inch&timezone=auto&forecast_days=1&latitude=$latitude&longitude=$longitude";
    //print_r($url);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($result, true);
    $temp = $data["current"]["temperature_2m"];
    $relHumidity = $data["current"]["relative_humidity_2m"];
    $precipitation = $data["current"]["precipitation"];
    return [$temp, $relHumidity, $precipitation];
}
?>