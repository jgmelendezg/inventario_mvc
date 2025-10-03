<?php

require_once(__DIR__ . '/../core/BaseModel.php');

class UsuarioModel extends BaseModel
{
    private $tabla = 'usuarios';

     public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }

    private function sanitizarDatos(array $datos): array
    {
        $datosSanitizados = [];
        $campos = ['nombre', 'cargo', 'usuario_sistema', 'contrasena', 'correo', 'area', 'estado'];
        foreach ($campos as $campo) {
            if (isset($datos[$campo])) {
                $datosSanitizados[$campo] = trim(htmlspecialchars($datos[$campo], ENT_QUOTES, 'UTF-8'));
            }
        }
        return $datosSanitizados;
    }

    public function obtenerUsuariosConPaginacion($filtros, $limite = 10, $offset = 0): array
    {
        $condiciones = [];
        $tipos = '';
        $parametros = [];

        if (!empty($filtros['area'])) {
            $condiciones[] = "area = ?";
            $tipos .= 's';
            $parametros[] = $filtros['area'];
        }
        if (!empty($filtros['estado'])) {
            $condiciones[] = "estado = ?";
            $tipos .= 's';
            $parametros[] = $filtros['estado'];
        }

        $where = count($condiciones) ? "WHERE " . implode(" AND ", $condiciones) : "";
        $sql = "SELECT id_usuario, nombre, cargo, usuario_sistema, contrasena, correo, area, estado FROM {$this->tabla} $where ORDER BY id_usuario DESC LIMIT ? OFFSET ?";

        $tipos .= 'ii';
        $parametros[] = $limite;
        $parametros[] = $offset;

        $stmt = $this->conexion->prepare($sql);
        if (count($parametros) > 0) {
            $stmt->bind_param($tipos, ...$parametros);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function contarUsuarios($filtros): int
    {
        $condiciones = [];
        $tipos = '';
        $parametros = [];

        if (!empty($filtros['area'])) {
            $condiciones[] = "area = ?";
            $tipos .= 's';
            $parametros[] = $filtros['area'];
        }
        if (!empty($filtros['estado'])) {
            $condiciones[] = "estado = ?";
            $tipos .= 's';
            $parametros[] = $filtros['estado'];
        }

        $where = count($condiciones) ? "WHERE " . implode(" AND ", $condiciones) : "";
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla} $where";

        $stmt = $this->conexion->prepare($sql);
        if ($stmt && count($parametros) > 0) {
            $stmt->bind_param($tipos, ...$parametros);
        }
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return (int)$resultado['total'];
    }

    public function obtenerUsuarioPorId(int $id): ?array
    {
        $stmt = $this->conexion->prepare("
            SELECT
            u.*,
            a.nombre AS nombre_area
            FROM usuarios u
            JOIN areas a ON u.area = a.id_area
            WHERE u.id_usuario = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function registrarUsuario(array $datos): bool
    {
        $datos = $this->sanitizarDatos($datos);
        $sql = "INSERT INTO {$this->tabla} (nombre, cargo, usuario_sistema, contrasena, correo, area, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssssss", 
            $datos['nombre'], 
            $datos['cargo'], 
            $datos['usuario_sistema'], 
            $datos['contrasena'], 
            $datos['correo'], 
            $datos['area'], 
            $datos['estado']
        );
        return $stmt->execute();
    }

    public function actualizarUsuario(int $id, array $datos): bool
    {
        $datos = $this->sanitizarDatos($datos);
        $sql = "UPDATE {$this->tabla} SET 
                nombre=?, cargo=?, usuario_sistema=?, contrasena=?, correo=?, area=?, estado=?
                WHERE id_usuario=?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssssssi", 
            $datos['nombre'], 
            $datos['cargo'], 
            $datos['usuario_sistema'], 
            $datos['contrasena'], 
            $datos['correo'], 
            $datos['area'], 
            $datos['estado'],
            $id
        );
        return $stmt->execute();
    }

    public function inactivarUsuario(int $id): bool
    {
        $sql = "UPDATE {$this->tabla} SET estado='Inactivo' WHERE id_usuario=?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}