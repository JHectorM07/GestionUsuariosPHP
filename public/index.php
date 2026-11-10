<?php 

session_start();
//Si no viene nada en la url, le asignamos login
$controlador = $_GET['controlador'] ?? 'login';
//Si no viene nada en la url, le asignamos index
$accion = $_GET['accion'] ?? 'index';

//Enrutador
if($controlador === 'login'){
  require_once '../app/controladores/AuthController.php';
  $auth = new AuthController();

    if($accion === 'ingresar'){
      $auth->ingresar();
    }elseif($accion === 'salir'){
      $auth->Salir();
    }else{
      require_once '../app/vistas/auth/login.php';
    }

  }elseif($controlador === 'clientes' && in_array($accion, ['guardar', 'actualizar', 'borrar'])){
    // Comprobamos si no existe la sesion del usuaro
    if(!isset($_SESSION['usuario_id'])){
      header('Location: /appgestionusuarios/public/index.php?controlador=login');
      exit();
  }
  require_once '../app/controladores/ClientesController.php';
  $clientesController = new ClientesController();
  $clientesController->$accion();

  }elseif($controlador === 'contratos' && in_array($accion, ['guardar', 'actualizar', 'borrar'])){
    if(!isset($_SESSION['usuario_id'])){
      header('Location: /appgestionusuarios/public/index.php?controlador=login');
      exit();
    }
    require_once '../app/controladores/ContratosController.php';
    $contratosController = new ContratosController();
    $contratosController->$accion();

}else{
  // Cualquier otro controlador lo mandamos al switch del dashboard
  require_once '../app/vistas/dashboard/index.php';
}

