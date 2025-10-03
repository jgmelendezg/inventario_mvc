<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var mysqli_result $resultado
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-list-ul"></i> <?= htmlspecialchars($titulo) ?></h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">
                            <i class="bi bi-pc-display"></i> Nombre PC
                        </th>
                        <th scope="col">
                            <i class="bi bi-laptop"></i> Tipo
                        </th>
                        <th scope="col">
                            <i class="bi bi-building"></i> Área
                        </th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($equipo = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($equipo['id_equipo']) ?></td>
                                <td><strong><?= htmlspecialchars($equipo['nombre_pc']) ?></strong></td>
                                <td><?= htmlspecialchars($equipo['tipo']) ?></td>
                                <td><?= htmlspecialchars($equipo['area']) ?></td>
                                <td class="text-center">
                                    <a href="<?= $url_base ?>asignaciones/asignar" class="btn btn-sm btn-outline-success" title="Asignar este equipo">
                                        <i class="bi bi-link"></i> Asignar
                                    </a>
                                    <a href="<?= $url_base ?>equipos/ver/<?= $equipo['id_equipo'] ?>" class="btn btn-sm btn-outline-info" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay equipos activos sin asignación.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>