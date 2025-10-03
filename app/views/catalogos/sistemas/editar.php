<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var array  $sistema
 */
?>
<h2 class="mb-4">?? Editar Sistema Operativo</h2>

<form action="<?= $url_base ?>catalogos/editarSistema/<?= $sistema['id_sistema'] ?>" method="POST" class="card p-4">
    <div class="mb-3">
        <label for="nombre_sistema" class="form-label">Nombre del Sistema</label>
        <input type="text" id="nombre_sistema" name="nombre_sistema" class="form-control"
               value="<?= htmlspecialchars($sistema['nombre_sistema']) ?>" required>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Guardar Cambios
        </button>
        <a href="<?= $url_base ?>catalogos/sistemas" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Cancelar
        </a>
    </div>
</form>