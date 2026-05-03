<?php

class AuthController{

    public function register(){
        $data = json_decode(file_get_contents('php://input'), true);

        // Verificar que el body llegó bien
        if (!$data) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
            return;
        }

        // Verificar campos vacíos
        if (empty($data['nombre']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Completa todos los campos"]);
            return;
        }

        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(422);
            echo json_encode(["status" => "error", "message" => "Email inválido"]);
            return;
        }

        // Validar longitud de contraseña
        if (strlen($data['password']) < 8) {
            http_response_code(422);
            echo json_encode(["status" => "error", "message" => "Contraseña muy corta"]);
            return;
        }

        $usuario = new Usuario();
        $result  = $usuario->register($data['nombre'], $data['email'], $data['password']);

        if (!$result['success']) {
            http_response_code(422);
            echo json_encode(["status" => "error", "message" => $result['error']]);
            return;
        }

        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "Cuenta creada correctamente"]);
    }

    public function login(){
        $data = json_decode(file_get_contents('php://input'), true);

        // Verificar que el body llegó bien
        if (!$data) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
            return;
        }

        // Verificar campos vacíos
        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Completa todos los campos"]);
            return;
        }

        $usuario = new Usuario();
        $result = $usuario->login($data['email'], $data['password']);

        if (!$result['success']) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
            return;
        }

        $_SESSION['usuario'] = [
            'id'     => $result['user']['usu_id'],
            'nombre' => $result['user']['usu_nom'],
            'email'  => $result['user']['usu_email'],
            'rol'    => $result['user']['usu_rol']
        ];

        echo json_encode(["status" => "success", "message" => "Bienvenido"]);
    }
}