<?php
$servername = "localhost";
$database = "bit695_tma4";
$username = "root";
$password = "root";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed");
}
?>