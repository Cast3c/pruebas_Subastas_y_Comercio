<?php
require_once "../config/database.php";

function getTasks($user){
    $conn  = $GLOBALS['conn'];

    //Accesos diferenciados por rol de user
    if($user->role === 'admin'){
        $stmt = $conn->prepare("SELECT * FROM tasks");

    } else {
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
        $stmt->bind_param("i", $user->id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];

    while($row = $result-> fetch_assoc()){
        $tasks[] = $row;
    }

    echo json_encode($tasks);
}

function createTask($user){
    $conn = $GLOBALS['conn'];

    $data = json_decode(file_get_contents("php://input"), true);

    if(!$data){
        http_response_code(400);
        echo json_encode(["error" => "JSON invalido"]);
        return;
    }

    if(empty($data['title'])){
        http_response_code(400);
        echo json_encode(["error" => "El titulo es obligatorio"]);
        return;
    }

    $title = htmlspecialchars($data['title']);
    $description = isset($data['description'])
        ? htmlspecialchars($data['title'])
        : "";
    $user_id = $user->id;

    $stmt = $conn->prepare("INSERT INTO tasks (title,description, user_id) VALUES(?,?,?)");

    $stmt->bind_param("ssi", $title, $description, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Tarea creada correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al crear la tarea"]);
    }
    
}

function updateTask($user){
    $conn  =$GLOBALS['conn'];

    // Se obtiene el id ed la task desde query params
    if(!isset($_GET['id'])){
        http_response_code(400);
        echo json_encode(["error" => "Es necesario el ID de la tarea para la operacion"]);
        return;
    }

    $task_id = (int) $_GET['id'];

    // Se lee el body
    $data = json_decode(file_get_contents("php://input"), true);

    if(!$data){
        http_response_code(400);
        echo json_encode(["error" => "JSON invalido"]);
        return;
    }

    // Validacion basica
    if(empty($data['title'])){
        http_response_code(400);
        echo json_encode(["error" => "El titulo es obligatorio"]);
        return;
    }

    $title  = htmlspecialchars($data['title']);
    $description  = isset($data['description'])
        ? htmlspecialchars($data['description'])
        : "";

    // Verificar pertenencia 
    $stmt = $conn->prepare("SELECT user_id FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if(!$task){
        http_response_code(404);
        echo json_encode(["error" => "Tarea no encontrada"]);
        return;
    }

    // Verificar rol o id para permitir edicion
    if($user->role !== 'admin' && $task['user_id'] != $user->id){
        http_response_code(403);
        echo json_encode(["error" => "No autorizado"]);
        return;
    }

    // Edicion de tarea
    $stmt  = $conn->prepare("UPDATE tasks SET title = ?, description  = ? WHERE id = ?");

    if(!$stmt){
        http_response_code(500);
        echo json_encode(["error" => "Error en la consulta"]);
        return;
    }

    $stmt->bind_param("ssi", $title, $description, $task_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Tarea actualizada correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al actualizar la tarea"]);
    }
    
}

function deleteTask($user){
    $conn = $GLOBALS['conn'];

    // Se obtiene el id de la task desde query params
    if(!isset($_GET['id'])){
        http_response_code(400);
        echo json_encode(["error" => "Es necesario el ID de la tarea para la operacion"]);
        return;
    }

    $task_id = (int) $_GET['id'];

    // Verificar pertenencia
    $stmt  = $conn->prepare("SELECT user_id FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if (!$task) {
        http_response_code(404);
        echo json_encode(["error" => "Tarea no encontrada"]);
        return;
    }

    // Verificar rol o id para permitir eliminacion
    if($user->role !== 'admin' && $task['user_id'] != $user->id){
        http_response_code(403);
        echo json_encode(["error" => "No autorizado"]);
        return;
    }

    // Eliminar atrea
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");

    if(!$stmt){
        http_response_code(500);
        echo json_encode(["error" => "Error en la consulta"]);
        return; 
    }

    $stmt->bind_param("i", $task_id);

    if($stmt->execute()){
        echo json_encode(["message" => "Tarea eliminada correctamente"]);
    }else{
        http_response_code(500);
        echo json_encode(["error" => "Error al intentar eliminar la tarea"]);
    }
}