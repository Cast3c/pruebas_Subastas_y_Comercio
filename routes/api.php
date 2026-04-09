<?php


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
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    getUsers($user);
    exit();
}

//Crear usuario nuevo (role: user/admin)
if($route === 'users' && $method === 'POST'){
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    createUser($user);
    exit();
}

// Editar usuario (role:admin)
if($route === 'users' && $method === 'PUT'){
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    updateUser($user);
    exit();
}

// Eliminar usuario (role:admin)
if($route === 'users' && $method === 'DELETE'){
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    deleteUser($user);
    exit();
}

// Traer tareas
if($route === 'tasks' && $method === 'GET'){
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    getTasks($user);
    // echo json_encode(["mensaje" => "Middleware saltado"]);
    exit();
}

// Crear tarea
if($route ==='tasks' && $method === 'POST'){
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    createTask($user);
    exit();
}

// Editar tarea
if($route === 'tasks' && $method === 'PUT'){
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    updateTask($user);
    exit();
}

// Eliminar tarea
if($route === 'tasks' && $method === 'DELETE'){
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    deleteTask($user);
    exit();
}

http_response_code(404);
echo json_encode(["error" => "Endpoint no encontrado"]);