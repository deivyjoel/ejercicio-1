<?php
    class Conectar{
        protected $dbh;

        protected function Conexion(){
            try{
                $conectar = $this->dbh = new PDO(
                    "mysql:host=localhost;dbname=banco_sistema_atc;charset=utf8mb4", 
                    "root", 
                    "",
                    
                    // Permite que PDO lance una excepción automaticamente si falla prepare(), bindValue(), execute()
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                    ]
                    );
                    return $conectar;
            }catch(Exception $e){
                http_response_code(500);
                echo json_encode(["success" => false, "error" => "Error de conexión a la base de datos"]);
                die();
            }
        }
    }



?>