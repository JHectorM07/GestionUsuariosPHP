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

    // Devolvemos los datos a la lista
    public function listar(){
        return $this->clienteModel->listar();
    }

    public function obtener($id){
        return $this->clienteModel->obtenerPorId($id);
    }

    // Recibimos el form de guardar
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
            // Mensaje de éxito al crear
            $this->redireccionar("Cliente agregado correctamente", 'success');
        }
    }

    // Recibimos el form de actualizar
    public function actualizar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id       = (int) ($_GET['id'] ?? 0);
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
            // Mensaje de éxito al editar
            $this->redireccionar("Cliente editado correctamente", 'success');
        }
    }

    // Recibe la confirmación de eliminar
    public function borrar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = (int) ($_GET['id'] ?? 0);

            if($id <= 0){
                $this->redireccionar("Cliente no válido", 'danger');
                return;
            }

            $this->clienteModel->eliminar($id);
            // Mensaje de éxito al eliminar
            $this->redireccionar("Cliente eliminado correctamente", 'success');
        }
    }

    // Guarda el mensaje en la sesión y vuelve al listado (o a $destino)
    private function redireccionar($mensaje, $tipo = 'success', $destino = '&accion=index'){

        // Nos aseguramos de que $_SESSION esté iniciada si no se ha iniciado antes
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['mensaje_clientes'] = ['tipo' => $tipo, 'texto' => $mensaje];
        header("Location: /appgestionusuarios/public/index.php?controlador=clientes" . $destino);
        exit();
    }
}