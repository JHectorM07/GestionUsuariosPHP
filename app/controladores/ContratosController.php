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
            $tipo_item      = trim($_POST['tipo_item'] ?? 'servicio');
            $monto_acordado = (float) ($_POST['monto_acordado'] ?? $_POST['monto'] ?? 0);
            $periodicidad   = trim($_POST['periodicidad'] ?? 'mensual');
            $dia_corte      = (int) ($_POST['dia_corte'] ?? 1);
            $fecha_inicio   = trim($_POST['fecha_inicio'] ?? '');
            $estado         = $_POST['estado'] ?? 'activo';

            if ($cliente_id <= 0 || empty($fecha_inicio) || $monto_acordado <= 0) {
                $this->responderJson(false, "Completa todos los campos obligatorios correctamente");
            }

            if (!in_array($estado, ['activo', 'inactivo', 'suspendido'])) {
                $estado = 'activo';
            }

            $this->contratoModel->crear($cliente_id, $tipo_item, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado);
            $this->responderJson(true, "Contrato creado correctamente");
        }
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id             = (int) ($_POST['id'] ?? 0);
            $cliente_id     = (int) ($_POST['cliente_id'] ?? 0);
            $tipo_item      = trim($_POST['tipo_item'] ?? 'servicio');
            $monto_acordado = (float) ($_POST['monto_acordado'] ?? $_POST['monto'] ?? 0);
            $periodicidad   = trim($_POST['periodicidad'] ?? 'mensual');
            $dia_corte      = (int) ($_POST['dia_corte'] ?? 1);
            $fecha_inicio   = trim($_POST['fecha_inicio'] ?? '');
            $estado         = $_POST['estado'] ?? 'activo';

            if ($id <= 0 || $cliente_id <= 0 || empty($fecha_inicio) || $monto_acordado <= 0) {
                $this->responderJson(false, "Completa los campos obligatorios");
            }

            if (!in_array($estado, ['activo', 'inactivo', 'suspendido'])) {
                $estado = 'activo';
            }

            $this->contratoModel->actualizar($id, $cliente_id, $tipo_item, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado);
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