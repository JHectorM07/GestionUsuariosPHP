<?php

require_once '../app/config/Conexion.php';
require_once '../app/modelos/Cliente.php';

class ClientesController {
    private $db;
    private $clienteModel;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
        $this->clienteModel = new Cliente($this->db);
    }

    // Listar todos los clientes (Se llama desde la vista index.php)
    public function listar() {
        return $this->clienteModel->listar();
    }

    // Obtener un cliente por su ID
    public function obtener($id) {
        return $this->clienteModel->obtenerPorId($id);
    }

    // Guardar (CREAR) nuevo cliente vía AJAX
    public function guardar() {
        $cliente  = $_POST['cliente']  ?? '';
        $email    = $_POST['email']    ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $estado   = $_POST['estado']   ?? 'activo';

        // Corregido: $this->clienteModel en lugar de $this->modelo
        $resultado = $this->clienteModel->crear($cliente, $email, $telefono, $estado);

        header('Content-Type: application/json');
        echo json_encode(['exito' => (bool)$resultado]);
        exit();
    }

    // Actualizar cliente existente vía AJAX
    public function actualizar() {
        $id       = $_POST['id']       ?? 0;
        $cliente  = $_POST['cliente']  ?? '';
        $email    = $_POST['email']    ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $estado   = $_POST['estado']   ?? 'activo';

        // Corregido: $this->clienteModel en lugar de $this->modelo
        $resultado = $this->clienteModel->actualizar($id, $cliente, $email, $telefono, $estado);

        header('Content-Type: application/json');
        echo json_encode(['exito' => (bool)$resultado]);
        exit();
    }

    // Eliminar cliente vía AJAX
    public function borrar() {
        $id = $_POST['id'] ?? 0;

        // Corregido: $this->clienteModel en lugar de $this->modelo
        $resultado = $this->clienteModel->eliminar($id);

        header('Content-Type: application/json');
        echo json_encode(['exito' => (bool)$resultado]);
        exit();
    }
}