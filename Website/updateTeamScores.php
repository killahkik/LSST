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
        echo $teamNameSql . " " . $teamLosses . " " . $teamWins . "<br>";
        if ($stmt->execute()) {
            echo "Record updated successfully.";
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

// Example usage:
echo updateNFLTeams();
?>
