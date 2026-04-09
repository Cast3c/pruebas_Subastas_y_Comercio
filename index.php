<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Habilitar CORS para conexiones externas
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obtener URI
$request  = $_SERVER['REQUEST_URI'];

// Quitar query params
$request = strtok($request, '?');

// Limpiar index.php 
$request = str_replace("/index.php", "", $request);

// Limpiar ruta final
$route = trim($request, "/");

$method = $_SERVER['REQUEST_METHOD'];

//DEBUG
// var_dump( $route, $method); 
// die();

require_once __DIR__ . "/routes/api.php";