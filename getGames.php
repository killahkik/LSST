<?php

function getNFLGamesForWeek($week, $seasonType, $season) {
    // Initialize cURL
    $curl = curl_init();

    // Set the API URL with dynamic parameters
    $url = "https://tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com/getNFLGamesForWeek?week=$week&seasonType=$seasonType&season=$season";

    // Set cURL options
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
    // Execute cURL request and fetch response
    $response = curl_exec($curl);

    curl_close($curl);

    $data = json_decode($response, true);
    echo $data['body'][0]['gameID'] . '<br>';
    if (isset($data['body'][0]['gameID'])) {
        $teamAbv = $data['body'][0]['gameID'];
        echo "Team Abbreviation: " . $teamAbv;
    } else {
        echo "teamAbv key not found in response.";
    }
}

// Example usage:
echo getNFLGamesForWeek(1, "reg", 2024);
?>
