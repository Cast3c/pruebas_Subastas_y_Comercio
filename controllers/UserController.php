<?php

require_once __DIR__ . "/../config/database.php";

function getUsers($user){
    $conn = $GLOBALS['conn'];

    // Verificar rol o id para permitir listra usuarios
    if($user->role !== 'admin'){
        http_response_code(403);
        echo json_encode(["error" => "Rol no autorizado"]);
        return;
    }

    // Consultamos usuarios en db 
    $stmt = $conn -> prepare("SELECT id, name, email, role  FROM users");

    $stmt -> execute();
    $result = $stmt -> get_result();

    // Agrupamos y preparamos respuesta
    $users = [];

    while($row = $result -> fetch_assoc()){
        $users[] = $row;
    }

    // Devolvemos usuarios
    echo json_encode($users);
}

function createUser($user){

    $conn = $GLOBALS['conn'];

    // Verificar rol para crear usuarios
    if($user->role !== 'admin'){
        http_response_code(403);
        echo json_encode(["error" => "No autorizado para registrar usuarios"]);
        return;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if(!$data) {
        http_response_code(400);
        echo json_encode(["error" => "JSON invalido"]);
        return; 
    }

    $name = htmlspecialchars($data["name"]);
    $email = htmlspecialchars($data["email"]);
    $password = $data["password"];
    $role = isset($data["role"]) ? $data["role"] : "user";

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
    $stmt = $conn-> prepare("SELECT id FROM users WHERE email = ?");
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
    $stmt = $conn-> prepare(
        "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("ssss", $name, $email, $passwordHash, $role);

    if($stmt->execute()){
        echo json_encode(["message"=> "Usuario creado con exito por admin"]);
    }else {
        http_response_code(500);
        echo json_encode(["error" => "Error al registrar el usuario"]);
    }

}

function updateUser($user){
    $conn = $GLOBALS['conn'];

    // Verificar rol para editar
    if($user->role !== "admin"){
        http_response_code(403);
        echo json_encode(["error" => "No autorizado para editar"]);
        return;
    } 

    // Se obtiene el id del usuario desde el query
    if (!isset($_GET['idUser'])) {
        http_response_code(400);
        echo json_encode(["error" => "Es necesario el id del usuario para esta operacion"]);
        return;
    }

    $user_id = (int) $_GET['idUser'];

    // Se lee el body 
    $data = json_decode(file_get_contents("php://input"), true);

    if(!$data){
        http_response_code(400);
        echo json_encode(["error" => "JSON invalido"]);
        return;
    }

    // Validacio basica
    if(empty($data['role'])){
        http_response_code(400);
        echo json_encode(["error" => "El rol es obligatorio"]);
        return; 
    }

    $role = htmlspecialchars($data['role']);

    // Se verifica existencia
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ? ");
    $stmt -> bind_param("i", $user_id);
    $stmt -> execute();

    $result = $stmt -> get_result();
    $result_user  = $result -> fetch_assoc();

    if(!$result_user){
        http_response_code(404);
        echo json_encode(["error" => "Usuario no encontrado"]);
        return;
    }

    // Editar usuario
    $stmt = $conn-> prepare("UPDATE users SET role = ? WHERE id = ? ");

    if(!$stmt){
        http_response_code(500);
        echo json_encode(["error" => "Error en la consulta"]);
        return;
    }

    $stmt -> bind_param("si", $role, $user_id);
    
    if($stmt->execute()){
        echo json_encode(["message" => "Usario actualizado correctamente"]);
    }else{
        http_response_code(500);
        echo json_encode(["error" => "Error al actualizar el usuario"]);
    }

}

function deleteUser($user){
    $conn = $GLOBALS['conn'];

    // Verificar rol
    if ($user->role !== 'admin') {
        http_response_code(403);
        echo json_encode(["error" => "No autorizado para eliminar usuarios"]);
        return;
    }

    // Verificar id del user a eliminar
    if(!isset($_GET['idUser'])){
        http_response_code(400);
        echo json_encode(["error" => "Es necesario el Id del usuario para la operacion"]);
        return;
    }

    $user_id = (int) $_GET['idUser'];

    // Verificar existencia de usuario
    $stmt = $conn -> prepare("SELECT id FROM users WHERE id = ?");
    $stmt -> bind_param("i", $user_id);
    $stmt -> execute();

    $result = $stmt->get_result();
    $del_user = $result->fetch_assoc();
    
    if(!$del_user){
        http_response_code(404);
        echo json_encode(["error" => "Usuario no encontrado"]);
        return;
    }

    // Eliminar usuario
    $stmt = $conn -> prepare("DELETE FROM users WHERE id = ?");
    
    if(!$stmt){
        http_response_code(500);
        echo json_encode(["error" => "Error en el proceso de eliminacion"]);
        return;
    }

    $stmt -> bind_param("i", $user_id);

    if($stmt->execute()){
        echo json_encode(["message" => "Usuario elimando correctamente"]);
    }else{
        http_response_code(500);
        echo json_encode(["error" => "Erorr al intentar eliminar el usuario"]);
    }
}