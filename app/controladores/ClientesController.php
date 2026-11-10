<?php

require_once '../app/config/Conexion.php';
require_once '../app/modelos/Cliente.php';

class ClientesController {
    private $db;
    private $clienteModel;

    public function __construct(){
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
        $this->clienteModel = new Cliente($this->db);
    }

    public function listar(){
        return $this->clienteModel->listar();
    }

    public function obtener($id){
        return $this->clienteModel->obtenerPorId($id);
    }

    public function guardar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $cliente  = trim($_POST['cliente'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $estado   = $_POST['estado'] ?? 'activo';

            if(empty($cliente) || empty($email)){
                $this->redireccionar("Completa los campos obligatorios", 'danger', '&accion=crear');
                return;
            }

            if(!in_array($estado, ['activo', 'inactivo'])){
                $estado = 'activo';
            }

            $this->clienteModel->crear($cliente, $email, $telefono, $estado);
            $this->redireccionar("Cliente agregado correctamente", 'success');
        }
    }

    public function actualizar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id       = (int) ($_REQUEST['id'] ?? 0);
            $cliente  = trim($_POST['cliente'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $estado   = $_POST['estado'] ?? 'activo';

            if($id <= 0 || empty($cliente) || empty($email)){
                $this->redireccionar("Completa los campos obligatorios", 'danger', "&accion=editar&id=$id");
                return;
            }

            if(!in_array($estado, ['activo', 'inactivo'])){
                $estado = 'activo';
            }

            $this->clienteModel->actualizar($id, $cliente, $email, $telefono, $estado);
            $this->redireccionar("Cliente editado correctamente", 'success');
        }
    }

    public function borrar(){
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $id = (int) ($_POST['id'] ?? 0);

        if($id <= 0){
            $this->redireccionar("Cliente no válido", 'danger');
            return;
        }

        try {
            $this->clienteModel->eliminar($id);
            $this->redireccionar("Cliente eliminado correctamente", 'success');
        } catch (PDOException $e) {
            // 23000 = violación de integridad (el cliente tiene contratos)
            if ($e->getCode() == '23000') {
                $this->redireccionar("No se puede eliminar: el cliente tiene contratos asociados. Puedes marcarlo como inactivo.", 'danger');
            }
            throw $e;   // cualquier otro error se muestra normal
        }
    }
}

  private function redireccionar($mensaje, $tipo = 'success', $accion = 'index', $id = 0) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['mensaje_clientes'] = ['tipo' => $tipo, 'texto' => $mensaje];
    
    // Guardamos la ruta de destino en la sesión
    $_SESSION['ir_a'] = [
        'controlador' => 'clientes',
        'accion'      => $accion,
        'id'          => $id
    ];

    header("Location: /appgestionusuarios/public/index.php");
    exit();
}
}