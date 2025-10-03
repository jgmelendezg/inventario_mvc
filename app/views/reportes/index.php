<?php
/**
 * @var string $titulo
 * @var string $url_base
 */
?>
<h2 class="mb-4"><i class="bi bi-bar-chart-line"></i> <?= htmlspecialchars($titulo) ?></h2>

<div class="card p-4">
    <h5 class="card-title">Seleccione el tipo de reporte a generar:</h5>
    </br>
    <form action="<?= $url_base ?>reportes/generar" method="GET" class="row g-3">
        <div class="col-md-12">
            <label for="reporte_select" class="form-label">Tipo de Reporte:</label>
            <select name="reporte" id="reporte_select" class="form-select mb-3" required>
                <option value="">-- Seleccione un reporte --</option>
                <option value="activos">Equipos Activos</option>
                <option value="usuarios">Equipos con Usuarios Asignados</option>
                <option value="software">Equipos con Software Asignado</option>
                <option value="detalle">Detalle Completo de Equipos</option>
                <option value="mantenimientos">Mantenimientos Realizados/Programados</option>
            </select>
        </div>
        <div class="col-md-6">
            <button type="submit" name="formato" value="html" class="btn btn-primary w-100">
                <i class="bi bi-file-earmark-bar-graph"></i> Ver en HTML
            </button>
        </div>
        <div class="col-md-6">
            <button type="submit" name="formato" value="pdf" class="btn btn-danger w-100">
                <i class="bi bi-file-earmark-pdf"></i> Generar PDF
            </button>
        </div>
    </form>
</div>