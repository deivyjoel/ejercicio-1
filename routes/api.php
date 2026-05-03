<?php
session_start(); 
header('Content-Type: application/json');
try {

    require_once("../config/config.php");
    require_once("../models/Usuario.php");
    require_once("../controller/usuario.php");


    $method = $_SERVER['REQUEST_METHOD'];
    $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $uri = str_replace('/banco_sistema_atc', '', $uri); 
    $response = match(true) {
        $method === 'POST' && $uri === '/auth/register' => fn() => (new AuthController)->register(),
        $method === 'POST' && $uri === '/auth/login'    => fn() => (new AuthController)->login(),
        $method === 'GET'  && $uri === '/usuarios'      => fn() => (new UsuarioController)->index(),
        $method === 'POST' && $uri === '/usuarios'      => fn() => (new UsuarioController)->store(),
        default => function() {
            http_response_code(404);
            echo json_encode(["error" => "Ruta no encontrada"]);
        }
    };

    $response();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error interno", "message" => $e->getMessage()]);
}