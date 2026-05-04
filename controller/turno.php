<?php

class TurnoController {

    public function historial_completo() {
        if (empty($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "No autenticado"]);
            return;
        }

        $turno  = new Turno();
        $result = $turno->get_all();

        if (!$result['success']) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $result['error']]);
            return;
        }

        echo json_encode(["status" => "success", "data" => $result['object']]);
    }

    public function mi_turno_activo() {
        if (empty($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "No autenticado"]);
            return;
        }

        $usu_id = $_SESSION['usuario']['id'];
        $turno  = new Turno();
        $result = $turno->get_turno_activo($usu_id);
        if (!$result['success']) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $result['error']]);
            return;
        }

        echo json_encode(["status" => "success", "data" => $result['object']]);
    }

    public function historial() {
        if (empty($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "No autenticado"]);
            return;
        }

        $usu_id = $_SESSION['usuario']['id'];
        $turno  = new Turno();
        $result = $turno->get_turnos_by_usuario($usu_id);

        if (!$result['success']) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $result['error']]);
            return;
        }

        echo json_encode(["status" => "success", "data" => $result['object']]);
    }

    public function reservar() {
        if (empty($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "No autenticado"]);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
            return;
        }

        if (empty($data['ser_id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Falta el servicio"]);
            return;
        }

        if (!is_numeric($data['ser_id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "El servicio debe ser numérico"]);
            return;
        }

        $usu_id = $_SESSION['usuario']['id'];
        $ser_id = (int) $data['ser_id'];
        $turno  = new Turno();

        if ($turno->usuario_tiene_turno_activo($usu_id)) {
            http_response_code(422);
            echo json_encode([
                "status"  => "error",
                "message" => "Ya tienes un turno activo. Debes esperar a que finalice antes de solicitar otro."
            ]);
            return;
        }

        if ($turno->usuario_tiene_turno_en_servicio($usu_id, $ser_id)) {
            http_response_code(422);
            echo json_encode([
                "status"  => "error",
                "message" => "Ya tienes un turno activo para este servicio."
            ]);
            return;
        }

        $result = $turno->insert_turno($usu_id, $ser_id);

        if (!$result['success']) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $result['error']]);
            return;
        }

        http_response_code(201);
        echo json_encode([
            "status"    => "success",
            "message"   => "Turno reservado correctamente",
            "tur_id"    => $result['id'],
            "tur_n_tur" => $result['n_turno'],
            "tur_pre"   => $result['cod_turno']
        ]);
    }

    public function cancelar_reserva() {
        if (empty($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "No autenticado"]);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['tur_id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Falta el id del turno"]);
            return;
        }

        if (!is_numeric($data['tur_id'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "El id del turno debe ser numérico"]);
            return;
        }

        $usu_id = $_SESSION['usuario']['id'];
        $tur_id = (int) $data['tur_id'];
        $turno  = new Turno();
        $result = $turno->cancelar_turno($tur_id, $usu_id);

        if (!$result['success']) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $result['error']]);
            return;
        }

        echo json_encode(["status" => "success", "message" => "Turno cancelado correctamente"]);
    }
}