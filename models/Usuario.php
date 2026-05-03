<?php

class Usuario extends Conectar{

    public function register($nombre, $email, $password){
        try{
            $conectar = parent::Conexion();

            // Verifica email duplicado
            $stmt = $conectar->prepare("SELECT usu_id FROM b_usuario WHERE usu_email=?");
            $stmt->bindValue(1, $email);
            $stmt->execute();
            if ($stmt->fetch()) {
                return ["success" => false, "error" => "El email ya está registrado"];
            }

            // Hashear contraseña
            $hash = password_hash($password, PASSWORD_BCRYPT);


            // Insertar usuario
            $sql = "
                INSERT INTO b_usuario(usu_nom, usu_email, usu_pass, usu_rol)
                VALUES (?, ?, ?, 'usuario')
            ";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $nombre);
            $stmt->bindValue(2, $email);
            $stmt->bindValue(3, $hash);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                return ["success" => false, "error" => "No se pudo crear el usuario"];
            }

            return ["success" => true];
        } catch(Throwable $e){
            error_log("ERROR en register: " . $e->getMessage());
            return [
                "success" => false,
                "error" => "Error al crear el usuario"
            ];
        }
    }



    public function login($email, $password){
        try{
            $conectar = parent::Conexion();
            
            // Busca al usuario por email
            $sql = "SELECT * FROM b_usuario WHERE usu_email = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Validar contraseña
            if ($user && password_verify($password, $user["usu_pass"])) {
                return [
                    "success" => true,
                    "user" => $user 
                ];
            }

            return ["success" => false];
            } catch(Throwable $e){
                error_log("ERROR en login: " . $e->getMessage());
                return [
                    "success" => false,
                    "error" => "Error al logear el usuario"
                ];
            }
    }
}
?>