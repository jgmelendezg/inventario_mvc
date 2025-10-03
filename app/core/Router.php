<?php

class Router
{
    private $controladores_mapeo = [
        'equipos'      => 'equipo',
        'catalogos'    => 'catalogo',
        'usuarios'     => 'usuario',
        'asignaciones' => 'asignacion',
        'dashboard'    => 'dashboard',
        'software_asignacion' => 'softwareAsignacion',
        'mantenimiento' => 'mantenimiento',
        'reportes'     => 'reporte'
    ];

    public function enrutar(string $request_uri, string $proyecto): void
    {
        if (strpos($request_uri, $proyecto) === 0) {
            $request_uri = substr($request_uri, strlen($proyecto));
        }
        $request_uri = trim(parse_url($request_uri, PHP_URL_PATH), '/');
        $partes_uri = $request_uri ? explode('/', $request_uri) : [];

        $controlador_base = array_shift($partes_uri);
        $accion = array_shift($partes_uri);
        $id = array_shift($partes_uri);

        $controlador_mapeado = (string) ($this->controladores_mapeo[$controlador_base] ?? $controlador_base);
        $controlador_nombre = ucfirst($controlador_mapeado) . 'Controller';
        $ruta_controlador = ROOT_PATH . "/app/controllers/{$controlador_nombre}.php";

        if (file_exists($ruta_controlador)) {
            $accion = $accion ?: 'index';
        } else {
            $controlador_nombre = 'EquipoController';
            $ruta_controlador = ROOT_PATH . "/app/controllers/{$controlador_nombre}.php";
            $accion = $controlador_base ?: 'listar';
            $id = $accion ?: null;
        }

        if (!file_exists($ruta_controlador)) {
            http_response_code(404);
            die("404 - Controlador no encontrado: " . $controlador_nombre);
        }
        require_once $ruta_controlador;

        if (!class_exists($controlador_nombre)) {
            http_response_code(500);
            die("500 - Error: La clase {$controlador_nombre} no existe.");
        }
        $controlador = new $controlador_nombre();

        if (!method_exists($controlador, $accion)) {
            http_response_code(404);
            die("404 - Acción no encontrada: " . $accion);
        }

        if ($id !== null) {
            $controlador->$accion($id);
        } else {
            $controlador->$accion();
        }
    }
}