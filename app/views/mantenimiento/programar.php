<?php
/**
 * @var string $titulo
 * @var string $url_base
 * @var array $equipo
 * @var mysqli_result $tecnicos
 */
?>
<h2 class="mb-4"><i class="bi bi-calendar-plus"></i> <?= htmlspecialchars($titulo) ?></h2>

<form method="POST" action="<?= $url_base ?>mantenimiento/programar/<?= htmlspecialchars($equipo['id_equipo']) ?>" class="row g-3">
    <div class="col-12">
        <div class="card p-4">
            <h5 class="card-title">Programar Mantenimiento para <?= htmlspecialchars($equipo['nombre_pc']) ?></h5>
            <hr>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="id_usuario_tecnico" class="form-label">Técnico:</label>
                    <select name="id_usuario_tecnico" id="id_usuario_tecnico" class="form-select" required>
                        <option value="">-- Seleccione un técnico --</option>
                        <?php while ($tec = $tecnicos->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($tec['id_usuario']) ?>">
                                <?= htmlspecialchars($tec['nombre']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="fecha_programada" class="form-label">Fecha Programada:</label>
                    <input type="date" name="fecha_programada" id="fecha_programada" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="fecha_solicitud" class="form-label">Fecha de Solicitud:</label>
                    <input type="date" name="fecha_solicitud" id="fecha_solicitud" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="tipo_mantenimiento" class="form-label">Tipo de Mantenimiento:</label>
                    <select name="tipo_mantenimiento" id="tipo_mantenimiento" class="form-select">
                        <option value="PREVENTIVO">Preventivo</option>
                        <option value="CORRECTIVO">Correctivo</option>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <label class="form-label">Motivos del Mantenimiento:</label>
                    <div class="row border rounded p-3 bg-light g-3">
                        <div class="col-md-4">
                            <strong>Asistencia Técnica</strong>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[asistencia_tecnica][]" value="No prende" id="motivo_no_prende">
                                <label class="form-check-label" for="motivo_no_prende">No prende</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[asistencia_tecnica][]" value="Se reinicia" id="motivo_se_reinicia">
                                <label class="form-check-label" for="motivo_se_reinicia">Se reinicia</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[asistencia_tecnica][]" value="Virus" id="motivo_virus">
                                <label class="form-check-label" for="motivo_virus">Virus</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[asistencia_tecnica][]" value="Configurar correo" id="motivo_configurar_correo">
                                <label class="form-check-label" for="motivo_configurar_correo">Configurar correo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[asistencia_tecnica][]" value="Archivos perdidos" id="motivo_archivos_perdidos">
                                <label class="form-check-label" for="motivo_archivos_perdidos">Archivos perdidos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[asistencia_tecnica][]" value="Equipo lento" id="motivo_equipo_lento">
                                <label class="form-check-label" for="motivo_equipo_lento">Equipo lento</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[asistencia_tecnica][]" value="Conexion a la red" id="motivo_conexion_red">
                                <label class="form-check-label" for="motivo_conexion_red">Conexión a la red</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[asistencia_tecnica][]" value="Configurar impresora" id="motivo_configurar_impresora">
                                <label class="form-check-label" for="motivo_configurar_impresora">Configurar impresora</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[asistencia_tecnica][]" value="Otros" id="motivo_asistencia_otros">
                                <label class="form-check-label" for="motivo_asistencia_otros">Otros</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <strong>Revisión / Mantenimiento</strong>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Impresora" id="motivo_impresora">
                                <label class="form-check-label" for="motivo_impresora">Impresora</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Teclado" id="motivo_teclado">
                                <label class="form-check-label" for="motivo_teclado">Teclado</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Pantalla" id="motivo_pantalla">
                                <label class="form-check-label" for="motivo_pantalla">Pantalla</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Mouse" id="motivo_mouse">
                                <label class="form-check-label" for="motivo_mouse">Mouse</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Regulador" id="motivo_regulador">
                                <label class="form-check-label" for="motivo_regulador">Regulador</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Unidad de CD/DVD" id="motivo_cd_dvd">
                                <label class="form-check-label" for="motivo_cd_dvd">Unidad de CD/DVD</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Puertos USB" id="motivo_puertos_usb">
                                <label class="form-check-label" for="motivo_puertos_usb">Puertos USB</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Sonido" id="motivo_sonido">
                                <label class="form-check-label" for="motivo_sonido">Sonido</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Disco duro" id="motivo_disco_duro">
                                <label class="form-check-label" for="motivo_disco_duro">Disco duro</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[revision][]" value="Otros" id="motivo_revision_otros">
                                <label class="form-check-label" for="motivo_revision_otros">Otros</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <strong>Instalar / Reinstalar Programas</strong>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[programas][]" value="Todos" id="motivo_todos_programas">
                                <label class="form-check-label" for="motivo_todos_programas">Todos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[programas][]" value="Antivirus" id="motivo_antivirus">
                                <label class="form-check-label" for="motivo_antivirus">Antivirus</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[programas][]" value="Aplicativos" id="motivo_aplicativos">
                                <label class="form-check-label" for="motivo_aplicativos">Aplicativos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[programas][]" value="Quemador" id="motivo_quemador">
                                <label class="form-check-label" for="motivo_quemador">Quemador</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[programas][]" value="Paquete de office" id="motivo_office">
                                <label class="form-check-label" for="motivo_office">Paquete de office</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[programas][]" value="Lector PDF" id="motivo_lector_pdf">
                                <label class="form-check-label" for="motivo_lector_pdf">Lector PDF</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[programas][]" value="Reproductor DVD" id="motivo_reproductor_dvd">
                                <label class="form-check-label" for="motivo_reproductor_dvd">Reproductor DVD</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[programas][]" value="Impresora" id="motivo_programas_impresora">
                                <label class="form-check-label" for="motivo_programas_impresora">Impresora</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="motivos[programas][]" value="Otros" id="motivo_programas_otros">
                                <label class="form-check-label" for="motivo_programas_otros">Otros</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label for="observaciones_solicitud" class="form-label">Observaciones Adicionales:</label>
                    <textarea name="observaciones_solicitud" id="observaciones_solicitud" class="form-control" rows="3" placeholder="Ej: No enciende, equipo lento, etc."></textarea>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="id_equipo" value="<?= htmlspecialchars($equipo['id_equipo']) ?>">

    <div class="col-12 text-end mt-4">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-calendar-plus"></i> Programar Mantenimiento
        </button>
        <a href="<?= $url_base ?>mantenimiento/listar" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>
</form>