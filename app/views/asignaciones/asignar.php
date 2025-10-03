<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var mysqli_result $equipos
 * @var mysqli_result $usuarios
 */
?>

<h2 class="mb-4">🔗 Asignar Equipo a Usuario</h2>

<form method="POST" action="<?= $url_base ?>asignaciones/asignar" class="row g-3">
    <div class="col-md-6">
        <label for="id_equipo" class="form-label">Equipo:</label>
        <select name="id_equipo" id="id_equipo" class="form-select" required>
            <?php while ($eq = $equipos->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($eq['id_equipo']) ?>">
                    <?= htmlspecialchars($eq['nombre_pc']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label for="id_usuario" class="form-label">Usuario:</label>
        <select name="id_usuario" id="id_usuario" class="form-select" required>
            <?php while ($us = $usuarios->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($us['id_usuario']) ?>">
                    <?= htmlspecialchars($us['nombre']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-12">
        <label for="fecha_asignacion" class="form-label">Fecha de asignación:</label>
        <input type="date" name="fecha_asignacion" id="fecha_asignacion" class="form-control" required>
    </div>

    <div class="col-md-12">
        <label for="observaciones" class="form-label">Observaciones:</label>
        <textarea name="observaciones" id="observaciones" class="form-control" rows="3"></textarea>
    </div>

    <div class="col-12 text-end">
        <button type="submit" class="btn btn-success"><i class="bi bi-link"></i> Asignar</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= $url_base ?>asignaciones/historial'">
            <i class="bi bi-arrow-left"></i> Volver 
        </button>
    </div>
</form>