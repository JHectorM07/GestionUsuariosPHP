<?php
require_once '../app/controladores/ContratosController.php';
$contratosController = new ContratosController();

// $accion viene desglosada desde public/index.php
switch ($accion) {
    case 'crear':
        include_once __DIR__ . '/acciones/crear.php';
        break;
    case 'editar':
        include_once __DIR__ . '/acciones/editar.php';
        break;
    case 'ver':
        include_once __DIR__ . '/acciones/ver.php';
        break;
    case 'eliminar':
        include_once __DIR__ . '/acciones/eliminar.php';
        break;
    default:
        include_once __DIR__ . '/listado.php';
        break;
}