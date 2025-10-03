<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var mysqli_result $resultado
 * @var string $mensaje
 * @var string $error
 */
?>

<?php if (!empty($mensaje)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $mensaje ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-gear-fill"></i> <?= htmlspecialchars($titulo) ?></h2>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Agregar nuevo sistema</h5>
        <form action="<?= $url_base ?>catalogos/registrarSistema" method="POST" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="nombre_sistema" class="form-control" placeholder="Nombre del nuevo sistema operativo" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-plus-circle"></i> Agregar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Sistema</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($sistema = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($sistema['id_sistema']) ?></td>
                            <td><?= htmlspecialchars($sistema['nombre_sistema']) ?></td>
                            <td class="text-center">
                                <a href="<?= $url_base ?>catalogos/editarSistema/<?= $sistema['id_sistema'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="<?= $url_base ?>catalogos/eliminarSistema/<?= $sistema['id_sistema'] ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   title="Eliminar"
                                   onclick="return confirm('¿Está seguro de que desea eliminar el sistema \'<?= htmlspecialchars($sistema['nombre_sistema']) ?>\'?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>