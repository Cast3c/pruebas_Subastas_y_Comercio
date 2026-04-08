<?php
$conn = new mysqli("localhost", "root", "", "task_manager");

if($conn->connect_error){
    die(json_encode(["error" => "DB connection failed" ]));
}