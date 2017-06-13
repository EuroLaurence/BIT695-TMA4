<?php
// Outputs event_schedule table as JSON object.

include "connect.php";

if ($connection->connect_error) {
    die("Connection failed");
}

$sql = "SELECT * FROM event_schedule";

    $result = $connection->query($sql);
    $events = array();

    while($row = $result->fetch_assoc()) {
        array_push($events, $row);
    }
   
    $output = json_encode($events);
    echo $output;
    
    $connection->close();
?>