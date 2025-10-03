<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var mysqli_result $equipos
 * @var mysqli_result $programas
 */
?>

<h2 class="mb-4">🔗 Asignar Software a Equipo</h2>

<form method="POST" action="<?= $url_base ?>software_asignacion/asignar" class="row g-3">
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
        <label for="id_programa" class="form-label">Software:</label>
        <select name="id_programa" id="id_programa" class="form-select" required>
            <?php while ($pr = $programas->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($pr['id_programa']) ?>">
                    <?= htmlspecialchars($pr['nombre_programa']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-12">
        <label for="fecha_instalacion" class="form-label">Fecha de Instalación:</label>
        <input type="date" name="fecha_instalacion" id="fecha_instalacion" class="form-control" required>
    </div>

    <div class="col-md-12">
        <label for="observaciones" class="form-label">Observaciones:</label>
        <textarea name="observaciones" id="observaciones" class="form-control" rows="3"></textarea>
    </div>

    <div class="col-12 text-end">
        <button type="submit" class="btn btn-success"><i class="bi bi-link"></i> Asignar</button>
        <a href="<?= $url_base ?>software_asignacion/listar" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</form>