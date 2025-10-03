<?php
require_once ROOT_PATH . '/app/models/LogModel.php';
require_once ROOT_PATH . '/app/controllers/BaseController.php';

class AsignacionController extends BaseController
{
    private AsignacionModel $asignacionModel;
    private EquipoModel $equipoModel;
    private UsuarioModel $usuarioModel;
    private LogModel $logModel;

    public function __construct()
    {
        parent::__construct();
        try {
            $conexion = get_db_connection();
            $this->asignacionModel = new AsignacionModel($conexion);
            $this->equipoModel = new EquipoModel($conexion);
            $this->usuarioModel = new UsuarioModel($conexion);
            $this->logModel = new LogModel($conexion);
        } catch (Throwable $e) {
            error_log(__METHOD__ . ' – ' . $e->getMessage());
            die('Error de inicialización del sistema');
        }
    }

    public function historial(): void
    {
        $titulo = 'Historial de Asignaciones';
        $resultado = $this->asignacionModel->obtenerHistorial();

        $mensaje = match ($_GET['mensaje'] ?? '') {
            'asignado' => '✅ Equipo asignado correctamente.',
            'desasignado' => '✅ Equipo desasignado con éxito.',
            default => '',
        };
        $error = match ($_GET['error'] ?? '') {
            'error_asignacion' => '❌ Error al asignar el equipo.',
            'error_desasignacion' => '❌ Error al desasignar el equipo.',
            default => '',
        };

        $this->cargarVista('asignaciones/historial', [
            'titulo' => $titulo,
            'url_base' => $this->urlBase,
            'resultado' => $resultado,
            'mensaje' => $mensaje,
            'error' => $error,
        ]);
    }

    public function asignar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $this->asignacionModel->asignarEquipo($_POST['id_equipo'], $_POST['id_usuario']);
            if ($ok) {
                $this->logModel->registrarLog("Asignación de equipo (ID: " . $_POST['id_equipo'] . ") a usuario (ID: " . $_POST['id_usuario'] . ")");
                $this->redireccionar('asignacion', 'historial', ['mensaje' => 'asignado']);
            } else {
                $this->redireccionar('asignacion', 'asignar', ['error' => 'error_asignacion']);
            }
            return;
        }
    
        $equipos = $this->equipoModel->obtenerEquiposSinAsignacion()->fetch_all(MYSQLI_ASSOC);
        $usuarios = $this->usuarioModel->obtenerUsuariosConPaginacion(['estado' => 'Activo'], 100);
    
        $this->cargarVista('asignaciones/asignar', [
            'titulo' => 'Asignar Equipo',
            'url_base' => $this->urlBase,
            'equipos' => $equipos,
            'usuarios' => $usuarios,
        ]);
    }

    public function desasignar(int $id): void
    {
        $ok = $this->asignacionModel->desasignar($id);
        if ($ok) {
            $this->logModel->registrarLog("Desasignación de equipo (ID Asignación: $id)");
            $this->redireccionar('asignacion', 'historial', ['mensaje' => 'desasignado']);
        } else {
            $this->redireccionar('asignacion', 'historial', ['error' => 'error_desasignacion']);
        }
    }
    
    protected function redireccionarAsignacion(string $accion, string $msg = '', bool $esError = false): void
    {
        $param = $esError ? 'error' : 'mensaje';
        $url   = $this->urlBase . 'asignaciones/' . $accion . ($msg ? "?$param=" . urlencode($msg) : '');
        header("Location: $url");
        exit;
    }
}