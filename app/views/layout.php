<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var string $contenido
 * @var string $ruta
 */

$titulo = $titulo ?? "Sistema de Inventario";
$ruta = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($titulo ?? 'Inventario') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $url_base ?>public/css/estilos.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-12 col-md-2 sidebar bg-dark text-white p-4">
                <h2 class="mb-4"><i class="bi bi-list"></i> Menú</h2>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center text-white <?= strpos($ruta, 'dashboard') !== false ? 'fw-bold' : '' ?>" href="<?= $url_base ?>dashboard">
                            <i class="bi bi-speedometer me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center text-white <?= strpos($ruta, 'equipos') !== false ? 'fw-bold' : '' ?>" href="<?= $url_base ?>equipos/listar">
                            <i class="bi bi-pc-display me-2"></i> Equipos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center text-white <?= strpos($ruta, 'catalogos') !== false ? 'fw-bold' : '' ?>" href="#catalogo-submenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="catalogo-submenu">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-gear me-2"></i> Catálogos
                            </span>
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="collapse" id="catalogo-submenu">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="<?= $url_base ?>catalogos/areas" class="link-light rounded d-block py-1 ps-3">Áreas</a></li>
                                <li><a href="<?= $url_base ?>catalogos/sistemas" class="link-light rounded d-block py-1 ps-3">Sistemas Operativos</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center text-white <?= strpos($ruta, 'usuarios') !== false ? 'fw-bold' : '' ?>" href="<?= $url_base ?>usuarios/listar">
                            <i class="bi bi-person me-2"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center text-white <?= (strpos($ruta, 'asignaciones') !== false || strpos($ruta, 'software_asignacion') !== false) ? 'fw-bold' : '' ?>" href="#asignaciones-submenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="asignaciones-submenu">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-link me-2"></i> Asignaciones
                            </span>
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="collapse" id="asignaciones-submenu">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="<?= $url_base ?>asignaciones/historial" class="link-light rounded d-block py-1 ps-3">Equipos a Usuarios</a></li>
                                <li><a href="<?= $url_base ?>software_asignacion/listar" class="link-light rounded d-block py-1 ps-3">Software a Equipos</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center text-white <?= strpos($ruta, 'reportes') !== false ? 'fw-bold' : '' ?>" href="<?= $url_base ?>reportes/index">
                            <i class="bi bi-file-earmark-bar-graph me-2"></i> Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center text-white <?= strpos($ruta, 'mantenimiento') !== false ? 'fw-bold' : '' ?>" href="<?= $url_base ?>mantenimiento/listar">
                            <i class="bi bi-tools me-2"></i> Mantenimientos
                        </a>
                    </li>
                </ul>
            </aside>
            <main class="col-md-10 ms-sm-auto px-md-4">
                <?= $contenido ?? '' ?>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>