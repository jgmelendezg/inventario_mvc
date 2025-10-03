<?php

require_once ROOT_PATH . '/app/core/PaginacionTrait.php';
require_once ROOT_PATH . '/app/core/MensajesTrait.php';

abstract class BaseController
{
    use PaginacionTrait;
    use MensajesTrait;

    protected string $urlBase = '/inventario_equipos_mvc/';

    public function __construct()
    {
        // El constructor no necesita hacer nada, pero debe existir para ser llamado por las clases hijas.
    }

    protected function cargarVista(string $nombreVista, array $datos = []): void
    {
        extract($datos);
        require_once ROOT_PATH . '/app/views/layout.php';
    }

    protected function redireccionar(string $controlador, string $accion, array $params = []): void
    {
        $queryString = http_build_query($params);
        $url = $this->urlBase . strtolower($controlador) . '/' . $accion;
        if (!empty($queryString)) {
            $url .= '?' . $queryString;
        }
        header("Location: $url");
        exit;
    }
}