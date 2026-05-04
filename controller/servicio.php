<?php
require_once("../config/config.php");
require_once("../models/Servicio.php");

class ServicioController {

    public function listar() {
        $servicio = new Servicio();
        $result = $servicio->get_servicio();

        if (!$result['success']) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error al obtener servicios"]);
            return;
        }

        echo json_encode(["status" => "success", "data" => $result['object']]);
    }
}