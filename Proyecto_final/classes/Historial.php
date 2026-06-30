<?php
require_once __DIR__ . '/../config/database.php';

class Historial
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function registrar($usuarioId, $accion, $detalle = null)
    {
        $sql = "INSERT INTO historial (usuario_id, accion, detalle) VALUES (:usuario_id, :accion, :detalle)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':accion' => $accion,
            ':detalle' => $detalle
        ]);
    }

    public function obtenerPorUsuario($usuarioId, $limite = 50)
    {
        $sql = "SELECT h.*, u.nombre as usuario_nombre 
                FROM historial h 
                LEFT JOIN usuarios u ON h.usuario_id = u.id 
                WHERE h.usuario_id = :usuario_id 
                ORDER BY h.fecha DESC LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerTodos($limite = 100)
    {
        $sql = "SELECT h.*, u.nombre as usuario_nombre 
                FROM historial h 
                LEFT JOIN usuarios u ON h.usuario_id = u.id 
                ORDER BY h.fecha DESC LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contarPorAccion($accion)
    {
        $sql = "SELECT COUNT(*) FROM historial WHERE accion = :accion";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':accion' => $accion]);
        return $stmt->fetchColumn();
    }
}
