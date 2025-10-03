<?php
require_once ROOT_PATH . '/conexion.php';
require_once ROOT_PATH . '/app/controllers/BaseController.php';
require_once ROOT_PATH . '/app/models/SoftwareModel.php';
require_once ROOT_PATH . '/app/models/LogModel.php';

class SoftwareController extends BaseController
{
    private SoftwareModel $softwareModel;
    private LogModel $logModel;

    public function __construct()
    {
        parent::__construct();
        try {
            $conexion = get_db_connection();
            $this->softwareModel = new SoftwareModel($conexion);
            $this->logModel = new LogModel($conexion);
        } catch (Throwable $e) {
            error_log(__METHOD__ . ' – ' . $e->getMessage());
            die('Error de inicialización del sistema');
        }
    }

    public function listar(): void
    {
        $titulo = "Listado de Programas";
        $resultado = $this->softwareModel->listarProgramas();

        $this->cargarVista('software/listar', [
            'titulo' => $titulo,
            'url_base' => $this->urlBase,
            'resultado' => $resultado,
        ]);
    }
}