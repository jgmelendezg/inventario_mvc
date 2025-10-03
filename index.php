<?php

// Definir una constante para la ruta raíz del proyecto
define('ROOT_PATH', __DIR__);

// Autocargador para las clases del proyecto
spl_autoload_register(function ($clase) {
    $directorios = ['controllers', 'models', 'core']; // Incluye el nuevo directorio 'core'
    foreach ($directorios as $directorio) {
        $ruta_archivo = ROOT_PATH . "/app/{$directorio}/{$clase}.php";
        if (file_exists($ruta_archivo)) {
            require_once $ruta_archivo;
            return;
        }
    }
});

// Enrutador
$request_uri = $_SERVER['REQUEST_URI'];
$proyecto = '/inventario_equipos_mvc';

$router = new Router();
$router->enrutar($request_uri, $proyecto);