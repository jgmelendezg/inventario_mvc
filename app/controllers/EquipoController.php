<?php
require_once ROOT_PATH . '/conexion.php';
require_once ROOT_PATH . '/app/models/EquipoModel.php';
require_once ROOT_PATH . '/app/models/LogModel.php';
require_once ROOT_PATH . '/app/models/MantenimientoModel.php';
require_once ROOT_PATH . '/app/controllers/BaseController.php';
require_once ROOT_PATH . '/app/models/ComboModel.php';

class EquipoController extends BaseController
{
    private EquipoModel $equipoModel;
    private LogModel $logModel;
    private MantenimientoModel $mantenimientoModel;
    private ComboModel $comboModel;

    public function __construct()
    {
        parent::__construct();
        try {
            $conexion = get_db_connection();
            $this->equipoModel = new EquipoModel($conexion);
            $this->logModel = new LogModel($conexion);
            $this->mantenimientoModel = new MantenimientoModel($conexion);
            $this->comboModel = new ComboModel($conexion);
        } catch (Throwable $e) {
            error_log(__METHOD__ . ' – ' . $e->getMessage());
            die('Error de inicialización del sistema');
        }
    }

    public function listar(): void
    {
        $titulo          = 'Listado de Equipos';
        $filtros         = [
            'search'   => $_GET['search']   ?? '',
            'area'     => $_GET['area']     ?? '',
            'tipo'     => $_GET['tipo']     ?? '',
            'estado'   => $_GET['estado']   ?? '',
            'garantia' => $_GET['garantia'] ?? '',
        ];

        $paginaActual    = (int)($_GET['pagina'] ?? 1);
        $registrosPorPag = 10;

        $totalRegistros = $this->equipoModel->contarEquipos($filtros);
        $paginacion = $this->calcularPaginacion($totalRegistros, $registrosPorPag, $paginaActual);

        $resultado       = $this->equipoModel->obtenerEquiposConPaginacion(
                               $filtros,
                               $paginacion['registros_por_pag'],
                               $paginacion['offset']
                           );
        
        $areas = $this->comboModel->areas();

        $mensajes = [
            'equipo_registrado'  => '✅ Equipo registrado con éxito.',
            'equipo_actualizado' => '✅ Equipo actualizado con éxito.',
            'equipo_eliminado'   => '⚠️ Equipo retirado con éxito.',
        ];
        $errores   = [
            'error_eliminacion'   => '❌ Error al retirar el equipo.',
            'equipo_no_encontrado'=> '❌ Equipo no encontrado.',
            'id_invalido'         => '❌ ID de equipo inválido.',
        ];

        $this->cargarVista('equipos/listar', array_merge($paginacion, [
            'titulo'          => $titulo,
            'resultado'       => $resultado,
            'url_base'        => $this->urlBase,
            'filtros'         => $filtros,
            'mensaje'         => $this->getMensaje('mensaje', $mensajes),
            'error'           => $this->getMensaje('error', $errores),
            'areas'           => $areas,
        ]));
    }
    
    public function registrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $this->equipoModel->registrarEquipo($_POST);
            if ($ok) {
                $this->logModel->registrarLog("Registro de equipo: " . $_POST['nombre_pc']);
                $this->redireccionar('equipo', 'listar', ['mensaje' => 'equipo_registrado']);
            } else {
                $this->redireccionar('equipo', 'registrar', ['error' => 'error_registro']);
            }
            return;
        }

        $areas   = $this->comboModel->areas();
        $sistemas= $this->comboModel->sistemas();
        
        $this->cargarVista('equipos/registrar', [
            'titulo'   => 'Registrar Equipo',
            'url_base' => $this->urlBase,
            'areas'    => $areas,
            'sistemas' => $sistemas,
        ]);
    }

    public function editar($id): void
    {
        $id = (int)$id;
        if (!$id) {
            $this->redireccionar('equipo', 'listar', ['error' => 'id_invalido']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $this->equipoModel->actualizarEquipo($id, $_POST);
            if ($ok) {
                $this->logModel->registrarLog("Actualización de equipo (ID: $id)");
                $this->redireccionar('equipo', 'listar', ['mensaje' => 'equipo_actualizado']);
            } else {
                $this->redireccionar('equipo', "editar/$id", ['error' => 'error_actualizar']);
            }
            return;
        }

        $equipo = $this->equipoModel->obtenerEquipoPorId($id);
        if (!$equipo) {
            $this->redireccionar('equipo', 'listar', ['error' => 'equipo_no_encontrado']);
            return;
        }

        $areas   = $this->comboModel->areas();
        $sistemas= $this->comboModel->sistemas();

        $this->cargarVista('equipos/editar', [
            'titulo'   => 'Editar Equipo',
            'equipo'   => $equipo,
            'url_base' => $this->urlBase,
            'areas'    => $areas,
            'sistemas' => $sistemas,
        ]);
    }

    public function eliminar($id): void
    {
        $id = (int)$id;
        if (!$id) {
            $this->redireccionar('equipo', 'listar', ['error' => 'id_invalido']);
            return;
        }

        $equipo = $this->equipoModel->obtenerEquipoPorId($id);
        $ok = $this->equipoModel->retirarEquipo($id);
        if ($ok) {
            $this->logModel->registrarLog("Retiro de equipo: " . $equipo['nombre_pc'] . " (ID: $id)");
            $this->redireccionar('equipo', 'listar', ['mensaje' => 'equipo_eliminado']);
        } else {
            $this->redireccionar('equipo', 'listar', ['error' => 'error_eliminacion']);
        }
    }

    public function ver(int $id): void
    {
        if (!$id) {
            $this->redireccionar('equipo', 'listar', ['error' => 'id_invalido']);
            return;
        }

        $equipo = $this->equipoModel->obtenerEquipoPorId($id);
        if (!$equipo) {
            $this->redireccionar('equipo', 'listar', ['error' => 'equipo_no_encontrado']);
            return;
        }

        $ultimoMantenimiento = $this->mantenimientoModel->obtenerUltimoMantenimiento($id);
        $diasTranscurridos = null;
        if ($ultimoMantenimiento) {
            $fechaUltimo = new DateTime($ultimoMantenimiento['fecha_mantenimiento']);
            $hoy = new DateTime();
            $diasTranscurridos = $hoy->diff($fechaUltimo)->days;
        }

        $this->cargarVista('equipos/ver', [
            'titulo'            => 'Detalles del Equipo',
            'equipo'            => $equipo,
            'url_base'          => $this->urlBase,
            'diasTranscurridos' => $diasTranscurridos,
        ]);
    }

    public function listarSinAsignacion(): void
    {
        $titulo = 'Equipos sin Asignación';
        $resultado = $this->equipoModel->obtenerEquiposSinAsignacion();

        $this->cargarVista('equipos/sin_asignacion', [
            'titulo'    => $titulo,
            'url_base'  => $this->urlBase,
            'resultado' => $resultado
        ]);
    }
    
    protected function redireccionarEquipo(string $accion, string $msg = '', bool $esError = false): void
    {
        $param = $esError ? 'error' : 'mensaje';
        $url   = $this->urlBase . 'equipo/' . $accion . ($msg ? "?$param=" . urlencode($msg) : '');
        header("Location: $url");
        exit;
    }

}