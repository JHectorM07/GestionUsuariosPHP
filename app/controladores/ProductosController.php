<?php

require_once '../app/config/Conexion.php';
require_once '../app/modelos/Productos.php';

class ProductosController {
    private $db;
    private $productoModel;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
        $this->productoModel = new Producto($this->db);
    }

    public function listar() {
        return $this->productoModel->listar();
    }

    public function obtener($id) {
        return $this->productoModel->obtenerPorId($id);
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoria_id    = (int) ($_POST['categoria_id'] ?? 0);
            $producto        = trim($_POST['producto'] ?? '');
            $descripcion     = trim($_POST['descripcion'] ?? '');
            $precio_unitario = (float) ($_POST['precio_unitario'] ?? 0);
            $cantidad        = (int) ($_POST['cantidad'] ?? 0);

            if ($categoria_id <= 0 || empty($producto) || $precio_unitario <= 0 || $cantidad < 0) {
                $this->responderJson(false, "Completa la categoría, el nombre del producto, un precio válido y una cantidad mayor o igual a 0.");
            }

            $this->productoModel->crear($categoria_id, $producto, $descripcion, $precio_unitario, $cantidad);
            $this->responderJson(true, "Producto creado correctamente");
        }
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id              = (int) ($_POST['id'] ?? 0);
            $categoria_id    = (int) ($_POST['categoria_id'] ?? 0);
            $producto        = trim($_POST['producto'] ?? '');
            $descripcion     = trim($_POST['descripcion'] ?? '');
            $precio_unitario = (float) ($_POST['precio_unitario'] ?? 0);
            $cantidad        = (int) ($_POST['cantidad'] ?? 0);

            if ($id <= 0 || $categoria_id <= 0 || empty($producto) || $precio_unitario <= 0 || $cantidad < 0) {
                $this->responderJson(false, "Completa todos los campos obligatorios con valores válidos.");
            }

            $this->productoModel->actualizar($id, $categoria_id, $producto, $descripcion, $precio_unitario, $cantidad);
            $this->responderJson(true, "Producto actualizado correctamente");
        }
    }

    public function borrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id'] ?? 0);

            if ($id <= 0) {
                $this->responderJson(false, "Producto no válido");
            }

            try {
                $this->productoModel->eliminar($id);
                $this->responderJson(true, "Producto eliminado correctamente");
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    $this->responderJson(false, "No se puede eliminar: el producto está asociado a uno o más registros.");
                }
                $this->responderJson(false, "Error al eliminar el producto.");
            }
        }
    }

    private function responderJson($exito, $mensaje) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($exito) {
            $_SESSION['mensaje_productos'] = [
                'tipo'  => 'success',
                'texto' => $mensaje
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'exito'   => $exito,
            'mensaje' => $mensaje
        ]);
        exit();
    }
}