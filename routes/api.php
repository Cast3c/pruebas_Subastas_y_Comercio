<?php

require_once __DIR__ ."/../middleware/auth_jwt.php";
require_once __DIR__ . "/../controllers/AuthController.php";
require_once __DIR__ . "/../controllers/UserController.php";
require_once __DIR__ . "/../controllers/TaskController.php";

// Login 
if ($route === 'login' && $method === 'POST') {
    login();
    exit();
}

//Registro usuario nuevo (role:user)
if($route === 'register' && $method === 'POST'){
    register();
    exit();
};

// Listar usuarios (role:admin)
if ($route === 'users' && $method === 'GET') {
    getUsers($user);
    exit();
}

//Crear usuario nuevo (role: user/admin)
if($route === 'users' && $method === 'POST'){
    createUser($user);
    exit();
}

// Editar usuario (role:admin)
if($route === 'users' && $method === 'PUT'){
    updateUser($user);
    exit();
}

// Eliminar usuario (role:admin)
if($route === 'users' && $method === 'DELETE'){
    deleteUser($user);
    exit();
}

// Traer tareas
if($route === 'tasks' && $method === 'GET'){
    getTasks($user);
    // echo json_encode(["mensaje" => "Middleware saltado"]);
    exit();
}

// Crear tarea
if($route ==='tasks' && $method === 'POST'){
    createTask($user);
    exit();
}

// Editar tarea
if($route === 'tasks' && $method === 'PUT'){
    updateTask($user);
    exit();
}

// Eliminar tarea
if($route === 'tasks' && $method === 'DELETE'){
    deleteTask($user);
    exit();
}

http_response_code(404);
echo json_encode(["error" => "Endpoint no encontrado"]);