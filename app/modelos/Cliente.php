<?php 

class Cliente{
    private $conn;
    private $tabla = "clientes";

    public function __construct($db){
        $this->conn = $db;
    }

    public function listar(){
        $query = "SELECT * FROM {$this->tabla} ORDER BY cliente_id DESC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id){
        $query = "SELECT * FROM {$this->tabla} WHERE cliente_id = ? LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crear($cliente, $email, $telefono, $estado){
        $query = "INSERT INTO {$this->tabla} (cliente, email, telefono, estado) VALUES(?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$cliente, $email, $telefono, $estado]);
    }

    public function actualizar($id, $cliente, $email, $telefono, $estado){
        $query = "UPDATE {$this->tabla} SET cliente = ?, email = ?, telefono = ?, estado = ? WHERE cliente_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$cliente, $email, $telefono, $estado, $id]);
    }

    public function eliminar($id){
        $query = "DELETE FROM {$this->tabla} WHERE cliente_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}