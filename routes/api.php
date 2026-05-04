<?php
session_start(); 
header('Content-Type: application/json');
try {

    require_once("../config/config.php");
    require_once("../models/Usuario.php");
    require_once("../controller/usuario.php");
    require_once("../controller/servicio.php");
    require_once("../models/Turno.php");
    require_once("../controller/turno.php");


    $method = $_SERVER['REQUEST_METHOD'];
    $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $uri = str_replace('/banco_sistema_atc', '', $uri); 
    $response = match(true) {
        $method === 'POST' && $uri === '/auth/register' => fn() => (new AuthController)->register(),
        $method === 'POST' && $uri === '/auth/login'    => fn() => (new AuthController)->login(),
        $method === 'POST' && $uri === '/auth/logout' => fn() => (new AuthController)->deslog(),
        $method === 'GET' && $uri === '/servicios' => fn() => (new ServicioController)->listar(),
        $method === 'GET'  && $uri === '/turnos'          => fn() => (new TurnoController)->historial_completo(),
        $method === 'GET'  && $uri === '/turnos/historial'=> fn() => (new TurnoController)->historial(),
        $method === 'POST' && $uri === '/turnos/reservar' => fn() => (new TurnoController)->reservar(),
        $method === 'POST'  && $uri === '/turnos/cancelar'   => fn() => (new TurnoController)->cancelar_reserva(),
        $method === 'GET'  && $uri === '/turnos/activo'   => fn() => (new TurnoController)->mi_turno_activo(),
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