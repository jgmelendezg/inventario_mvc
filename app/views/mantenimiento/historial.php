<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var array $equipo
 * @var mysqli_result $resultado
 */
?>
<h2 class="mb-4">
    <i class="bi bi-tools"></i> <?= htmlspecialchars($titulo) ?> para <?= htmlspecialchars($equipo['nombre_pc']) ?>
</h2>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Mantenimiento</th>
                        <th>Técnico</th>
                        <th>Tipo</th>
                        <th>Motivos</th>
                        <th>Diagnóstico</th>
                        <th>Soluciones</th>
                        <th>Recomendaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($m = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['id_mantenimiento']) ?></td>
                                <td><?= date("d/m/Y", strtotime($m['fecha_solicitud'])) ?></td>
                                <td><?= date("d/m/Y", strtotime($m['fecha_mantenimiento'])) ?></td>
                                <td><?= htmlspecialchars($m['nombre_tecnico']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($m['tipo_mantenimiento']) ?></span></td>
                                <td>
                                <?php
                                    $motivos = json_decode($m['motivos_json'], true);
                                    if ($motivos) {
                                        foreach ($motivos as $categoria => $lista) {
                                            echo "<strong>" . ucfirst(str_replace('_', ' ', $categoria)) . ":</strong> " . implode(', ', $lista) . "<br>";
                                        }
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($m['diagnostico']) ?></td>
                                <td><?= htmlspecialchars($m['soluciones']) ?></td>
                                <td><?= htmlspecialchars($m['recomendaciones']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No se encontraron registros de mantenimiento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="<?= $url_base ?>equipos/ver/<?= $equipo['id_equipo'] ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver a la ficha del equipo
    </a>
</div>