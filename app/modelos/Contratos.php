<?php 

class Contrato {
    private $conn;
    private $tabla = "contratos";

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function listar() {
        $query = "SELECT c.*, cl.cliente AS nombre_cliente 
                  FROM {$this->tabla} c
                  INNER JOIN clientes cl ON c.cliente_id = cl.cliente_id
                  ORDER BY c.contrato_id DESC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }

    
    public function obtenerPorId($id) {
        $query = "SELECT * FROM {$this->tabla} WHERE contrato_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    
    public function crear($cliente_id, $tipo_item, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado) {
        $query = "INSERT INTO {$this->tabla} (cliente_id, tipo_item, monto_acordado, periodicidad, dia_corte, fecha_inicio, estado) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$cliente_id, $tipo_item, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado]);
    }

    
    public function actualizar($id, $cliente_id, $tipo_item, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado) {
        $query = "UPDATE {$this->tabla} 
                  SET cliente_id = ?, tipo_item = ?, monto_acordado = ?, periodicidad = ?, dia_corte = ?, fecha_inicio = ?, estado = ? 
                  WHERE contrato_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$cliente_id, $tipo_item, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado, $id]);
    }

    
    public function eliminar($id) {
        $query = "DELETE FROM {$this->tabla} WHERE contrato_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}