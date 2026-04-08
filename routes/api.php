<?php

//Registro usuario nuevo 
if($route === 'register' && $method === 'POST'){
    require_once "../controllers/AuthController.php";
    register();
    exit();
};

// Login 
if ($route === 'login' && $method === 'POST') {
    require_once "../controllers/AuthController.php";
    login();
    exit();
}

// Traer tareas
if($route === 'tasks' && $method === 'GET'){
    require_once "../middleware/auth_jwt.php";
    require_once "../controllers/TaskController.php";
    getTasks($user);
    exit();
}

// Crear tarea
if($route ==='tasks' && $method === 'POST'){
    require_once "../middleware/auth_jwt.php";
    require_once "../controllers/TaskController.php";
    createTask($user);
    exit();
}

// Editar tarea
if($route === 'tasks' && $method === 'PUT'){
    // var_dump($route, $method);
    // die();
    require_once "../middleware/auth_jwt.php";
    require_once "../controllers/TaskController.php";
    updateTask($user);
    exit();
}
