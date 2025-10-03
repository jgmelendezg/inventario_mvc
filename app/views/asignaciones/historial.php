<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var mysqli_result $resultado
 * @var string $mensaje
 * @var string $error
 */
?>

<?php if (!empty($mensaje)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $mensaje ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-clock-history"></i> <?= htmlspecialchars($titulo) ?></h2>
    <a href="<?= $url_base ?>asignaciones/asignar" class="btn btn-primary">
        <i class="bi bi-link"></i> Asignar Equipo
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Equipo</th>
                        <th>Usuario</th>
                        <th>Fecha Asignación</th>
                        <th>Fecha Desasignación</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($a = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['nombre_pc']) ?></td>
                                <td><?= htmlspecialchars($a['nombre_usuario']) ?></td>
                                <td><?= date("d/m/Y", strtotime($a['fecha_asignacion'])) ?></td>
                                <td>
                                    <?= !empty($a['fecha_desasignacion']) ? date("d/m/Y", strtotime($a['fecha_desasignacion'])) : '—' ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $a['estado'] == 'Activo' ? 'success' : 'secondary' ?>">
                                        <?= htmlspecialchars($a['estado']) ?>
                                    </span>
                                </td>
                                <td><?= !empty($a['observaciones'] ? htmlspecialchars($a['observaciones']) : htmlspecialchars($a['nombre_pc']) ) ?></td>
                                <td class="text-center">
                                    <?php if ($a['estado'] == 'Activo'): ?>
                                        <a href="<?= $url_base ?>asignaciones/desasignar/<?= $a['id_asignacion'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Desasignar Equipo"
                                           onclick="return confirm('¿Está seguro de que desea desasignar este equipo?');">
                                            <i class="bi bi-x-circle"></i> Desasignar
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Finalizado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No se encontraron asignaciones.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>