<?php

function getPlayerInfo($name) {
    // URL encode the player name to handle spaces and special characters
    $formattedName = urlencode($name);

    // Initialize cURL
    $curl = curl_init();

    // Set cURL options with the formatted player name
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://tank01-nfl-live-in-game-real-time-statistics-nfl.p.rapidapi.com/getNFLPlayerInfo?playerName=$formattedName&getStats=true",
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


    // Close the cURL session
    curl_close($curl);
    $data = json_decode($response, true);
    echo $data['body'][0]['espnName'] . '<br>';
    // Check for errors and return the result
    if (isset($data['body'][0]['espnName'])) {
        $teamAbv = $data['body'][0]['espnName'];
        echo "Team Abbreviation: " . $teamAbv;
    } else {
        echo "teamAbv key not found in response.";
    }
}

// Example usage:
echo getPlayerInfo("Drake London");
?>