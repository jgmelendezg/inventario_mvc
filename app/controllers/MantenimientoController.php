<?php
require_once ROOT_PATH . '/conexion.php';
require_once ROOT_PATH . '/app/models/MantenimientoModel.php';
require_once ROOT_PATH . '/app/models/EquipoModel.php';
require_once ROOT_PATH . '/app/models/UsuarioModel.php';
require_once ROOT_PATH . '/app/controllers/BaseController.php';

class MantenimientoController extends BaseController
{
    private MantenimientoModel $mantenimientoModel;
    private EquipoModel $equipoModel;
    private UsuarioModel $usuarioModel;

    public function __construct()
    {
        parent::__construct();
        try {
            $conexion = get_db_connection();
            $this->mantenimientoModel = new MantenimientoModel($conexion);
            $this->equipoModel = new EquipoModel($conexion);
            $this->usuarioModel = new UsuarioModel($conexion);
        } catch (Throwable $e) {
            error_log(__METHOD__ . ' – ' . $e->getMessage());
            die('Error de inicialización del sistema');
        }
    }

    public function listar(): void
    {
        $titulo = "Historial de Mantenimientos";
        $resultado = $this->mantenimientoModel->obtenerListado();

        $mensaje = match ($_GET['mensaje'] ?? '') {
            'registrado' => '? Registro de mantenimiento agregado con éxito.',
            default => '',
        };
        $error = match ($_GET['error'] ?? '') {
            'error_registro' => '? Error al registrar el mantenimiento.',
            default => '',
        };

        $this->cargarVista('mantenimiento/listar', [
            'titulo'    => $titulo,
            'url_base'  => $this->urlBase,
            'resultado' => $resultado,
            'mensaje'   => $mensaje,
            'error'     => $error,
        ]);
    }

    public function programados(): void
    {
        $titulo = "Mantenimientos Programados";
        $resultado = $this->mantenimientoModel->obtenerMantenimientosProgramados();
        
        $this->cargarVista('mantenimiento/programados', [
            'titulo' => $titulo,
            'url_base' => $this->urlBase,
            'resultado' => $resultado,
        ]);
    }

    public function registrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $motivos = $_POST['motivos'] ?? [];
            $motivos_json = json_encode($motivos);
    
            $datos = [
                'id_equipo'              => $_POST['id_equipo'],
                'id_usuario_tecnico'     => $_POST['id_usuario_tecnico'],
                'fecha_solicitud'        => $_POST['fecha_solicitud'],
                'fecha_mantenimiento'    => $_POST['fecha_mantenimiento'],
                'tipo_mantenimiento'     => $_POST['tipo_mantenimiento'],
                'motivos'                => $motivos_json,
                'diagnostico'            => $_POST['diagnostico'] ?? '',
                'soluciones'             => $_POST['soluciones'] ?? '',
                'recomendaciones'        => $_POST['recomendaciones'] ?? '',
            ];
            
            $ok = $this->mantenimientoModel->registrar($datos);
            
            $this->redireccionarMantenimiento('listar', $ok ? 'registrado' : 'error_registro', !$ok);
            return;
        }

        $equipos = $this->equipoModel->obtenerEquiposConPaginacion(['estado' => 'Activo'], 100);
        $tecnicos = $this->usuarioModel->obtenerUsuariosConPaginacion(['estado' => 'Activo'], 100);
        
        $this->cargarVista('mantenimiento/registrar', [
            'titulo'    => "Registrar Mantenimiento",
            'url_base'  => $this->urlBase,
            'equipos'   => $equipos,
            'tecnicos'  => $tecnicos,
        ]);
    }

    public function historial(int $id_equipo): void
    {
        $equipo = $this->equipoModel->obtenerEquipoPorId($id_equipo);
        if (!$equipo) {
            $this->redireccionarMantenimiento('equipos/listar', 'equipo_no_encontrado');
            return;
        }

        $titulo = 'Historial de Mantenimientos';
        $resultado = $this->mantenimientoModel->obtenerHistorialPorEquipo($id_equipo);

        $this->cargarVista('mantenimiento/historial', [
            'titulo'   => $titulo,
            'url_base' => $this->urlBase,
            'equipo'   => $equipo,
            'resultado'=> $resultado,
        ]);
    }

    protected function redireccionarMantenimiento(string $accion, string $msg = '', bool $esError = false): void
    {
        $param = $esError ? 'error' : 'mensaje';
        $url   = $this->urlBase . 'mantenimiento/' . $accion . ($msg ? "?$param=" . urlencode($msg) : '');
        header("Location: $url");
        exit;
    }

    public function programar(int $id_equipo): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $motivos = $_POST['motivos'] ?? [];
            $motivos_json = json_encode($motivos);
    
            $datos = [
                'id_equipo'              => $id_equipo,
                'id_usuario_tecnico'     => $_POST['id_usuario_tecnico'],
                'fecha_solicitud'        => $_POST['fecha_solicitud'],
                'fecha_programada'       => $_POST['fecha_programada'],
                'tipo_mantenimiento'     => $_POST['tipo_mantenimiento'],
                'motivos'                => $motivos_json,
                'observaciones_solicitud'=> $_POST['observaciones_solicitud'] ?? '',
            ];
            
            $ok = $this->mantenimientoModel->programarMantenimiento($datos);
            
            $this->redireccionarMantenimiento('programados', $ok ? 'programado' : 'error_programacion', !$ok);
            return;
        }

        $equipo = $this->equipoModel->obtenerEquipoPorId($id_equipo);
        $tecnicos = $this->usuarioModel->obtenerUsuariosConPaginacion(['estado' => 'Activo', 'cargo' => 'Técnico'], 100);

        $this->cargarVista('mantenimiento/programar', [
            'titulo'    => "Programar Mantenimiento",
            'url_base'  => $this->urlBase,
            'equipo'    => $equipo,
            'tecnicos'  => $tecnicos,
        ]);
    }
}