<?php
/**
 * @var string $titulo
 * @var array  $equipo
 * @var string $url_base
 * @var array  $areas      ['id_area'=>'1', 'nombre_area'=>'IT']
 * @var array  $sistemas   ['id_sistema'=>'1', 'nombre_sistema'=>'Windows 11']
 */
?>
<h2 class="mb-4">🛠️ Editar Equipo</h2>

<form method="POST" class="row g-3">
    <!-- 1. Nombre PC -->
    <div class="col-md-6">
        <label class="form-label">Nombre del PC *</label>
        <input type="text" name="nombre_pc" class="form-control"
               placeholder="Ej: DESARROLLO-01" required minlength="3"
               value="<?= htmlspecialchars($equipo['nombre_pc'] ?? '') ?>">
    </div>

    <!-- 2. Tipo -->
    <div class="col-md-6">
        <label class="form-label">Tipo *</label>
        <select name="tipo" class="form-select" required>
            <?php
            $tipos = ['Portátil', 'Desktop', 'Tablet', 'Servidor', 'Impresora', 'Monitor'];
            foreach ($tipos as $t) :
                $sel = ($equipo['tipo'] ?? '') === $t ? 'selected' : '';
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
                $sel = ($equipo['area'] ?? '') === $a['nombre_area'] ? 'selected' : '';
            ?>
                <option value="<?= htmlspecialchars($a['nombre_area']) ?>" <?= $sel ?>>
                    <?= htmlspecialchars($a['nombre_area']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- 4. Estado -->
    <div class="col-md-6">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
            <?php
            $estados = ['Activo' => '🟢', 'Mantenimiento' => '🟡', 'Retirado' => '🔴'];
            foreach ($estados as $txt => $ico) :
                $sel = ($equipo['estado'] ?? '') === $txt ? 'selected' : '';
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
               value="<?= htmlspecialchars($equipo['ip'] ?? '') ?>">
    </div>

    <!-- 6. MAC -->
    <div class="col-md-6">
        <label class="form-label">MAC</label>
        <input type="text" name="mac" class="form-control"
               placeholder="XX:XX:XX:XX:XX:XX"
               value="<?= htmlspecialchars($equipo['mac'] ?? '') ?>">
    </div>

    <!-- 7. Service Tag -->
    <div class="col-md-6">
        <label class="form-label">Service Tag</label>
        <input type="text" name="service_tag" class="form-control"
               placeholder="ABC123456"
               value="<?= htmlspecialchars($equipo['service_tag'] ?? '') ?>">
    </div>

    <!-- 8. Modelo -->
    <div class="col-md-6">
        <label class="form-label">Modelo *</label>
        <input type="text" name="modelo" class="form-control"
               placeholder="Latitude 3440"
               value="<?= htmlspecialchars($equipo['modelo'] ?? '') ?>">
    </div>

    <!-- 9. SISTEMA OPERATIVO → COMBO -->
    <div class="col-md-6">
        <label class="form-label">Sistema Operativo</label>
        <select name="sistema_operativo" class="form-select">
            <option value="">-- Sin sistema --</option>
            <?php foreach ($sistemas as $s) :
                $sel = ($equipo['sistema_operativo'] ?? '') === $s['nombre_sistema'] ? 'selected' : '';
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
               value="<?= htmlspecialchars($equipo['clave_licencia'] ?? '') ?>">
    </div>

    <!-- 11. Fecha de garantía -->
    <div class="col-md-6">
        <label class="form-label">Fin de garantía</label>
        <input type="date" name="fecha_garantia" class="form-control"
               value="<?= htmlspecialchars($equipo['fecha_garantia'] ?? '') ?>">
    </div>

    <!-- 12. Observaciones -->
    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="3"
                  placeholder="Notas adicionales"><?= htmlspecialchars($equipo['observaciones'] ?? '') ?></textarea>
    </div>

    <!-- BOTONES -->
    <div class="col-12 text-end">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Actualizar
    </button>
    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='<?= $url_base ?>listar'">
        Cancelar
    </button>
    <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= $url_base ?>listar'">
        <i class="bi bi-arrow-left"></i> Volver al listado
    </button>
    </div>

</form>
</br>