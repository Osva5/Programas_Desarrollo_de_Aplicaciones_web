<?php
require_once __DIR__ . '/../config/database.php';

class Pago
{
    private $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function procesarPago($reservacionId, $metodoPago = 'tarjeta')
    {
        $this->db->beginTransaction();
        try {
            $sql = "SELECT r.* FROM reservaciones r WHERE r.id = :id AND r.estado = 'pendiente'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $reservacionId]);
            $reservacion = $stmt->fetch();

            if (!$reservacion) {
                throw new Exception("Reservación no encontrada o ya procesada");
            }

            $referencia = 'PAG-' . strtoupper(uniqid());

            $sqlUpdPago = "UPDATE pagos SET metodo_pago = :metodo_pago, estado_pago = 'completado', referencia = :referencia, fecha_pago = NOW() WHERE reservacion_id = :reservacion_id";
            $stmtUpdPago = $this->db->prepare($sqlUpdPago);
            $stmtUpdPago->execute([
                ':metodo_pago' => $metodoPago,
                ':referencia' => $referencia,
                ':reservacion_id' => $reservacionId
            ]);

            $sqlUpdRes = "UPDATE reservaciones SET estado = 'confirmada' WHERE id = :id";
            $stmtUpdRes = $this->db->prepare($sqlUpdRes);
            $stmtUpdRes->execute([':id' => $reservacionId]);

            $this->db->commit();
            return ['exito' => true, 'referencia' => $referencia];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['exito' => false, 'mensaje' => $e->getMessage()];
        }
    }

    public function obtenerPorReservacion($reservacionId)
    {
        $sql = "SELECT p.*, r.fecha, r.hora_inicio, r.hora_fin, r.total, c.nombre as cancha_nombre 
                FROM pagos p 
                JOIN reservaciones r ON p.reservacion_id = r.id 
                JOIN canchas c ON r.cancha_id = c.id 
                WHERE p.reservacion_id = :reservacion_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':reservacion_id' => $reservacionId]);
        return $stmt->fetch();
    }

    public function obtenerTodos()
    {
        $sql = "SELECT p.*, r.fecha, r.hora_inicio, u.nombre as usuario_nombre, c.nombre as cancha_nombre 
                FROM pagos p 
                JOIN reservaciones r ON p.reservacion_id = r.id 
                JOIN usuarios u ON r.usuario_id = u.id 
                JOIN canchas c ON r.cancha_id = c.id 
                ORDER BY p.fecha_pago DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function ingresoTotal($fechaInicio = null, $fechaFin = null)
    {
        $sql = "SELECT COALESCE(SUM(monto), 0) as total FROM pagos WHERE estado_pago = 'completado'";
        $params = [];
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND fecha_pago BETWEEN :inicio AND :fin";
            $params[':inicio'] = $fechaInicio;
            $params[':fin'] = $fechaFin;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function metodoPagoMasUsado()
    {
        $sql = "SELECT metodo_pago, COUNT(*) as total FROM pagos WHERE estado_pago = 'completado' GROUP BY metodo_pago ORDER BY total DESC LIMIT 1";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
