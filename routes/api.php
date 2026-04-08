<?php

//Registro usuario nuevo 
if($route === 'register' && $method === 'POST'){
    require_once __DIR__ . "/../controllers/AuthController.php";
    register();
    exit();
};

// Login 
if ($route === 'login' && $method === 'POST') {
    require_once __DIR__ . "/../controllers/AuthController.php";
    login();
    exit();
}

// Traer tareas
if($route === 'tasks' && $method === 'GET'){
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    require_once __DIR__ . "/../controllers/TaskController.php";
    getTasks($user);
    exit();
}

// Crear tarea
if($route ==='tasks' && $method === 'POST'){
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    require_once __DIR__ . "/../controllers/TaskController.php";
    createTask($user);
    exit();
}

// Editar tarea
if($route === 'tasks' && $method === 'DELETE'){
    // var_dump($route, $method);
    // die();
    require_once __DIR__ ."/../middleware/auth_jwt.php";
    require_once __DIR__ . "/../controllers/TaskController.php";
    updateTask($user);
    exit();
}
