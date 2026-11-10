<?php

class Conexion {

    private $host = "localhost";
    private $db_name = "gestionclientes";
    private $username = "root";
    private $password = "";
    public $conn;

    //Metodo para obtener la conexion 
    public function getConexion(){
        $this->conn = null; //Sirve para que no queden restos de conexiones anteriores no validas

        try{
            //Realizamos la conexion usando la clase nativa PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" .$this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    //Hacemos una configuracion para que PDO lance excepciones si algo falla
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    //Hacemos una configuracion para que las consultas devuelvan los datos con 
                    //arreglos asociativos
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC

                ]
            );
        }catch(PDOException $e){
            die("Error en la conexion a la base de datos: " .$e->getMessage());
        }

        return $this->conn;
    }
}

//Prueba temporal de conexion
//$prueba = new Conexion();
//$db = $prueba->getConexion();
//if($db) {echo "Conexion exitosa";}