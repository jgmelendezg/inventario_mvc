<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var mysqli_result $resultado
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-cpu"></i> <?= htmlspecialchars($titulo) ?></h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre del Programa</th>
                        <th scope="col">Clave de Licencia</th>
                        </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($programa = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($programa['id_programa']) ?></td>
                                <td><?= htmlspecialchars($programa['nombre_programa']) ?></td>
                                <td><?= htmlspecialchars($programa['clave_licencia'] ?: 'No asignada') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">No se encontraron programas registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>