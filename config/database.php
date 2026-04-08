<?php
// HOSTINGER
$host = "localhost";
$user = "u744129407_ricapa";
$password = "861019rCp+";
$database = "u744129407_tasks";

// // LOCAL
// $db_host = "localhost";
// $db_user = "root";
// $db_password = "";
// $db_database = "task_manager";

$conn = new mysqli($db_host, $db_user, $db_password, $db_database);

if($conn->connect_error){
    http_response_code(500);
    die("DB connection failed");
}