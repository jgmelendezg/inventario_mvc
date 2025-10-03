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
    <h2 class="mb-0"><i class="bi bi-gear-fill"></i> <?= htmlspecialchars($titulo) ?></h2>
    <a href="<?= $url_base ?>software_asignacion/asignar" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Asignar Software
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Equipo</th>
                        <th>Programa</th>
                        <th>Fecha de Instalación</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($asignacion = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($asignacion['id_asignacion_software']) ?></td>
                                <td><strong><?= htmlspecialchars($asignacion['nombre_pc']) ?></strong></td>
                                <td><?= htmlspecialchars($asignacion['nombre_programa']) ?></td>
                                <td><?= date("d/m/Y", strtotime($asignacion['fecha_instalacion'])) ?></td>
                                <td><?= htmlspecialchars($asignacion['observaciones'] ?: 'N/A') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No se encontraron asignaciones de software.</td>
                        </tr>
                    <?php endif; ?>
                    <a href="<?= $url_base ?>listar" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al listado
                    </a>
                </tbody>
            </table>
        </div>
    </div>
</div>