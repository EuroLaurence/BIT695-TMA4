<?php

// Outputs players table as JSON object.
include "connect.php";

if ($connection->connect_error) {
    die("Connection failed");
}

$sql = "SELECT * FROM players";

    $result = $connection->query($sql);
    $players = array();

    while($row = $result->fetch_assoc()) {
        array_push($players, $row);
    }
   
    $output = json_encode($players);
    echo $output;
    
    $connection->close();
?>