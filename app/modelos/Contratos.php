<?php

class Contrato {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function listar() {
        $sql = "SELECT c.*, 
                       cl.cliente AS nombre_cliente,
                       s.servicio AS nombre_servicio,
                       p.producto AS nombre_producto
                FROM contratos c
                LEFT JOIN clientes cl ON c.cliente_id = cl.cliente_id
                LEFT JOIN servicios s ON c.servicio_id = s.servicio_id
                LEFT JOIN productos p ON c.producto_id = p.producto_id
                ORDER BY c.contrato_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM contratos WHERE contrato_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($cliente_id, $tipo_item, $servicio_id, $producto_id, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado) {
        $sql = "INSERT INTO contratos (cliente_id, tipo_item, servicio_id, producto_id, monto_acordado, periodicidad, dia_corte, fecha_inicio, estado)
                VALUES (:cliente_id, :tipo_item, :servicio_id, :producto_id, :monto_acordado, :periodicidad, :dia_corte, :fecha_inicio, :estado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':cliente_id'     => $cliente_id,
            ':tipo_item'      => $tipo_item,
            ':servicio_id'    => $servicio_id,
            ':producto_id'    => $producto_id,
            ':monto_acordado' => $monto_acordado,
            ':periodicidad'   => $periodicidad,
            ':dia_corte'      => $dia_corte,
            ':fecha_inicio'   => $fecha_inicio,
            ':estado'         => $estado
        ]);
    }

    public function actualizar($id, $cliente_id, $tipo_item, $servicio_id, $producto_id, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado) {
        $sql = "UPDATE contratos
                SET cliente_id     = :cliente_id,
                    tipo_item      = :tipo_item,
                    servicio_id    = :servicio_id,
                    producto_id    = :producto_id,
                    monto_acordado = :monto_acordado,
                    periodicidad   = :periodicidad,
                    dia_corte      = :dia_corte,
                    fecha_inicio   = :fecha_inicio,
                    estado         = :estado
                WHERE contrato_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'             => $id,
            ':cliente_id'     => $cliente_id,
            ':tipo_item'      => $tipo_item,
            ':servicio_id'    => $servicio_id,
            ':producto_id'    => $producto_id,
            ':monto_acordado' => $monto_acordado,
            ':periodicidad'   => $periodicidad,
            ':dia_corte'      => $dia_corte,
            ':fecha_inicio'   => $fecha_inicio,
            ':estado'         => $estado
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM contratos WHERE contrato_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}