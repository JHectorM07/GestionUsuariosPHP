<?php

require_once '../app/config/Conexion.php';
require_once '../app/modelos/Servicios.php';

class ServiciosController {
    private $db;
    private $servicioModel;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
        $this->servicioModel = new Servicio($this->db);
    }

    public function listar() {
        return $this->servicioModel->listar();
    }

    public function listarCategorias() {
        return $this->servicioModel->listarCategorias();
    }

    public function obtener($id) {
        return $this->servicioModel->obtenerPorId($id);
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoria_id = (int) ($_POST['categoria_id'] ?? 0);
            $servicio     = trim($_POST['servicio'] ?? '');
            $descripcion  = trim($_POST['descripcion'] ?? '');
            $precio_base  = (float) ($_POST['precio_base'] ?? 0);
            $periodicidad = trim($_POST['periodicidad'] ?? 'mensual');

            if ($categoria_id <= 0 || empty($servicio) || $precio_base <= 0) {
                $this->responderJson(false, "Completa la categoría, el nombre del servicio y un precio válido.");
            }

            $this->servicioModel->crear($categoria_id, $servicio, $descripcion, $precio_base, $periodicidad);
            $this->responderJson(true, "Servicio creado correctamente");
        }
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id           = (int) ($_POST['id'] ?? 0);
            $categoria_id = (int) ($_POST['categoria_id'] ?? 0);
            $servicio     = trim($_POST['servicio'] ?? '');
            $descripcion  = trim($_POST['descripcion'] ?? '');
            $precio_base  = (float) ($_POST['precio_base'] ?? 0);
            $periodicidad = trim($_POST['periodicidad'] ?? 'mensual');

            if ($id <= 0 || $categoria_id <= 0 || empty($servicio) || $precio_base <= 0) {
                $this->responderJson(false, "Completa todos los campos obligatorios.");
            }

            $this->servicioModel->actualizar($id, $categoria_id, $servicio, $descripcion, $precio_base, $periodicidad);
            $this->responderJson(true, "Servicio actualizado correctamente");
        }
    }

    public function borrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id'] ?? 0);

            if ($id <= 0) {
                $this->responderJson(false, "Servicio no válido");
            }

            try {
                $this->servicioModel->eliminar($id);
                $this->responderJson(true, "Servicio eliminado correctamente");
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    $this->responderJson(false, "No se puede eliminar: el servicio está asociado a uno o más registros.");
                }
                $this->responderJson(false, "Error al eliminar el servicio.");
            }
        }
    }

    private function responderJson($exito, $mensaje) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($exito) {
            $_SESSION['mensaje_servicios'] = [
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