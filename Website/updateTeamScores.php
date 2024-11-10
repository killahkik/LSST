<?php
function updateNFLTeams() {
    // Initialize cURL
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com/getNFLTeams?sortBy=standings&rosters=false&schedules=false&topPerformers=true&teamStats=true&teamStatsSeason=2023",
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

    // Execute cURL request and fetch response
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $data = json_decode($response, true);
    // Close the cURL session
    curl_close($curl);
    $conn = new mysqli("localhost", "root", "", "userData");

    $sql = "UPDATE teams SET wins = ?, loses = ? WHERE teamName = ?";
    $stmt = $conn->prepare($sql);
    for($i=0; $i <32; $i++){
        $teamNameSql = $data['body'][$i]['teamCity'] . " " . $data['body'][$i]['teamName'];
        $teamLosses = $data['body'][$i]['loss'];
        $teamWins = $data['body'][$i]['wins'];
        $stmt->bind_param("iis",$teamWins,$teamLosses,$teamNameSql);
        $stmt->execute();
        if ($stmt->execute()) {
            //echo "team scores updated!";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }
    //this is to see the entry fields in the array its stored in
    $stmt->close();
    $conn->close();
    /*
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    */
    //echo
    // Check for errors and return the result
    /*
    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
    */
    //echo $data['body'][0]['teamAbv'];
}
//echo updateNFLTeams();
// Example usage^^

function updateTeamsForLogo(){
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com/getNFLTeams?sortBy=standings&rosters=false&schedules=false&topPerformers=true&teamStats=true&teamStatsSeason=2023",
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
    //$err = curl_error($curl);
    $data = json_decode($response, true);
    curl_close($curl);
    $conn = new mysqli("localhost", "root", "", "userData");
    $table= 'teams';
    $column = 'logo';
    $dbname = "userData";
    $sql = "SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = ? 
        AND TABLE_NAME = ? 
        AND COLUMN_NAME = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $dbname, $table, $column);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        //debugging line to check if column exists or not for visuals
        //echo "Column already exists";
        $sql = "UPDATE teams SET logo = ? WHERE teamName = ?";
        $stmt = $conn->prepare($sql);
        for($i=0; $i <32; $i++) {
            $teamNameSql = $data['body'][$i]['teamCity'] . " " . $data['body'][$i]['teamName'];
            $logoTeam = $data['body'][$i]['espnLogo1'];
            $stmt->bind_param("ss", $logoTeam, $teamNameSql);
            $stmt->execute();
            if ($stmt->execute()) {
                // debug to check if logo team works by display logo in this php section of commented out you wont have any issues
                //echo "<img src='" . $logoTeam . "' alt='Team logo' style='width: 200px; height: 200px;'>";
            } else {
                echo "Error updating record: " . $stmt->error;
            }
        }
    } else {

        $addColumnSql = "ALTER TABLE teams ADD COLUMN logo VARCHAR(200)";
        if ($conn->query($addColumnSql) === TRUE) {
            echo "Column logo added successfully to the table teams.";
            $sql = "UPDATE teams SET logo = ? WHERE teamName = ?";
            $stmt = $conn->prepare($sql);
            for($i=0; $i <32; $i++) {
                $teamNameSql = $data['body'][$i]['teamCity'] . " " . $data['body'][$i]['teamName'];
                $logoTeam = $data['body'][$i]['espnLogo1'];
                $stmt->bind_param("ss", $logoTeam, $teamNameSql);
                $stmt->execute();
                if ($stmt->execute()) {
                    // debug to check if logo team works by display logo in this php section of commented out you wont have any issues
                    //echo "<img src='" . $logoTeam . "' alt='Team logo' style='width: 200px; height: 200px;'>";
                } else {
                    echo "Error updating record: " . $stmt->error;
                }
            }
        }


    else {
            echo "Error adding column: " . $conn->error;
        }

    }
    $stmt->close();
    $conn->close();

}
// echo updateTeamsForLogo();
// Example usage^^

?>
