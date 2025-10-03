<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var mysqli_result $resultado
 */
?>
<h2 class="mb-4">
    <i class="bi bi-calendar-check"></i> <?= htmlspecialchars($titulo) ?>
</h2>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Equipo</th>
                        <th>Fecha Programada</th>
                        <th>Estado</th>
                        <th>Motivos</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($m = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['nombre_pc']) ?></td>
                                <td><?= date("d/m/Y", strtotime($m['fecha_programada'])) ?></td>
                                <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($m['estado_programacion']) ?></span></td>
                                <td><?= htmlspecialchars(substr($m['motivos'], 0, 50)) ?>...</td>
                                <td class="text-center">
                                    </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay mantenimientos programados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>