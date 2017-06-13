<?php
// Outputs board_games table as a JSON object.

include "connect.php";

if ($connection->connect_error) {
    die("Connection failed");
}

$sql = "SELECT game FROM board_games";

    $result = $connection->query($sql);
    $games = array();

    while($row = $result->fetch_assoc()) {
        array_push($games, $row);
    }
   
    $output = json_encode($games);
    echo $output;
    
    $connection->close();
?>