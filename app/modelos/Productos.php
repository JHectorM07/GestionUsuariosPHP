<?php

class Producto {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function listar() {
        $sql = "SELECT * FROM productos ORDER BY producto_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM productos WHERE producto_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($categoria_id, $producto, $descripcion, $precio_unitario, $cantidad) {
        $sql = "INSERT INTO productos (categoria_id, producto, descripcion, precio_unitario, cantidad)
                VALUES (:categoria_id, :producto, :descripcion, :precio_unitario, :cantidad)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':categoria_id'    => $categoria_id,
            ':producto'        => $producto,
            ':descripcion'     => $descripcion,
            ':precio_unitario' => $precio_unitario,
            ':cantidad'        => $cantidad
        ]);
    }

    public function actualizar($id, $categoria_id, $producto, $descripcion, $precio_unitario, $cantidad) {
        $sql = "UPDATE productos
                SET categoria_id    = :categoria_id,
                    producto        = :producto,
                    descripcion     = :descripcion,
                    precio_unitario = :precio_unitario,
                    cantidad        = :cantidad
                WHERE producto_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'              => $id,
            ':categoria_id'    => $categoria_id,
            ':producto'        => $producto,
            ':descripcion'     => $descripcion,
            ':precio_unitario' => $precio_unitario,
            ':cantidad'        => $cantidad
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM productos WHERE producto_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}