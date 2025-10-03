<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var array $usuario
 */
?>
<h2 class="mb-4">✏️ Editar Usuario</h2>

<form method="POST" action="<?= $url_base ?>usuarios/editar/<?= $usuario['id_usuario'] ?>" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Cargo</label>
        <input type="text" name="cargo" class="form-control" value="<?= htmlspecialchars($usuario['cargo']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Usuario de sistema</label>
        <input type="text" name="usuario_sistema" class="form-control" value="<?= htmlspecialchars($usuario['usuario_sistema']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Contraseña</label>
        <input type="password" name="contrasena" class="form-control" value="<?= htmlspecialchars($usuario['contrasena']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Área</label>
        <select name="area" class="form-select">
            <option value="">Seleccione un área</option>
            <?php foreach ($areas as $area): ?>
                <option value="<?= htmlspecialchars($area['nombre_area']) ?>"
                    <?= $usuario['id_area'] == $area['id_area'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($area['nombre_area']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
            <option value="Activo" <?= $usuario['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
            <option value="Inactivo" <?= $usuario['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>
    <div class="col-12 text-end">
        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar cambios</button>
        <button type="button" class="btn btn-secondary"
                            onclick="location.href='<?= $url_base ?>usuarios/listar';">
                        <i class="bi bi-arrow-left"></i> Volver
        </button>
    </div>
</form>