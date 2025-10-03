<?php
require_once ROOT_PATH . '/app/models/DashboardModel.php';
require_once ROOT_PATH . '/app/controllers/BaseController.php';

class DashboardController extends BaseController
{
    private DashboardModel $dashboardModel;

    public function __construct()
    {
        parent::__construct();
        try {
            $conexion = get_db_connection();
            $this->dashboardModel = new DashboardModel($conexion);
        } catch (Throwable $e) {
            error_log(__METHOD__ . ' – ' . $e->getMessage());
            die('Error de inicialización del sistema');
        }
    }

    public function index(): void
    {
        $titulo = "Panel Principal";
        
        $datos = $this->dashboardModel->obtenerDatosDashboard();

        $this->cargarVista('dashboard/index', [
            'titulo'   => $titulo,
            'url_base' => $this->urlBase,
            'datos'    => $datos,
        ]);
    }
}