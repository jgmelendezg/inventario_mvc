<?php
require_once ROOT_PATH . '/conexion.php';
require_once ROOT_PATH . '/app/models/UsuarioModel.php';
require_once ROOT_PATH . '/app/controllers/BaseController.php';
require_once ROOT_PATH . '/app/models/ComboModel.php';
require_once ROOT_PATH . '/app/models/LogModel.php';

class UsuarioController extends BaseController
{
    private UsuarioModel $usuarioModel;
    private ComboModel $comboModel;
    private LogModel $logModel;

    public function __construct()
    {
        parent::__construct();
        try {
            $conexion = get_db_connection();
            $this->usuarioModel = new UsuarioModel($conexion);
            $this->comboModel = new ComboModel($conexion);
            $this->logModel = new LogModel($conexion);
        } catch (Throwable $e) {
            error_log(__METHOD__ . ' – ' . $e->getMessage());
            die('Error de inicialización del sistema');
        }
    }

    public function listar(): void
    {
        $titulo = 'Listado de Usuarios';
        $filtros = [
            'area' => $_GET['area'] ?? '',
            'estado' => $_GET['estado'] ?? '',
        ];

        $paginaActual = max(1, (int)($_GET['pagina'] ?? 1));
        $registrosPorPag = 10;
        $offset = ($paginaActual - 1) * $registrosPorPag;

        $totalRegistros = $this->usuarioModel->contarUsuarios($filtros);
        $resultado = $this->usuarioModel->obtenerUsuariosConPaginacion($filtros, $registrosPorPag, $offset);

        $totalPaginas = $totalRegistros === 0 ? 1 : (int)ceil($totalRegistros / $registrosPorPag);
        $mostrandoDesde = $totalRegistros === 0 ? 0 : $offset + 1;
        $mostrandoHasta = min($offset + $registrosPorPag, $totalRegistros);

        $mensaje = match ($_GET['mensaje'] ?? '') {
            'usuario_registrado' => '✅ Usuario registrado con éxito.',
            'usuario_actualizado' => '✅ Usuario actualizado con éxito.',
            'usuario_inactivado' => '⚠️ Usuario inactivado.',
            default => '',
        };
        $error = match ($_GET['error'] ?? '') {
            'error_inactivacion' => '❌ Error al inactivar el usuario.',
            'usuario_no_encontrado' => '❌ Usuario no encontrado.',
            'id_invalido' => '❌ ID de usuario inválido.',
            default => '',
        };

        $this->cargarVista('usuarios/listar', [
            'titulo' => $titulo,
            'url_base' => $this->urlBase,
            'resultado' => $resultado,
            'filtros' => $filtros,
            'total_registros' => $totalRegistros,
            'pagina_actual' => $paginaActual,
            'total_paginas' => $totalPaginas,
            'mostrando_desde' => $mostrandoDesde,
            'mostrando_hasta' => $mostrandoHasta,
            'mensaje' => $mensaje,
            'error' => $error,
        ]);
    }

    public function registrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $this->usuarioModel->registrarUsuario($_POST);
            $this->redireccionar('usuario', 'listar', ['mensaje' => $ok ? 'usuario_registrado' : 'error_registro']);
            return;
        }

        $areas = $this->comboModel->areas();

        $this->cargarVista('usuarios/registrar', [
            'titulo' => 'Registrar Usuario',
            'url_base' => $this->urlBase,
            'areas' => $areas,
        ]);
    }

    public function editar(int $id): void
    {
        if (!$id) {
            $this->redireccionar('usuario', 'listar', ['error' => 'id_invalido']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $this->usuarioModel->actualizarUsuario($id, $_POST);
            $this->redireccionar('usuario', 'listar', ['mensaje' => $ok ? 'usuario_actualizado' : 'error_actualizacion']);
            return;
        }

        $usuario = $this->usuarioModel->obtenerUsuarioPorId($id);
        if (!$usuario) {
            $this->redireccionar('usuario', 'listar', ['error' => 'usuario_no_encontrado']);
            return;
        }

        $areas = $this->comboModel->areas();

        $this->cargarVista('usuarios/editar', [
            'titulo' => 'Editar Usuario',
            'url_base' => $this->urlBase,
            'usuario' => $usuario,
            'areas'    => $areas,
        ]);
    }

    public function inactivar(int $id): void
    {
        if (!$id) {
            $this->redireccionar('usuario', 'listar', ['error' => 'id_invalido']);
            return;
        }

        $ok = $this->usuarioModel->inactivarUsuario($id);
        $this->redireccionar('usuario', 'listar', ['mensaje' => $ok ? 'usuario_inactivado' : 'error_inactivacion']);
    }
}