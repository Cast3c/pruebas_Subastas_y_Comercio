<!-- Usuario Admin  -->
<!-- {
  "name": "Ricardo",
  "email": "ricardo@test.com",
  "password": "123456",
  "role": "admin"
} -->


<!-- User simple -->
<!-- {
  "name": "usuario de prueba",
  "email": "usuario@test.com",
  "password": "123456",
  "role": "user"
} -->

<!-- <!DOCTYPE html>
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
            <h2>Deploy ok</h2>
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

        </html> -->

# Task Manager API

API REST desarrollada en PHP para la gestión de tareas con autenticación JWT y control de roles.

## 🚀 Tecnologías
- PHP
- MySQL
- JWT (Firebase PHP-JWT)
- Apache (XAMPP / Hosting)

## 🔐 Autenticación
La API utiliza JWT. Se debe enviar el token en:

Authorization: Bearer <token>