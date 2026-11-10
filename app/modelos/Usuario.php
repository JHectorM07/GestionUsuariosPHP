<?php

class Usuario {
    private $conn;
    private $tabla = "usuarios";

    //Constructor que utilizamos para recibir la conexion a la bd
    public function __construct($db){
        $this->conn = $db;
    }

    //Metodo para obtener un usuario por su username 
    public function obtenerPorUsuario($usuario){
        //parametros ? son para evitar inyeccion SQL
        $query = "SELECT * FROM  {$this->tabla}   WHERE usuario = ? LIMIT 1";

        // Preparamos la consulta para evitar Inyección SQL
        //en la var stmt se guarda el objeto statement, del cual luego haremos uso de sus metodos
        $stmt = $this->conn->prepare($query);

        //Pasamos el valor que debe tomar ?
        $stmt->execute([$usuario]);

        // Retorna un arreglo asociativo con el resultado del usuario o false si no existe
        return $stmt->fetch();
    }

}