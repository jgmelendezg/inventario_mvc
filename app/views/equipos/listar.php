<?php
/**
 * @var string $titulo
 * @var array $resultado
 * @var string $url_base
 * @var array  $filtros
 * @var int    $total_registros
 * @var int    $pagina_actual
 * @var int    $total_paginas
 * @var int    $mostrando_desde
 * @var int    $mostrando_hasta
 * @var string $mensaje
 * @var string $error
 * @var array  $areas
 */
?>

<?php if (!empty($mensaje)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($mensaje) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">
            <i class="bi bi-list-ul"></i> <?= htmlspecialchars($titulo) ?>
        </h2>
        <?php if ($total_registros > 0): ?>
            <small class="text-muted">
                Mostrando <?= $mostrando_desde ?> - <?= $mostrando_hasta ?> de <?= $total_registros ?> equipos.
            </small>
        <?php endif; ?>
    </div>
    <a href="<?= htmlspecialchars($url_base) ?>equipos/registrar" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Nuevo Equipo
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form id="filtrosForm" action="<?= htmlspecialchars($url_base) ?>equipos/listar" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control form-control-sm" id="search" name="search"
                           value="<?= htmlspecialchars($filtros['search'] ?? '') ?>" placeholder="Nombre, serial...">
                </div>
                <div class="col-md-2">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select class="form-select form-select-sm" id="tipo" name="tipo">
                        <option value="">Todos</option>
                        <option value="Desktop" <?= ($filtros['tipo'] ?? '') === 'Desktop' ? 'selected' : '' ?>>Desktop</option>
                        <option value="Laptop" <?= ($filtros['tipo'] ?? '') === 'Laptop' ? 'selected' : '' ?>>Laptop</option>
                        <option value="Tablet" <?= ($filtros['tipo'] ?? '') === 'Tablet' ? 'selected' : '' ?>>Tablet</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select form-select-sm" id="estado" name="estado">
                        <option value="">Todos</option>
                        <option value="Activo" <?= ($filtros['estado'] ?? '') === 'Activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="Mantenimiento" <?= ($filtros['estado'] ?? '') === 'Mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
                        <option value="Retirado" <?= ($filtros['estado'] ?? '') === 'Retirado' ? 'selected' : '' ?>>Retirado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="area" class="form-label">Área</label>
                    <select class="form-select form-select-sm" id="area" name="area">
                        <option value="">Todas</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?= htmlspecialchars($area['id_area']) ?>"
                                <?= ($filtros['area'] ?? '') == $area['id_area'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($area['nombre_area']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="garantia" class="form-label">Garantía</label>
                    <select class="form-select form-select-sm" id="garantia" name="garantia">
                        <option value="">Todos</option>
                        <option value="por_vencer" <?= ($filtros['garantia'] ?? '') === 'por_vencer' ? 'selected' : '' ?>>Por vencer</option>
                        <option value="vencida" <?= ($filtros['garantia'] ?? '') === 'vencida' ? 'selected' : '' ?>>Vencida</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm me-2">
                        <i class="bi bi-filter"></i>
                    </button>
                    <a href="<?= htmlspecialchars($url_base) ?>equipos/listar" class="btn btn-secondary btn-sm">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive mt-4">
    <table class="table table-sm table-hover table-striped">
        <!-- SOLUCIÓN CRÍTICA: colgroup para control estricto de anchos -->
        <colgroup>
            <col style="width: 4% !important;">   <!-- ID -->
            <col style="width: 18% !important;">  <!-- Nombre PC -->
            <col style="width: 16% !important;">  <!-- Modelo -->
            <col style="width: 20% !important;">  <!-- Usuario (Cargo) -->
            <col style="width: 14% !important;">  <!-- Área Asignada -->
            <col style="width: 10% !important;">  <!-- Garantía -->
            <col style="width: 8% !important;">   <!-- Estado -->
            <col style="width: 10% !important;">  <!-- Acciones -->
        </colgroup>
        <thead class="bg-dark text-white">
            <tr>
                <th class="col-id">ID</th>
                <th>Nombre PC</th>
                <th>Modelo</th>
                <th>Usuario (Cargo)</th>
                <th>Área Asignada</th>
                <th>Garantía</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($resultado)): ?>
                <tr>
                    <td colspan="8" class="text-center">No se encontraron equipos.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($resultado as $equipo): ?>
                    <tr>
                        <td class="col-id"><?= htmlspecialchars($equipo['id_equipo'] ?? '') ?></td>
                        <td class="nombre-pc">
                            <div>
                                <strong class="text-truncate-table" title="<?= htmlspecialchars($equipo['nombre_pc']) ?>">
                                    <?= htmlspecialchars($equipo['nombre_pc']) ?>
                                </strong>
                                <?php if (!empty($equipo['tipo'])): ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($equipo['tipo']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="modelo">
                            <div>
                                <strong class="text-truncate-table" title="<?= htmlspecialchars($equipo['modelo']) ?>">
                                    <?= htmlspecialchars($equipo['modelo']) ?>
                                </strong>
                                <?php if (!empty($equipo['service_tag'])): ?>
                                    <br>
                                    <small class="text-muted text-truncate-table" title="<?= htmlspecialchars($equipo['service_tag']) ?>">
                                        <?= htmlspecialchars($equipo['service_tag']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($equipo['nombre_usuario'])): ?>
                                <div class="usuario-info">
                                    <span class="usuario-nombre nombre-completo" title="<?= htmlspecialchars($equipo['nombre_usuario']) ?>">
                                        <?= htmlspecialchars($equipo['nombre_usuario']) ?>
                                    </span>
                                    <br>
                                    <small class="usuario-cargo cargo" title="<?= htmlspecialchars($equipo['cargo_usuario']) ?>">
                                        <?= htmlspecialchars($equipo['cargo_usuario']) ?>
                                    </small>
                                </div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Sin Asignación</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="text-truncate-table" title="<?= htmlspecialchars($equipo['area_asignada'] ?? '') ?>">
                                <?= htmlspecialchars($equipo['area_asignada'] ?? '') ?>
                            </span>
                        </td>
                        <td>
                            <span class="text-truncate-table" title="<?= htmlspecialchars($equipo['fecha_garantia'] ?? '') ?>">
                                <?= htmlspecialchars($equipo['fecha_garantia'] ?? '') ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $estado_class = 'secondary';
                            if ($equipo['estado'] === 'Activo') $estado_class = 'success';
                            elseif ($equipo['estado'] === 'Mantenimiento') $estado_class = 'warning';
                            ?>
                            <span class="badge bg-<?= $estado_class ?> estado-badge">
                                <?= htmlspecialchars($equipo['estado'] ?? '') ?>
                            </span>
                        </td>
                        <td>
                            <div class="acciones-grupo">
                                <a href="<?= $url_base ?>equipos/ver/<?= htmlspecialchars($equipo['id_equipo'] ?? '') ?>" 
                                   class="btn btn-info btn-sm" 
                                   title="Ver equipo" 
                                   data-bs-toggle="tooltip">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= $url_base ?>equipos/editar/<?= htmlspecialchars($equipo['id_equipo'] ?? '') ?>" 
                                   class="btn btn-warning btn-sm" 
                                   title="Editar equipo" 
                                   data-bs-toggle="tooltip">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= $url_base ?>equipos/eliminar/<?= htmlspecialchars($equipo['id_equipo'] ?? '') ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirmarEliminacion('<?= htmlspecialchars($equipo['nombre_pc'] ?? '', ENT_QUOTES) ?>');" 
                                   title="Retirar equipo" 
                                   data-bs-toggle="tooltip">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($total_paginas > 1): ?>
    <nav>
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Página <?= $pagina_actual ?> de <?= $total_paginas ?>
            </small>
            <ul class="pagination pagination-sm m-0">
                <?php if ($pagina_actual > 1): ?>
                    <li class="page-item">
                        <a class="page-link"
                           href="<?= $url_base ?>equipos/listar?pagina=1&<?= http_build_query($filtros) ?>"
                           title="Primera página">
                            <i class="bi bi-chevron-double-left"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link"
                           href="<?= $url_base ?>equipos/listar?pagina=<?= $pagina_actual - 1 ?>&<?= http_build_query($filtros) ?>"
                           title="Anterior">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>

                <?php
                $inicio = max(1, $pagina_actual - 2);
                $fin = min($total_paginas, $pagina_actual + 2);
                for ($i = $inicio; $i <= $fin; $i++):
                    ?>
                    <li class="page-item <?= ($i == $pagina_actual) ? 'active' : '' ?>">
                        <a class="page-link"
                           href="<?= $url_base ?>equipos/listar?pagina=<?= $i ?>&<?= http_build_query($filtros) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link"
                           href="<?= $url_base ?>equipos/listar?pagina=<?= $pagina_actual + 1 ?>&<?= http_build_query($filtros) ?>"
                           title="Siguiente">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link"
                           href="<?= $url_base ?>equipos/listar?pagina=<?= $total_paginas ?>&<?= http_build_query($filtros) ?>"
                           title="Última página">
                            <i class="bi bi-chevron-double-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <br>
<?php endif; ?>

<script>
// Confirmación para retirar equipos
function confirmarEliminacion(nombreEquipo) {
    return confirm(`¿Está seguro de que desea retirar el equipo "${nombreEquipo}"?nnEsta acción cambiará su estado a "Retirado".`);
}

// Auto-envío del formulario de filtros con debounce
document.addEventListener('DOMContentLoaded', function() {
    const filtros = document.querySelectorAll('#filtrosForm input[type="text"], #filtrosForm select');
    let timeoutId;
    
    filtros.forEach(function(input) {
        input.addEventListener('change', function() {
            document.getElementById('filtrosForm').submit();
        });
        // Solo aplica el debounce a los campos de texto
        if (input.type === 'text') {
            input.addEventListener('input', function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(function() {
                    // Solo auto-enviar si hay al menos 2 caracteres o si está vacío
                    if (input.value.length >= 2 || input.value.length === 0) {
                        document.getElementById('filtrosForm').submit();
                    }
                }, 1000); // Esperar 1 segundo después de dejar de escribir
            });
        }
    });
    
    // Inicializar tooltips de Bootstrap si están disponibles
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>