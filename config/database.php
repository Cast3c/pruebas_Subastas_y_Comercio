<?php
$host = "localhost";
$user = "u744129407_ricapa";
$password = "861019rCp+";
$database = "u744129407_tasks";

$conn = new mysqli($host, $user, $password, $database);

if($conn->connect_error){
    die(json_encode(["error" => "DB connection failed: " . $conn->connect_error ]));
}