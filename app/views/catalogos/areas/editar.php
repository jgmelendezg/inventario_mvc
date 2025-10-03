<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var array  $area
 */
?>
<h2 class="mb-4">?? Editar Área</h2>

<form action="<?= $url_base ?>catalogos/editarArea/<?= $area['id_area'] ?>" method="POST" class="card p-4">
    <div class="mb-3">
        <label for="nombre_area" class="form-label">Nombre del Área</label>
        <input type="text" id="nombre_area" name="nombre_area" class="form-control"
               value="<?= htmlspecialchars($area['nombre_area']) ?>" required>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Guardar Cambios
        </button>
        <a href="<?= $url_base ?>catalogos/areas" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Cancelar
        </a>
    </div>
</form>