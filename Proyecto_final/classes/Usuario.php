<?php
require_once __DIR__ . '/../config/database.php';

class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function registrar($datos)
    {
        $sql = "INSERT INTO usuarios (nombre, email, password, telefono, rol) VALUES (:nombre, :email, :password, :telefono, :rol)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':email' => $datos['email'],
            ':password' => password_hash($datos['password'], PASSWORD_DEFAULT),
            ':telefono' => $datos['telefono'] ?? '',
            ':rol' => 'cliente'
        ]);
        return $this->db->lastInsertId();
    }

    public function login($email, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function actualizar($id, $datos)
    {
        $campos = [];
        $params = [':id' => $id];
        foreach (['nombre', 'email', 'telefono', 'foto_perfil', 'rol', 'activo'] as $campo) {
            if (isset($datos[$campo])) {
                $campos[] = "$campo = :$campo";
                $params[":$campo"] = $datos[$campo];
            }
        }
        if (!empty($campos)) {
            $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }
        return false;
    }

    public function cambiarPassword($id, $password)
    {
        $sql = "UPDATE usuarios SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    }

    public function emailExiste($email)
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function contarClientes()
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'";
        return $this->db->query($sql)->fetchColumn();
    }
}
