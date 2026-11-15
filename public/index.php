<?php

declare(strict_types=1);

session_start();
require_once __DIR__ . '/../src/autoload.php';

$configPath = __DIR__ . '/../config/database.php';
$config = require (is_file($configPath) ? $configPath : __DIR__ . '/../config/database.example.php');

try {
    $controller = ApplicationFactory::build($config);
    $routes = [
        'crear' => static fn () => ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
            ? $controller->create()
            : $controller->createForm(),
        'listar' => static fn () => $controller->index(),
        'reporte' => static fn () => $controller->report(),
    ];
    $action = (string) ($_GET['accion'] ?? 'listar');

    if (!isset($routes[$action])) {
        http_response_code(404);
        $mensaje = 'La acción solicitada no existe.';
        require __DIR__ . '/../views/error.php';
        exit;
    }

    $routes[$action]();
} catch (Throwable $exception) {
    http_response_code($exception instanceof InvalidArgumentException ? 422 : 500);
    $mensaje = $exception->getMessage();
    require __DIR__ . '/../views/error.php';
}
