<?php

require_once '../app/config/Conexion.php';
require_once '../app/modelos/Contratos.php';

class ContratosController {
    private $db;
    private $contratoModel;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
        $this->contratoModel = new Contrato($this->db);
    }

    public function listar() {
        return $this->contratoModel->listar();
    }

    public function obtener($id) {
        return $this->contratoModel->obtenerPorId($id);
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente_id     = (int) ($_POST['cliente_id'] ?? 0);
            $tipo_item      = $_POST['tipo_item'] ?? 'servicio';
            $servicio_id    = ($tipo_item === 'servicio' && !empty($_POST['servicio_id'])) ? (int) $_POST['servicio_id'] : null;
            $producto_id    = ($tipo_item === 'producto' && !empty($_POST['producto_id'])) ? (int) $_POST['producto_id'] : null;
            $monto_acordado = (float) ($_POST['monto_acordado'] ?? 0);
            $periodicidad   = $_POST['periodicidad'] ?? 'mensual';
            $dia_corte      = (int) ($_POST['dia_corte'] ?? 1);
            $fecha_inicio   = $_POST['fecha_inicio'] ?? '';
            $estado         = $_POST['estado'] ?? 'activo';

            if ($cliente_id <= 0 || $monto_acordado <= 0 || empty($fecha_inicio)) {
                $this->responderJson(false, "Completa todos los campos obligatorios.");
            }

            if ($tipo_item === 'servicio' && !$servicio_id) {
                $this->responderJson(false, "Debes seleccionar un servicio del catálogo.");
            }

            if ($tipo_item === 'producto' && !$producto_id) {
                $this->responderJson(false, "Debes seleccionar un producto del catálogo.");
            }

            $this->contratoModel->crear($cliente_id, $tipo_item, $servicio_id, $producto_id, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado);
            $this->responderJson(true, "Contrato creado correctamente");
        }
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id             = (int) ($_POST['id'] ?? 0);
            $cliente_id     = (int) ($_POST['cliente_id'] ?? 0);
            $tipo_item      = $_POST['tipo_item'] ?? 'servicio';
            $servicio_id    = ($tipo_item === 'servicio' && !empty($_POST['servicio_id'])) ? (int) $_POST['servicio_id'] : null;
            $producto_id    = ($tipo_item === 'producto' && !empty($_POST['producto_id'])) ? (int) $_POST['producto_id'] : null;
            $monto_acordado = (float) ($_POST['monto_acordado'] ?? 0);
            $periodicidad   = $_POST['periodicidad'] ?? 'mensual';
            $dia_corte      = (int) ($_POST['dia_corte'] ?? 1);
            $fecha_inicio   = $_POST['fecha_inicio'] ?? '';
            $estado         = $_POST['estado'] ?? 'activo';

            if ($id <= 0 || $cliente_id <= 0 || $monto_acordado <= 0 || empty($fecha_inicio)) {
                $this->responderJson(false, "Completa todos los campos obligatorios.");
            }

            if ($tipo_item === 'servicio' && !$servicio_id) {
                $this->responderJson(false, "Debes seleccionar un servicio del catálogo.");
            }

            if ($tipo_item === 'producto' && !$producto_id) {
                $this->responderJson(false, "Debes seleccionar un producto del catálogo.");
            }

            $this->contratoModel->actualizar($id, $cliente_id, $tipo_item, $servicio_id, $producto_id, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado);
            $this->responderJson(true, "Contrato actualizado correctamente");
        }
    }

    public function borrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id'] ?? 0);

            if ($id <= 0) {
                $this->responderJson(false, "Contrato no válido");
            }

            try {
                $this->contratoModel->eliminar($id);
                $this->responderJson(true, "Contrato eliminado correctamente");
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    $this->responderJson(false, "No se puede eliminar: el contrato tiene registros asociados (cobros o facturas). Puedes marcarlo como inactivo o suspendido.");
                }
                $this->responderJson(false, "Error al eliminar el contrato.");
            }
        }
    }

    private function responderJson($exito, $mensaje) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Guarda el mensaje flash para que aparezca en la alerta al hacer location.reload()
        if ($exito) {
            $_SESSION['mensaje_contratos'] = [
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