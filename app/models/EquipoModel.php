<?php
//require_once(__DIR__ . '/../../conexion.php');
require_once(__DIR__ . '/../core/BaseModel.php');

class EquipoModel extends BaseModel
{
    private $tabla = 'equipos';

    public function __construct(mysqli $conexion)
    {
        //$conexion = get_db_connection(); // Obtener la conexión
        parent::__construct($conexion);
    }
    
    public function obtenerEquiposConPaginacion($filtros, $limite = 10, $offset = 0): array
    {
        try {
            $limite = max(1, min(100, (int)$limite));
            $offset = max(0, (int)$offset);

            $condiciones = [];
            $tipos = '';
            $parametros = [];

            $this->construirCondicionesFiltro($filtros, $condiciones, $tipos, $parametros);

            $where = count($condiciones) ? "WHERE " . implode(" AND ", $condiciones) : "";
            $sql = "SELECT 
                        e.id_equipo,
                        e.nombre_pc,
                        e.service_tag,
                        e.modelo,
                        e.tipo,
                        e.estado,
                        e.fecha_garantia,
                        u.nombre as nombre_usuario,
                        u.cargo as cargo_usuario,
                        e.area as area_asignada
                    FROM {$this->tabla} e
                    LEFT JOIN asignaciones asig ON e.id_equipo = asig.id_equipo AND asig.fecha_desasignacion IS NULL
                    LEFT JOIN usuarios u ON asig.id_usuario = u.id_usuario
                    {$where}
                    ORDER BY e.id_equipo DESC LIMIT ? OFFSET ?";

            $tipos .= 'ii';
            $parametros[] = $limite;
            $parametros[] = $offset;

            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
            }

            if (count($parametros) > 0) {
                $stmt->bind_param($tipos, ...$parametros);
            }

            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        } catch (Exception $e) {
            error_log("Error en obtenerEquiposConPaginacion: " . $e->getMessage());
            return [];
        }
    }

    public function contarEquipos($filtros): int
    {
        try {
            $condiciones = [];
            $tipos = '';
            $parametros = [];

            $this->construirCondicionesFiltro($filtros, $condiciones, $tipos, $parametros);

            $where = count($condiciones) ? "WHERE " . implode(" AND ", $condiciones) : "";
            $sql = "SELECT COUNT(e.id_equipo) as total
                    FROM {$this->tabla} e
                    LEFT JOIN asignaciones a ON e.id_equipo = a.id_equipo AND a.fecha_desasignacion IS NULL
                    LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario
                    $where";

            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
            }
            if (count($parametros) > 0) {
                $stmt->bind_param($tipos, ...$parametros);
            }

            $stmt->execute();
            $resultado = $stmt->get_result()->fetch_assoc();
            return (int)$resultado['total'];

        } catch (Exception $e) {
            error_log("Error en contarEquipos: " . $e->getMessage());
            return 0;
        }
    }
    
    // Aquí van los otros métodos como obtenerEquipoPorId, registrarEquipo, etc.
    // ...
    private function construirCondicionesFiltro($filtros, &$condiciones, &$tipos, &$parametros)
    {
        // Filtro de búsqueda general (nombre_pc, serial)
        if (!empty($filtros['search'])) {
            $condiciones[] = "(e.nombre_pc LIKE ? OR e.service_tag LIKE ?)";
            $tipos .= 'ss';
            $search = "%" . $filtros['search'] . "%";
            $parametros[] = $search;
            $parametros[] = $search;
        }
        
        // Filtro por tipo
        if (!empty($filtros['tipo'])) {
            $condiciones[] = "e.tipo = ?";
            $tipos .= 's';
            $parametros[] = $filtros['tipo'];
        }
        
        // Filtro por estado
        if (!empty($filtros['estado'])) {
            $condiciones[] = "e.estado = ?";
            $tipos .= 's';
            $parametros[] = $filtros['estado'];
        }

        // Filtro por área del usuario asignado
        if (!empty($filtros['area'])) {
            $condiciones[] = "u.area = ?";
            $tipos .= 's';
            $parametros[] = $filtros['area'];
        }

        // Filtro por garantía
        if (!empty($filtros['garantia'])) {
            $fecha_actual = date('Y-m-d');
            if ($filtros['garantia'] === 'vencida') {
                $condiciones[] = "e.fecha_garantia < ?";
                $tipos .= 's';
                $parametros[] = $fecha_actual;
            } elseif ($filtros['garantia'] === 'por_vencer') {
                $condiciones[] = "e.fecha_garantia >= ?";
                $tipos .= 's';
                $parametros[] = $fecha_actual;
            }
        }
    }
    
    // ... resto de los métodos privados
    private function limpiarTexto($texto) {
        return trim(htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'));
    }

    private function validarFecha($fecha) {
        if (empty($fecha)) {
            return null;
        }
        $timestamp = strtotime($fecha);
        return $timestamp !== false ? date('Y-m-d', $timestamp) : null;
    }

    private function validarEstado($estado) {
        $estadosValidos = ['Activo', 'Mantenimiento', 'Retirado'];
        return in_array($estado, $estadosValidos) ? $estado : 'Activo';
    }

    /**
    * Registrar un nuevo equipo
    */
    
    public function registrarEquipo($datos) {
        try {
            // Limpiar y validar datos
            $datosLimpios = $this->limpiarDatosEquipo($datos);

        $sql = "INSERT INTO {$this->tabla} (nombre_pc, tipo, ip, mac, service_tag, sistema_operativo, modelo, clave_licencia, observaciones, area, fecha_garantia, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("ssssssssssss", 
            $datosLimpios['nombre_pc'], 
            $datosLimpios['tipo'], 
            $datosLimpios['ip'], 
            $datosLimpios['mac'], 
            $datosLimpios['service_tag'], 
            $datosLimpios['sistema_operativo'], 
            $datosLimpios['modelo'], 
            $datosLimpios['clave_licencia'], 
            $datosLimpios['observaciones'], 
            $datosLimpios['area'], 
            $datosLimpios['fecha_garantia'], 
            $datosLimpios['estado']
        );

        return $stmt->execute();

         } catch (Exception $e) {
            error_log("Error en registrarEquipo: " . $e->getMessage());
            throw $e;
        }
    }

     /**
     * Obtener un equipo por su ID
     */
    public function obtenerEquipoPorId($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return false;
            }

            $sql = "SELECT * FROM {$this->tabla} WHERE id_equipo = ?";
            $stmt = $this->conexion->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            return $stmt->get_result()->fetch_assoc();

        } catch (Exception $e) {
            error_log("Error en obtenerEquipoPorId: " . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Actualizar un equipo existente
     */
    public function actualizarEquipo($id, $datos) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return false;
            }

            // Limpiar y validar datos
            $datosLimpios = $this->limpiarDatosEquipo($datos);

            $sql = "UPDATE {$this->tabla} SET 
                    nombre_pc=?, tipo=?, ip=?, mac=?, service_tag=?, sistema_operativo=?, 
                    modelo=?, clave_licencia=?, observaciones=?, area=?, fecha_garantia=?, estado=? 
                    WHERE id_equipo=?";
            
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
            }

            $stmt->bind_param("ssssssssssssi", 
                $datosLimpios['nombre_pc'], 
                $datosLimpios['tipo'], 
                $datosLimpios['ip'], 
                $datosLimpios['mac'], 
                $datosLimpios['service_tag'], 
                $datosLimpios['sistema_operativo'], 
                $datosLimpios['modelo'], 
                $datosLimpios['clave_licencia'], 
                $datosLimpios['observaciones'], 
                $datosLimpios['area'], 
                $datosLimpios['fecha_garantia'], 
                $datosLimpios['estado'], 
                $id
            );

            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error en actualizarEquipo: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Retirar un equipo (cambiar estado a Retirado)
     */
    public function retirarEquipo($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return false;
            }

            $sql = "UPDATE {$this->tabla} SET estado = 'Retirado' WHERE id_equipo = ?";
            $stmt = $this->conexion->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
            }

            $stmt->bind_param("i", $id);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error en retirarEquipo: " . $e->getMessage());
            throw $e;
        }
    }

        /**
     * Eliminar un equipo completamente (solo si es necesario)
     */
    public function eliminarEquipo($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return false;
            }

            $sql = "DELETE FROM {$this->tabla} WHERE id_equipo = ?";
            $stmt = $this->conexion->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
            }

            $stmt->bind_param("i", $id);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error en eliminarEquipo: " . $e->getMessage());
            throw $e;
        }
    }


       public function obtenerEquiposSinAsignacion()
    {
        $sql = "SELECT * FROM {$this->tabla} 
                WHERE estado = 'Activo' 
                AND id_equipo NOT IN (
                    SELECT id_equipo FROM asignaciones WHERE estado = 'Activo'
                )
                ORDER BY nombre_pc ASC";
        return $this->conexion->query($sql);
    }

        /**
     * Limpiar y validar datos del equipo
     */
    private function limpiarDatosEquipo($datos) {
        return [
            'nombre_pc' => $this->limpiarTexto($datos['nombre_pc'] ?? ''),
            'tipo' => $this->limpiarTexto($datos['tipo'] ?? ''),
            'ip' => $this->limpiarTexto($datos['ip'] ?? ''),
            'mac' => $this->limpiarTexto($datos['mac'] ?? ''),
            'service_tag' => $this->limpiarTexto($datos['service_tag'] ?? ''),
            'sistema_operativo' => $this->limpiarTexto($datos['sistema_operativo'] ?? ''),
            'modelo' => $this->limpiarTexto($datos['modelo'] ?? ''),
            'clave_licencia' => $this->limpiarTexto($datos['clave_licencia'] ?? ''),
            'observaciones' => $this->limpiarTexto($datos['observaciones'] ?? ''),
            'area' => $this->limpiarTexto($datos['area'] ?? ''),
            'fecha_garantia' => $this->validarFecha($datos['fecha_garantia'] ?? ''),
            'estado' => $this->validarEstado($datos['estado'] ?? 'Activo')
        ];
    }

    public function obtenerEquipos($filtros) {
        try {
            $condiciones = [];
            $tipos = '';
            $parametros = [];

            // Construir condiciones WHERE
            $this->construirCondicionesFiltro($filtros, $condiciones, $tipos, $parametros);

            $where = count($condiciones) ? "WHERE " . implode(" AND ", $condiciones) : "";
            $sql = "SELECT * FROM {$this->tabla} $where ORDER BY id_equipo DESC";

            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
            }

            if ($stmt && count($parametros) > 0) {
                $stmt->bind_param($tipos, ...$parametros);
            }

            $stmt->execute();
            return $stmt->get_result();

        } catch (Exception $e) {
            error_log("Error en obtenerEquipos: " . $e->getMessage());
            throw $e;
        }
    }



}