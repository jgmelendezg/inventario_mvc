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
    <h2 class="mb-0"><i class="bi bi-tools"></i> <?= htmlspecialchars($titulo) ?></h2>
    <a href="<?= $url_base ?>mantenimiento/registrar" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Registrar Mantenimiento
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Equipo</th>
                        <th scope="col">Técnico</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Motivos</th>
                        <th scope="col">Diagnóstico</th>
                        <th scope="col">Soluciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($mantenimiento = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($mantenimiento['id_mantenimiento']) ?></td>
                                <td><strong><?= htmlspecialchars($mantenimiento['nombre_pc']) ?></strong></td>
                                <td><?= htmlspecialchars($mantenimiento['nombre_tecnico']) ?></td>
                                <td><?= date("d/m/Y", strtotime($mantenimiento['fecha_solicitud'])) ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($mantenimiento['tipo_mantenimiento']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $motivos = json_decode($mantenimiento['motivos_json'], true);
                                    if ($motivos) {
                                        foreach ($motivos as $categoria => $lista) {
                                            echo "<strong>" . ucfirst(str_replace('_', ' ', $categoria)) . ":</strong> " . implode(', ', $lista) . "<br>";
                                        }
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars(substr($mantenimiento['diagnostico'], 0, 50)) . '...' ?></td>
                                <td><?= htmlspecialchars(substr($mantenimiento['soluciones'], 0, 50)) . '...' ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No se encontraron registros de mantenimiento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>