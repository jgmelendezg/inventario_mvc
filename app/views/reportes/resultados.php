<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var mysqli_result $resultado
 * @var string $reporte
 */
?>
<h2 class="mb-4"><i class="bi bi-clipboard-data"></i> <?= htmlspecialchars($titulo) ?></h2>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <?php if ($reporte === 'detalle'): ?>
                            <th>ID</th>
                            <th>Nombre PC</th>
                            <th>Estado</th>
                            <th>Área</th>
                            <th>Usuario Asignado</th>
                            <th>Software</th>
                        <?php elseif ($reporte === 'mantenimientos'): ?>
                            <th>ID Mant.</th>
                            <th>Equipo</th>
                            <th>Fecha</th>
                            <th>Técnico</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                        <?php else: ?>
                            <th>ID</th>
                            <th>Nombre PC</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Área</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <?php if ($reporte === 'detalle'): ?>
                                    <td><?= htmlspecialchars($fila['id_equipo']) ?></td>
                                    <td><?= htmlspecialchars($fila['nombre_pc']) ?></td>
                                    <td><?= htmlspecialchars($fila['estado']) ?></td>
                                    <td><?= htmlspecialchars($fila['area']) ?></td>
                                    <td><?= htmlspecialchars($fila['nombre_usuario'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($fila['id_programa'] ?? 'N/A') ?></td>
                                <?php elseif ($reporte === 'mantenimientos'): ?>
                                    <td><?= htmlspecialchars($fila['id_mantenimiento']) ?></td>
                                    <td><?= htmlspecialchars($fila['nombre_pc']) ?></td>
                                    <td><?= htmlspecialchars($fila['fecha_mantenimiento']) ?></td>
                                    <td><?= htmlspecialchars($fila['nombre_tecnico']) ?></td>
                                    <td><?= htmlspecialchars($fila['tipo_mantenimiento']) ?></td>
                                    <td><?= htmlspecialchars($fila['estado_programacion']) ?></td>
                                <?php else: ?>
                                    <td><?= htmlspecialchars($fila['id_equipo']) ?></td>
                                    <td><?= htmlspecialchars($fila['nombre_pc']) ?></td>
                                    <td><?= htmlspecialchars($fila['tipo']) ?></td>
                                    <td><?= htmlspecialchars($fila['estado']) ?></td>
                                    <td><?= htmlspecialchars($fila['area']) ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No se encontraron resultados para este reporte.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="<?= $url_base ?>reportes/index" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver a Opciones de Reporte
    </a>
</div>