<?php

require_once __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/../config/jwt.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$headers = getallheaders();


if(!isset($headers['Authorization'])){
    http_response_code(401);
    echo json_encode(["error" => "Token requerido"]);
    exit();
}

$token = str_replace("Bearer ", "", $headers["Authorization"]);

try {
    $decoded = JWT::decode($token, new Key($config['secret_key'], 'HS256'));

    if($decoded->iss !== $config['issuer'] || $decoded->aud !== $config['audience']){
        http_response_code(401);
        echo json_encode(['error'=> "Token invalido"]);
        exit();
    }

    //Guarda datos de usuario 
    $user = $decoded->data;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Token invalido o expirado"]);
    exit();
}