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
                $this->redireccionar("Completa todos los campos obligatorios correctamente", 'danger', '&accion=crear');
                return;
            }

            if (!in_array($estado, ['activo', 'inactivo', 'suspendido'])) {
                $estado = 'activo';
            }

            $this->contratoModel->crear($cliente_id, $tipo_item, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado);
            $this->redireccionar("Contrato creado correctamente", 'success');
        }
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // CAMBIADO: $_REQUEST para capturar id enviado por POST o GET
            $id             = (int) ($_REQUEST['id'] ?? 0);
            $cliente_id     = (int) ($_POST['cliente_id'] ?? 0);
            $tipo_item      = trim($_POST['tipo_item'] ?? 'servicio');
            $monto_acordado = (float) ($_POST['monto_acordado'] ?? $_POST['monto'] ?? 0);
            $periodicidad   = trim($_POST['periodicidad'] ?? 'mensual');
            $dia_corte      = (int) ($_POST['dia_corte'] ?? 1);
            $fecha_inicio   = trim($_POST['fecha_inicio'] ?? '');
            $estado         = $_POST['estado'] ?? 'activo';

            if ($id <= 0 || $cliente_id <= 0 || empty($fecha_inicio) || $monto_acordado <= 0) {
                $this->redireccionar("Completa los campos obligatorios", 'danger', "&accion=editar&id=$id");
                return;
            }

            if (!in_array($estado, ['activo', 'inactivo', 'suspendido'])) {
                $estado = 'activo';
            }

            $this->contratoModel->actualizar($id, $cliente_id, $tipo_item, $monto_acordado, $periodicidad, $dia_corte, $fecha_inicio, $estado);
            $this->redireccionar("Contrato actualizado correctamente", 'success');
        }
    }

   public function borrar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = (int) ($_REQUEST['id'] ?? 0);

        if ($id <= 0) {
            $this->redireccionar("Contrato no válido", 'danger');
            return;
        }

        try {
            $this->contratoModel->eliminar($id);
            $this->redireccionar("Contrato eliminado correctamente", 'success');
        } catch (PDOException $e) {
            // Código SQLSTATE 23000 = Violación de clave foránea / Integridad referencial
            if ($e->getCode() == '23000') {
                $this->redireccionar("No se puede eliminar: el contrato tiene registros asociados (cobros o facturas). Puedes marcarlo como inactivo o suspendido.", 'danger');
            }
            throw $e; // Cualquier otro error inesperado
        }
    }
}
private function redireccionar($mensaje, $tipo = 'success', $accion = 'index', $id = 0) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['mensaje_contratos'] = ['tipo' => $tipo, 'texto' => $mensaje];
    
    $_SESSION['ir_a'] = [
        'controlador' => 'contratos',
        'accion'      => $accion,
        'id'          => $id
    ];

    header("Location: /appgestionusuarios/public/index.php");
    exit();
}
}