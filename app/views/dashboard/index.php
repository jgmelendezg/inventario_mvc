<?php
// Extracción de datos del array
extract($datos);
?>
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i> 
            Avisos de Garantía
        </h5>
        <button class="btn btn-sm btn-outline-secondary" type="button" 
                data-bs-toggle="collapse" data-bs-target="#garantiasCollapse" 
                aria-expanded="true" aria-controls="garantiasCollapse">
            <i class="bi bi-eye"></i> Ocultar/Mostrar
        </button>
    </div>

    <div class="collapse hide" id="garantiasCollapse">
        <?php if (!empty($avisosGarantia)): ?>
            <?php foreach ($avisosGarantia as $aviso): ?>
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <div>
                        ⚠️ El equipo <strong><?= htmlspecialchars($aviso['nombre_pc']) ?></strong> tiene garantía que vence el <strong><?= date("d/m/Y", strtotime($aviso['fecha_garantia'])) ?></strong>.
                    </div>
                    <a href="<?= $url_base ?>equipos/listar?estado=Activo" class="btn btn-sm btn-outline-dark">
                        <i class="bi bi-eye"></i> Ver equipos
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                No hay garantías próximas a vencer.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-bg-primary shadow">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-pc-display-horizontal"></i> Equipos activos</h5>
                <p class="card-text">Actualmente hay <strong><?= $equiposActivos ?></strong> equipos en uso.</p>
                <a href="<?= $url_base ?>equipos/listar?estado=Activo" class="btn btn-light"><i class="bi bi-eye"></i> Ver activos</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-secondary shadow">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-x-circle"></i> Equipos retirados</h5>
                <p class="card-text">Hay <strong><?= $equiposRetirados ?></strong> equipos fuera de servicio.</p>
                <a href="<?= $url_base ?>equipos/listar?estado=Retirado" class="btn btn-light"><i class="bi bi-eye"></i> Ver retirados</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success shadow">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-person-lines-fill"></i> Usuarios</h5>
                <p class="card-text">Actualmente hay <strong><?= $totalUsuarios ?></strong> usuarios registrados.</p>
                <a href="<?= $url_base ?>usuarios/listar" class="btn btn-light"><i class="bi bi-eye"></i> Ver usuarios</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning shadow">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-arrow-left-right"></i> Asignaciones</h5>
                <p class="card-text">Se han realizado <strong><?= $totalAsignaciones ?></strong> asignaciones.</p>
                <a href="<?= $url_base ?>asignaciones/historial" class="btn btn-light"><i class="bi bi-eye"></i> Ver historial</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-danger shadow">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-cpu"></i> Equipos sin software</h5>
                <p class="card-text">Hay <strong><?= $equiposSinSoftware ?></strong> equipos sin software registrado.</p>
                <a href="<?= $url_base ?>software/listar" class="btn btn-light"><i class="bi bi-eye"></i> Revisar software</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
    <div class="card text-bg-dark shadow">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-link"></i> Equipos sin asignación</h5>
            <p class="card-text">Hay <strong><?= $equiposSinAsignacion ?></strong> equipos sin asignación activa.</p>
            <a href="<?= $url_base ?>equipos/listarSinAsignacion" class="btn btn-light">
                <i class="bi bi-eye"></i> Ver listado
            </a>
        </div>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-bar-chart-line"></i> Estado de los equipos</h5>
                <canvas id="graficoEstadoEquipos" height="150"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-clock-history"></i> Últimas acciones</h5>
                <ul class="list-group list-group-flush">
                    <?php if (!empty($ultimosLogs)): ?>
                        <?php foreach ($ultimosLogs as $log): ?>
                            <li class="list-group-item">
                                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($log['accion']) ?>
                                <br><small class="text-muted"><?= date("d/m/Y H:i", strtotime($log['fecha'])) ?></small>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-light">No hay acciones recientes registradas.</div>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const estadoEquipos = <?= json_encode($estadoEquipos) ?>;
const labels = estadoEquipos.map(e => e.estado);
const datos = estadoEquipos.map(e => e.cantidad);

new Chart(document.getElementById('graficoEstadoEquipos'), {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            data: datos,
            backgroundColor: ['#198754', '#ffc107', '#6c757d', '#0d6efd', '#dc3545']
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Distribución por estado'
            }
        }
    }
});
</script>