<?php

class Servicio {
    private $db;

    public function __construct($db) {
        $this->db =$db;
    }

    public function listar() {
        $sql = "SELECT s.*, c.categoria AS nombre_categoria
                FROM servicios s
                LEFT JOIN categoria c ON s.categoria_id = c.categoria_id
                ORDER BY s.servicio_id DESC";
        $stmt =$this->db->prepare($sql);$stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarCategorias() {
        $sql = "SELECT * FROM categoria ORDER BY categoria_id ASC";
        $stmt =$this->db->prepare($sql);$stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {$sql = "SELECT * FROM servicios WHERE servicio_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' =>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($categoria_id,$servicio, $descripcion,$precio_base, $periodicidad) {$sql = "INSERT INTO servicios (categoria_id, servicio, descripcion, precio_base, periodicidad)
                VALUES (:categoria_id, :servicio, :descripcion, :precio_base, :periodicidad)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':categoria_id' => $categoria_id,
            ':servicio'     => $servicio,
            ':descripcion'  => $descripcion,
            ':precio_base'  => $precio_base,
            ':periodicidad' => $periodicidad
        ]);
    }

    public function actualizar($id, $categoria_id,$servicio, $descripcion,$precio_base, $periodicidad) {$sql = "UPDATE servicios
                SET categoria_id = :categoria_id,
                    servicio     = :servicio,
                    descripcion  = :descripcion,
                    precio_base  = :precio_base,
                    periodicidad = :periodicidad
                WHERE servicio_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':categoria_id' => $categoria_id,
            ':servicio'     => $servicio,
            ':descripcion'  => $descripcion,
            ':precio_base'  => $precio_base,
            ':periodicidad' => $periodicidad
        ]);
    }

    public function eliminar($id) {$sql = "DELETE FROM servicios WHERE servicio_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' =>$id]);
    }
}