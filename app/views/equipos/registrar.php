<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var array  $areas      ['id_area'=>'1', 'nombre_area'=>'IT']
 * @var array  $sistemas   ['id_sistema'=>'1', 'nombre_sistema'=>'Windows 11']
 */
?>
<h2 class="mb-4">📋 Registrar Equipo</h2>

<form method="POST" class="row g-3">
    <!-- 1. Nombre PC -->
    <div class="col-md-6">
        <label class="form-label">Nombre del PC *</label>
        <input type="text" name="nombre_pc" class="form-control"
               placeholder="Ej: DESARROLLO-01" required minlength="3"
               value="<?= htmlspecialchars($_POST['nombre_pc'] ?? '') ?>">
    </div>

    <!-- 2. Tipo -->
    <div class="col-md-6">
        <label class="form-label">Tipo *</label>
        <select name="tipo" class="form-select" required>
            <?php
            $tipos = ['Portátil', 'Desktop', 'Tablet', 'Servidor', 'Impresora', 'Monitor'];
            foreach ($tipos as $t) :
                $sel = ($_POST['tipo'] ?? '') === $t ? 'selected' : '';
                echo "<option value='{$t}' {$sel}>{$t}</option>";
            endforeach;
            ?>
        </select>
    </div>

    <!-- 3. ÁREA → COMBO -->
    <div class="col-md-6">
        <label class="form-label">Área</label>
        <select name="area" class="form-select">
            <option value="">-- Sin área --</option>
            <?php foreach ($areas as $a) :
                $sel = ($_POST['area'] ?? '') === $a['nombre_area'] ? 'selected' : '';
            ?>
                <option value="<?= htmlspecialchars($a['nombre_area']) ?>" <?= $sel ?>>
                    <?= htmlspecialchars($a['nombre_area']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- 4. Estado -->
    <div class="col-md-6">
        <label class="form-label">Estado inicial</label>
        <select name="estado" class="form-select">
            <?php
            $estados = ['Activo' => '🟢', 'Mantenimiento' => '🟡', 'Retirado' => '🔴'];
            foreach ($estados as $txt => $ico) :
                $sel = ($_POST['estado'] ?? 'Activo') === $txt ? 'selected' : '';
                echo "<option value='{$txt}' {$sel}>{$ico} {$txt}</option>";
            endforeach;
            ?>
        </select>
    </div>

    <!-- 5. IP -->
    <div class="col-md-6">
        <label class="form-label">IP</label>
        <input type="text" name="ip" class="form-control"
               placeholder="10.20.1.100"
               value="<?= htmlspecialchars($_POST['ip'] ?? '') ?>">
    </div>

    <!-- 6. MAC -->
    <div class="col-md-6">
        <label class="form-label">MAC</label>
        <input type="text" name="mac" class="form-control"
               placeholder="XX:XX:XX:XX:XX:XX"
               value="<?= htmlspecialchars($_POST['mac'] ?? '') ?>">
    </div>

    <!-- 7. Service Tag -->
    <div class="col-md-6">
        <label class="form-label">Service Tag</label>
        <input type="text" name="service_tag" class="form-control"
               placeholder="ABC123456"
               value="<?= htmlspecialchars($_POST['service_tag'] ?? '') ?>">
    </div>

    <!-- 8. Modelo -->
    <div class="col-md-6">
        <label class="form-label">Modelo *</label>
        <input type="text" name="modelo" class="form-control"
               placeholder="Latitude 3440"
               value="<?= htmlspecialchars($_POST['modelo'] ?? '') ?>">
    </div>

    <!-- 9. SISTEMA OPERATIVO → COMBO -->
    <div class="col-md-6">
        <label class="form-label">Sistema Operativo</label>
        <select name="sistema_operativo" class="form-select">
            <option value="">-- Sin sistema --</option>
            <?php foreach ($sistemas as $s) :
                $sel = ($_POST['sistema_operativo'] ?? '') === $s['nombre_sistema'] ? 'selected' : '';
            ?>
                <option value="<?= htmlspecialchars($s['nombre_sistema']) ?>" <?= $sel ?>>
                    <?= htmlspecialchars($s['nombre_sistema']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- 10. Clave de licencia -->
    <div class="col-md-6">
        <label class="form-label">Clave de licencia</label>
        <input type="text" name="clave_licencia" class="form-control"
               placeholder="XXXXX-XXXXX-XXXXX-XXXXX"
               value="<?= htmlspecialchars($_POST['clave_licencia'] ?? '') ?>">
    </div>

    <!-- 11. Fecha de garantía -->
    <div class="col-md-6">
        <label class="form-label">Fin de garantía</label>
        <input type="date" name="fecha_garantia" class="form-control"
               value="<?= htmlspecialchars($_POST['fecha_garantia'] ?? '') ?>">
    </div>

    <!-- 12. Observaciones -->
    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="3"
                  placeholder="Notas adicionales"><?= htmlspecialchars($_POST['observaciones'] ?? '') ?></textarea>
    </div>

    <!-- BOTONES -->
    <div class="col-12 text-end">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Registrar
        </button>
        <a href="<?= $url_base ?>listar" class="btn btn-outline-secondary">Cancelar</a>
        <a href="<?= $url_base ?>listar" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>
</form>
</br>