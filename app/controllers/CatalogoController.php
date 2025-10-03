<?php
require_once ROOT_PATH . '/conexion.php';
require_once ROOT_PATH . '/app/controllers/BaseController.php';
require_once ROOT_PATH . '/app/models/AreaModel.php';
require_once ROOT_PATH . '/app/models/SistemaModel.php';

class CatalogoController extends BaseController
{
    private AreaModel $areaModel;
    private SistemaModel $sistemaModel;

    public function __construct()
    {
        parent::__construct();
        try {
            $conexion = get_db_connection();
            $this->areaModel = new AreaModel($conexion);
            $this->sistemaModel = new SistemaModel($conexion);
        } catch (Throwable $e) {
            error_log(__METHOD__ . ' – ' . $e->getMessage());
            die('Error de inicialización del sistema');
        }
    }

    public function areas(): void
    {
        $titulo = "Catálogo de Áreas";
        $resultado = $this->areaModel->listar();

        $mensaje = match ($_GET['mensaje'] ?? '') {
            'area_agregada'    => '✅ Área agregada con éxito.',
            'area_actualizada' => '✅ Área actualizada con éxito.',
            'area_eliminada'   => '⚠️ Área eliminada con éxito.',
            default            => '',
        };
        $error = match ($_GET['error'] ?? '') {
            'id_invalido' => '❌ ID de área inválido.',
            default       => '',
        };

        $this->cargarVista('catalogos/areas/listar', [
            'titulo'    => $titulo,
            'url_base'  => $this->urlBase,
            'resultado' => $resultado,
            'mensaje'   => $mensaje,
            'error'     => $error,
        ]);
    }

    public function agregarArea(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_area'] ?? '');
            $ok = $this->areaModel->registrar($nombre);
            $this->redireccionar('catalogo', 'areas', ['mensaje' => $ok ? 'area_agregada' : 'error']);
            return;
        }
        $this->cargarVista('catalogos/areas/agregar', ['titulo' => 'Agregar Área']);
    }

    public function editarArea(int $id): void
    {
        if (!$id) {
            $this->redireccionar('catalogo', 'areas', ['error' => 'id_invalido']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_area'] ?? '');
            $ok = $this->areaModel->actualizar($id, $nombre);
            $this->redireccionar('catalogo', 'areas', ['mensaje' => $ok ? 'area_actualizada' : 'error']);
            return;
        }

        $area = $this->areaModel->obtenerPorId($id);
        if (!$area) {
            $this->redireccionar('catalogo', 'areas', ['error' => 'id_invalido']);
            return;
        }

        $this->cargarVista('catalogos/areas/editar', [
            'titulo'    => 'Editar Área',
            'url_base'  => $this->urlBase,
            'area'      => $area,
        ]);
    }

    public function eliminarArea(int $id): void
    {
        if (!$id) {
            $this->redireccionar('catalogo', 'areas', ['error' => 'id_invalido']);
            return;
        }

        $ok = $this->areaModel->eliminar($id);
        $this->redireccionar('catalogo', 'areas', ['mensaje' => $ok ? 'area_eliminada' : 'error']);
    }

    public function sistemas(): void
    {
        $titulo = "Catálogo de Sistemas Operativos";
        $resultado = $this->sistemaModel->listar();

        $mensaje = match ($_GET['mensaje'] ?? '') {
            'sistema_agregado'    => '✅ Sistema agregado con éxito.',
            'sistema_actualizado' => '✅ Sistema actualizado con éxito.',
            'sistema_eliminado'   => '⚠️ Sistema eliminado con éxito.',
            default               => '',
        };
        $error = match ($_GET['error'] ?? '') {
            'id_invalido' => '❌ ID de sistema inválido.',
            default       => '',
        };

        $this->cargarVista('catalogos/sistemas/listar', [
            'titulo'    => $titulo,
            'url_base'  => $this->urlBase,
            'resultado' => $resultado,
            'mensaje'   => $mensaje,
            'error'     => $error,
        ]);
    }

    public function agregarSistema(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_sistema'] ?? '');
            $ok = $this->sistemaModel->registrar($nombre);
            $this->redireccionar('catalogo', 'sistemas', ['mensaje' => $ok ? 'sistema_agregado' : 'error']);
            return;
        }
        $this->cargarVista('catalogos/sistemas/agregar', ['titulo' => 'Agregar Sistema Operativo']);
    }

    public function editarSistema(int $id): void
    {
        if (!$id) {
            $this->redireccionar('catalogo', 'sistemas', ['error' => 'id_invalido']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_sistema'] ?? '');
            $ok = $this->sistemaModel->actualizar($id, $nombre);
            $this->redireccionar('catalogo', 'sistemas', ['mensaje' => $ok ? 'sistema_actualizado' : 'error']);
            return;
        }

        $sistema = $this->sistemaModel->obtenerPorId($id);
        if (!$sistema) {
            $this->redireccionar('catalogo', 'sistemas', ['error' => 'id_invalido']);
            return;
        }

        $this->cargarVista('catalogos/sistemas/editar', [
            'titulo'   => 'Editar Sistema Operativo',
            'url_base' => $this->urlBase,
            'sistema'  => $sistema,
        ]);
    }

    public function eliminarSistema(int $id): void
    {
        if (!$id) {
            $this->redireccionar('catalogo', 'sistemas', ['error' => 'id_invalido']);
            return;
        }

        $ok = $this->sistemaModel->eliminar($id);
        $this->redireccionar('catalogo', 'sistemas', ['mensaje' => $ok ? 'sistema_eliminado' : 'error']);
    }
}