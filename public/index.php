<?php 

session_start();

// Destino guardado por una redirección (se usa una sola vez)
$destino = $_SESSION['ir_a'] ?? [];
unset($_SESSION['ir_a']);

$controlador = $_POST['controlador'] ?? $_GET['controlador'] ?? $destino['controlador']
               ?? (isset($_SESSION['usuario_id']) ? 'dashboard' : 'login');

$accion      = $_POST['accion'] ?? $_GET['accion'] ?? $destino['accion'] ?? 'index';

// Capturamos también el ID si venía guardado en la sesión flash
if (!isset($_POST['id']) && !isset($_GET['id']) && !empty($destino['id'])) {
    $_REQUEST['id'] = $_POST['id'] = $_GET['id'] = $destino['id'];
}

// 1. Módulo de Autenticación
if ($controlador === 'login') {
    require_once '../app/controladores/AuthController.php';
    $auth = new AuthController();

    if ($accion === 'ingresar') {
        $auth->ingresar();
    } elseif ($accion === 'salir') {
        $auth->Salir();
    } else {
        require_once '../app/vistas/auth/login.php';
    }
    exit();
}

// Comprobación global de sesión activa para el resto de módulos
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /appgestionusuarios/public/index.php?controlador=login');
    exit();
}

// 2. Procesamiento de acciones POST/AJAX de Clientes
if ($controlador === 'clientes' && in_array($accion, ['guardar', 'actualizar', 'borrar'])) {
    require_once '../app/controladores/ClientesController.php';
    $clientesController = new ClientesController();
    $clientesController->$accion();
    exit();
}

// 3. Procesamiento de acciones POST/AJAX de Contratos
if ($controlador === 'contratos' && in_array($accion, ['guardar', 'actualizar', 'borrar'])) {
    require_once '../app/controladores/ContratosController.php';
    $contratosController = new ContratosController();
    $contratosController->$accion();
    exit();
}

// 4. Carga del Layout Principal (Dashboard y Vistas GET)
require_once '../app/vistas/dashboard/index.php';