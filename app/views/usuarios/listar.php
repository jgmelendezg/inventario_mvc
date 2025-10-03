<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var array $resultado
 * @var array $filtros
 * @var int $total_registros
 * @var int $pagina_actual
 * @var int $total_paginas
 * @var int $mostrando_desde
 * @var int $mostrando_hasta
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
    <div>
        <h2 class="mb-0"><i class="bi bi-person-lines-fill"></i> <?= htmlspecialchars($titulo) ?></h2>
        <?php if ($total_registros > 0): ?>
            <small class="text-muted">
                Total de usuarios: <?= $total_registros ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $url_base ?>usuarios/registrar" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-funnel"></i> Filtros de Búsqueda</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3" id="filtrosForm">
            <div class="col-md-6">
                <label for="area" class="form-label">Área</label>
                <input type="text" id="area" name="area" class="form-control" placeholder="Ej: IT, Contabilidad..." value="<?= htmlspecialchars($filtros['area']) ?>">
            </div>
            <div class="col-md-6">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select">
                    <option value="">-- Todos los estados --</option>
                    <option value="Activo" <?= $filtros['estado'] == "Activo" ? "selected" : "" ?>>🟢 Activo</option>
                    <option value="Inactivo" <?= $filtros['estado'] == "Inactivo" ? "selected" : "" ?>>🔴 Inactivo</option>
                </select>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="location.href='<?= $url_base ?>usuarios/listar';">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                    </button>
                </div>
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
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Área</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_registros > 0): ?>
                        <?php foreach ($resultado as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                                <td class="nombre-completo"><?= htmlspecialchars($usuario['nombre']) ?></td>
                                <td class="cargo"><?= htmlspecialchars($usuario['cargo']) ?></td>
                                <td><?= htmlspecialchars($usuario['usuario_sistema']) ?></td>
                                <td><?= htmlspecialchars($usuario['correo']) ?></td>
                                <td><?= htmlspecialchars($usuario['area']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $usuario['estado'] == 'Activo' ? 'success' : 'danger' ?>">
                                        <?= htmlspecialchars($usuario['estado']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?= $url_base ?>usuarios/editar/<?= $usuario['id_usuario'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <?php if ($usuario['estado'] == 'Activo'): ?>
                                            <a href="<?= $url_base ?>usuarios/inactivar/<?= $usuario['id_usuario'] ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               title="Inactivar"
                                               onclick="return confirm('¿Está seguro de que desea inactivar a <?= htmlspecialchars($usuario['nombre']) ?>?');">
                                                <i class="bi bi-person-x"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No se encontraron usuarios.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>