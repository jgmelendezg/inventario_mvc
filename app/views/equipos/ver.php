<?php
/**
 * @var string $titulo
 * @var array  $equipo        // devuelto por obtenerEquipoPorId
 * @var string $url_base
 * @var int|null $diasTranscurridos // Nuevo
 */
?>
<h2 class="mb-4">👁️ Detalles del Equipo</h2>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title text-primary">
            <i class="bi bi-pc-display"></i> <?= htmlspecialchars($equipo['nombre_pc']) ?>
        </h5>

        <div class="row mb-3">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong><i class="bi bi-laptop"></i> Tipo:</strong>
                        <span class="badge bg-secondary"><?= htmlspecialchars($equipo['tipo']) ?></span>
                    </li>
                    <li class="list-group-item">
                        <strong><i class="bi bi-circle-fill"></i> Estado:</strong>
                        <?php
                        $badge = match ($equipo['estado']) {
                            'Activo' => 'success',
                            'Mantenimiento' => 'warning text-dark',
                            'Retirado' => 'danger',
                            default => 'secondary'
                        };
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($equipo['estado']) ?></span>
                    </li>
                    <li class="list-group-item">
                        <strong><i class="bi bi-geo-alt"></i> Área:</strong>
                        <?= htmlspecialchars($equipo['area'] ?: 'No asignada') ?>
                    </li>
                    <li class="list-group-item">
                        <strong><i class="bi bi-cpu"></i> Modelo:</strong>
                        <?= htmlspecialchars($equipo['modelo']) ?>
                    </li>
                    <li class="list-group-item">
                        <strong><i class="bi bi-upc"></i> Service Tag:</strong>
                        <?= htmlspecialchars($equipo['service_tag'] ?: 'No registrado') ?>
                    </li>
                </ul>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong><i class="bi bi-wifi"></i> IP:</strong>
                        <?= htmlspecialchars($equipo['ip'] ?: 'No asignada') ?>
                    </li>
                    <li class="list-group-item">
                        <strong><i class="bi bi-ethernet"></i> MAC:</strong>
                        <?= htmlspecialchars($equipo['mac'] ?: 'No registrada') ?>
                    </li>
                    <li class="list-group-item">
                        <strong><i class="bi bi-windows"></i> Sistema Operativo:</strong>
                        <?= htmlspecialchars($equipo['sistema_operativo'] ?: 'No especificado') ?>
                    </li>
                    <li class="list-group-item">
                        <strong><i class="bi bi-key"></i> Clave de licencia:</strong>
                        <span class="text-muted font-monospace"><?= htmlspecialchars($equipo['clave_licencia'] ?: 'No registrada') ?></span>
                    </li>
                    <li class="list-group-item">
                        <?php if (!empty($equipo['fecha_garantia']) && $equipo['fecha_garantia'] !== '0000-00-00'): ?>
                            <li class="list-group-item">
                                <strong><i class="bi bi-calendar-check"></i> Fin de garantía:</strong>
                                <?php
                                $f = new DateTime($equipo['fecha_garantia']);
                                $hoy = new DateTime();
                                $dias = $hoy->diff($f)->days;
                                $txt = $f >= $hoy ? "Faltan $dias días" : "Vencida";
                                $col = $f >= $hoy ? 'text-success' : 'text-danger';
                                echo "<span class='$col'>{$f->format('d/m/Y')} ($txt)</span>";
                                ?>
                            </li>
                        <?php else: ?>
                            <li class="list-group-item">
                                <strong><i class="bi bi-calendar-x"></i> Fin de garantía:</strong>
                                <span class="text-muted">Sin garantía registrada</span>
                            </li>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Observaciones -->
        <?php if ($equipo['observaciones']): ?>
            <div class="alert alert-secondary mb-0" role="alert">
                <strong><i class="bi bi-chat-left-text"></i> Observaciones:</strong><br>
                <?= nl2br(htmlspecialchars($equipo['observaciones'])) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<h2 class="mb-4"><i class="bi bi-tools"></i> Mantenimiento del Equipo</h2>

<div class="card p-4 mb-4">
    <div class="row">
        <div class="col-md-6">
            <strong>Último Mantenimiento:</strong> 
            <?php if ($diasTranscurridos !== null): ?>
                <span class="badge bg-success">Hace <?= $diasTranscurridos ?> días</span>
            <?php else: ?>
                <span class="badge bg-secondary">Sin mantenimiento registrado</span>
            <?php endif; ?>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= $url_base ?>mantenimiento/programar/<?= $equipo['id_equipo'] ?>" class="btn btn-outline-success">
                <i class="bi bi-calendar-plus"></i> Programar Mantenimiento
            </a>
        </div>
    </div>
</div>

<!-- Botones de acción -->
<div class="text-end">
    <a href="<?= $url_base ?>equipos/editar/<?= $equipo['id_equipo'] ?>" class="btn btn-warning">
        <i class="bi bi-pencil"></i> Editar
    </a>
    <a href="<?= $url_base ?>mantenimiento/historial/<?= $equipo['id_equipo'] ?>" class="btn btn-outline-primary me-2">
        <i class="bi bi-tools"></i> Ver Historial de Mantenimiento
    </a>
    <a href="<?= $url_base ?>listar" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </a>

</div>