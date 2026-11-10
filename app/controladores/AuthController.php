    <?php

    require_once '../app/config/Conexion.php';
    require_once '../app/modelos/Usuario.php';
    class AuthController {
        private $db;
        private $usuarioModel;
        
        public function __construct(){
            $conexion = new Conexion();
            $this->db = $conexion->getConexion();
            $this->usuarioModel = new Usuario($this->db);
        }

        //Procesamos el intento de login
        public function ingresar(){
            //verificamos que la peticion sea post
            if($_SERVER['REQUEST_METHOD']=== 'POST'){

                $usuario = trim($_POST['usuario'] ?? '');
                $password = trim($_POST['password'] ?? '');

                if(empty($usuario) || empty($password)){
                    $this->redireccionarConError("Por farvor, completa todos los campos");
                    return;
                }

                //Consultamos si el usuario existe en la bd
                $datosUsuario = $this->usuarioModel->obtenerPorUsuario($usuario);

                //verificamos si existe el usuario y la contraseña coincide con el hash
                if($datosUsuario && $password === $datosUsuario['password']){

                    //Iniciamos sesion con el usuario
                    $_SESSION['usuario_id'] = $datosUsuario['usuario_id'];
                    $_SESSION['usuario_nombre'] = $datosUsuario['nombres'];

                    // Redireccionar al Dashboard principal
                    header("Location: /appgestionusuarios/public/index.php?controlador=dashboard");
                    exit();
                } else{
                    $this->redireccionarConError("Credenciales invalidas");
                }
            }

            
        }
        // Finalizar sesión (REPASAR)
        public function Salir(){
            session_destroy();
            header("Location: /appgestionusuarios/public/index.php?controlador=login");
            exit();
        }

        private function redireccionarConError($mensaje){
            $_SESSION['error_login'] = $mensaje;
            header("Location: /appgestionusuarios/public/index.php?controlador=login");
            exit();
        }
    
    }