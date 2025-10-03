<?php
require_once ROOT_PATH . '/conexion.php';
require_once ROOT_PATH . '/app/controllers/BaseController.php';
require_once ROOT_PATH . '/app/models/SoftwareAsignacionModel.php';
require_once ROOT_PATH . '/app/models/EquipoModel.php';
require_once ROOT_PATH . '/app/models/SoftwareModel.php';
require_once ROOT_PATH . '/app/models/LogModel.php';

class SoftwareAsignacionController extends BaseController
{
    private SoftwareAsignacionModel $softwareAsignacionModel;
    private EquipoModel $equipoModel;
    private SoftwareModel $softwareModel;
    private LogModel $logModel;

    public function __construct()
    {
        parent::__construct();
        try {
            $conexion = get_db_connection();
            $this->softwareAsignacionModel = new SoftwareAsignacionModel($conexion);
            $this->equipoModel = new EquipoModel($conexion);
            $this->softwareModel = new SoftwareModel($conexion);
            $this->logModel = new LogModel($conexion);
        } catch (Throwable $e) {
            error_log(__METHOD__ . ' – ' . $e->getMessage());
            die('Error de inicialización del sistema');
        }
    }

    public function listar(): void
    {
        $titulo = "Asignación de Software";
        $resultado = $this->softwareAsignacionModel->obtenerListado();

        $mensaje = match ($_GET['mensaje'] ?? '') {
            'asignado' => '✅ Software asignado correctamente.',
            default => '',
        };
        $error = match ($_GET['error'] ?? '') {
            'error_asignacion' => '❌ Error al asignar el software.',
            'software_existente' => '❌ Este software ya está asignado a este equipo.',
            default => '',
        };

        $this->cargarVista('software_asignacion/listar', [
            'titulo'    => $titulo,
            'url_base'  => $this->urlBase,
            'resultado' => $resultado,
            'mensaje'   => $mensaje,
            'error'     => $error,
        ]);
    }

    public function asignar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_equipo = $_POST['id_equipo'];
            $id_programa = $_POST['id_programa'];

            // Verificar si la asignación ya existe
            if ($this->softwareAsignacionModel->existeAsignacion($id_equipo, $id_programa)) {
                $this->redireccionarSoftwareAsignacion('asignar', 'software_existente', true);
                return;
            }

            // Registrar la asignación
            $ok = $this->softwareAsignacionModel->registrar($_POST);
            if ($ok) {
                $this->logModel->registrarLog("Asignación de software (ID: $id_programa) a equipo (ID: $id_equipo)");
                $this->redireccionarSoftwareAsignacion('listar', 'asignado');
            } else {
                $this->redireccionarSoftwareAsignacion('asignar', 'error_asignacion', true);
            }
            return;
        }

        $equipos = $this->equipoModel->obtenerEquipos(['estado' => 'Activo']);
        $programas = $this->softwareModel->listarProgramas();

        $this->cargarVista('software_asignacion/asignar', [
            'titulo'    => "Asignar Software",
            'url_base'  => $this->urlBase,
            'equipos'   => $equipos,
            'programas' => $programas,
        ]);
    }

    protected function redireccionarSoftwareAsignacion(string $accion, string $msg = '', bool $esError = false): void
    {
        $param = $esError ? 'error' : 'mensaje';
        $url   = $this->urlBase . 'software_asignacion/' . $accion . ($msg ? "?$param=" . urlencode($msg) : '');
        header("Location: $url");
        exit;
    }
}