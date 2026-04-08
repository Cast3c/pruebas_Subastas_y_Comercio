<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function login(){
    // echo "Test";
    // exit();

    session_start();

    $conn = $GLOBALS['conn'];

    $config = require __DIR__ . "/../config/jwt.php";
    $secret_key = $config['secret_key'];
    $issuer = $config['issuer'];
    $audience = $config['audience'];
    
    $data = json_decode(file_get_contents("php://input"), true);

    $email = $data["email"];
    $password = $data["password"];
    
    // Validacion de inputs
    if(empty($email) || empty($password)){
        http_response_code(400);
        echo json_encode(["error" => "Email y password son obligatorios"]);
        return;
    }

    $stmt = $conn-> prepare("SELECT * FROM users WHERE email = ? ");
    $stmt-> bind_param("s", $email);
    $stmt-> execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user && password_verify($password, $user['password'])){
        $payload = [
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => time(),
            "exp" => time() + 3600,
            "data" => [
                "id" => $user['id'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        echo json_encode([
            "message" => "Login exitoso",
            "token" => $jwt
        ]);
        exit();
    }else {
        http_response_code(401);
        echo json_encode(["error" => "Credenciales invalidas"]);
    }
}

function register(){

    $conn = $GLOBALS['conn'];

    $data = json_decode(file_get_contents("php://input"), true);

    if(!$data) {
        http_response_code(400);
        echo json_encode(["error" => "JSON invalido"]);
        return; 
    }

    $name = htmlspecialchars($data["name"]);
    $email = htmlspecialchars($data["email"]);
    $password = $data["password"];
    $role = "user";

    // Validacion de datos basicos
    if(empty($name) || empty($email) || empty($password)){
        http_response_code(400);
        echo json_encode(["error"=> "Todos los campos son obligatorios"]);
        return;
    }

    // Validar rol permitido
    if(!in_array($role, ["user", "admin"])){
        http_response_code(400);
        echo json_encode(["error" => "Rol invalido"]);
        return;
    }

    //Validar si el email ya esta registrado
    $stmt = $conn-> prepare("SELECT * FROM users WHERE email = ?");
    $stmt-> bind_param("s", $email);
    $stmt-> execute();
    $result = $stmt->get_result();

    if($result-> num_rows > 0){
        http_response_code(400);
        echo json_encode(["error" => "Este correo ya esta registrado"]);
        return;
    }

    // <!-- Hash de la contraseña -->
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    //Insertar el usuario
    $stmt = $GLOBALS['conn']-> prepare(
        "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("ssss", $name, $email, $passwordHash, $role);

    if($stmt->execute()){
        echo json_encode(["message"=> "Usuario registrado con exito en la db"]);
    }else {
        http_response_code(500);
        echo json_encode(["error" => "Error al registrar el usuario"]);
    }

}

