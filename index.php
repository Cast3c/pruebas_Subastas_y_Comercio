<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = "/task_manager/";
$route = str_replace($base, "", $request);

$method = $_SERVER['REQUEST_METHOD'];

if ($route === "" || "/") {
    if ($method === 'GET') {
?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Task Manager API</title>
            <style>
                body {
                    font-family: Arial;
                    padding: 40px;
                    background: #f4f4f4;
                }

                h1 {
                    color: #333;
                }

                code {
                    background: #eee;
                    padding: 4px;
                }
            </style>
        </head>

        <body>
            <h1>Task Manager API</h1>
            <p>API de prueba para Subastas y Comercio</p>

            <h2>Endpoints</h2>
            <ul>
                <li><code>POST /api/register</code></li>
                <li><code>POST /api/login</code></li>
                <li><code>GET /api/tasks</code></li>
                <li><code>POST /api/tasks</code></li>
                <li><code>PUT /api/tasks?id=1</code></li>
                <li><code>DELETE /api/tasks?id=1</code></li>
            </ul>

            <h2>Autenticación</h2>
            <p>Header: <code>Authorization: Bearer TOKEN</code></p>
        </body>

        </html>
<?php
        exit();
    }
}

echo "
         API de prueba para Subastas y Comercio
    ";

header("Content-Type: application/json");

require_once "./routes/api.php";
