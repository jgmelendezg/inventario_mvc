<?php
require_once ROOT_PATH . '/conexion.php';
require_once ROOT_PATH . '/app/controllers/BaseController.php';
require_once ROOT_PATH . '/app/models/ReporteModel.php';
require_once ROOT_PATH . '/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteController extends BaseController
{
    private ReporteModel $reporteModel;

    public function __construct()
    {
        parent::__construct();
        try {
            $conexion = get_db_connection();
            $this->reporteModel = new ReporteModel($conexion);
        } catch (Throwable $e) {
            error_log(__METHOD__ . ' – ' . $e->getMessage());
            die('Error de inicialización del sistema');
        }
    }

    public function index(): void
    {
        $this->cargarVista('reportes/index', [
            'titulo' => 'Generador de Reportes',
            'url_base' => $this->urlBase,
        ]);
    }

    public function generar(): void
    {
        $reporte = $_GET['reporte'] ?? '';
        $formato = $_GET['formato'] ?? 'html';
        $titulo = 'Reporte de Equipos';
        $resultado = null;

        // Obtener los datos del reporte
        switch ($reporte) {
            case 'activos':
                $titulo = 'Reporte de Equipos Activos';
                $resultado = $this->reporteModel->obtenerEquiposActivos();
                break;
            case 'usuarios':
                $titulo = 'Reporte de Equipos con Usuarios Asignados';
                $resultado = $this->reporteModel->obtenerEquiposConUsuariosAsignados();
                break;
            case 'software':
                $titulo = 'Reporte de Equipos con Software Asignado';
                $resultado = $this->reporteModel->obtenerEquiposConSoftwareAsignado();
                break;
            case 'mantenimientos':
                $titulo = 'Reporte de Mantenimientos Realizados y Programados';
                $resultado = $this->reporteModel->obtenerMantenimientos();
                break;
            case 'detalle':
            default:
                $titulo = 'Reporte Detallado de Equipos';
                $resultado = $this->reporteModel->obtenerDetalleEquipos();
                break;
        }

        // Si el formato es PDF, llamar a la nueva función de generación
        if ($formato === 'pdf') {
            $this->generarPdf($titulo, $resultado, $reporte, $this->urlBase);
            return;
        }

        // Si no, cargar la vista normal en HTML
        $this->cargarVista('reportes/resultados', [
            'titulo'    => $titulo,
            'url_base'  => $this->urlBase,
            'resultado' => $resultado,
            'reporte'   => $reporte,
        ]);
    }

    protected function generarPdf(string $titulo, mysqli_result $resultado, string $reporte, string $url_base): void
    {
        // 1. Configurar opciones de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        
        // 2. Extraer variables para que la vista las reconozca
        extract(compact('titulo', 'resultado', 'reporte', 'url_base'));
        
        // 3. Capturar el HTML de la vista
        ob_start();
        require ROOT_PATH . '/app/views/reportes/resultados.php';
        $html = ob_get_clean();

        // 4. Cargar y renderizar el PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // 5. Enviar el PDF al navegador
        $dompdf->stream("Reporte_{$reporte}.pdf", ["Attachment" => true]);
    }
}