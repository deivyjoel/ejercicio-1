<?php
session_start();
require_once("../config/conexion.php");
require_once("../models/Turno.php");

$turno = new Turno();

switch ($_GET["op"]) {

    case 'listar_usuario':       
        if (empty($_SESSION['usuario'])) {
            echo json_encode(["sEcho" => 1, "iTotalRecords" => 0, "iTotalDisplayRecords" => 0, "aaData" => []]);
            break;
        }

        $usu_rol = $_SESSION['usuario']['rol'];

        if ($usu_rol === 1){
            $datos = $turno->get_all();

            $data = array();
            foreach ($datos as $i => $row) {
                $sub_array = array();
                $tur_pre = strtoupper(substr($row["ser_nom"], 0, 3));
                $sub_array[] = $tur_pre . str_pad($row["tur_n_tur"], 3, "0", STR_PAD_LEFT);
                $sub_array[] = $row["ser_nom"]; 
                $sub_array[] = date("d/m/Y h:i:s A", strtotime($row["tur_fec_hor"]));
                $sub_array[] = $row["usu_nom"];
                $sub_array[] = '
                        <button class="btn btn-sm" style="font-size:0.85em; background-color:#CECBF6; color:#3C3489; border:none;" onclick="cambiarEstado(' . $row["tur_id"] . ')">
                            <i class="mdi mdi-eye"></i> DETALLE
                        </button>';   

                switch ($row["tur_est"]) {
                    case 1:
                        $sub_array[] = '<span class="badge" style="font-size:1em; background-color:#CECBF6; color: #3C3489">EN ESPERA</span>';
                        break;
                    case 2:
                        $sub_array[] = '<span class="badge" style="font-size:1em; background-color:#534AB7; color: #FFFFFF">ATENDIENDO</span>';
                        break;
                    case 4:
                        $sub_array[] = '<span class="badge" style="font-size:1em; background-color:#1DB97A; color:#ffffff">ATENDIDO</span>';
                        break;
                    case 3:
                        $sub_array[] = '<span class="badge" style="font-size:1em; background-color:#E24B4A; color:#ffffff">CANCELADO</span>';
                        break;
                    default:
                        $sub_array[] = '<span class="badge" style="font-size:1em; background-color:gray;">' . $row["tur_est"] . '</span>';
                        break;
                }

                $data[] = $sub_array;
            }

            $response = [
                "sEcho"                => 1,
                "iTotalRecords"        => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData"               => $data
            ];

            $json = json_encode($response);
            echo $json;
            break;            

        } else {
            $usu_id = $_SESSION['usuario']['id'];
            $datos = $turno->get_turnos_by_usuario($usu_id);

            $data = array();
            foreach ($datos as $i => $row) {
                $sub_array = array();
                $tur_pre = strtoupper(substr($row["ser_nom"], 0, 3));
                $sub_array[] = $tur_pre . str_pad($row["tur_n_tur"], 3, "0", STR_PAD_LEFT);
                $sub_array[] = $row["ser_nom"];
                $sub_array[] = date("d/m/Y h:i:s A", strtotime($row["tur_fec_hor"]));

                switch ($row["tur_est"]) {
                    case 1:
                        $sub_array[] = '<span class="badge" style="font-size:0.85em; background-color:#CECBF6; color:#3C3489;">EN ESPERA</span>';
                        break;
                    case 2:
                        $sub_array[] = '<span class="badge" style="font-size:0.85em; background-color:#534AB7; color:#FFFFFF;">EN ATENCIÓN</span>';
                        break;
                    case 4:
                        $sub_array[] = '<span class="badge" style="font-size:0.85em; background-color:#1DB97A; color:#FFFFFF;">ATENDIDO</span>';
                        break;
                    case 3:
                        $sub_array[] = '<span class="badge" style="font-size:0.85em; background-color:#E24B4A; color:#FFFFFF;">CANCELADO</span>';
                        break;
                    default:
                        $sub_array[] = '<span class="badge" style="font-size:0.85em; background-color:#CECBF6; color:#3C3489;">' . $row["tur_est"] . '</span>';
                        break;
                }


                $data[] = $sub_array;
            }

            $response = [
                "sEcho"                => 1,
                "iTotalRecords"        => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData"               => $data
            ];

            $json = json_encode($response);
            echo $json;
            break;
        }
        
    case 'turno_activo':
        if (empty($_SESSION['usuario'])) {
            echo json_encode(["success" => false, "message" => "No autenticado"]);
            break;
        }

        $usu_id = $_SESSION['usuario']['id'];
        $datos = $turno->get_turno_activo($usu_id);

        if ($datos) {
            // PREFIJO
            $tur_pnom = strtoupper(substr($datos["ser_nom"], 0, 3));
            $tur_pre = $tur_pnom. str_pad($datos["tur_n_tur"], 3, "0", STR_PAD_LEFT);
            // ------
            $datos["tur_pre"] = $tur_pre;
            echo json_encode(["success" => true, "data" => $datos]);
        } else {
            echo json_encode(["success" => false, "message" => "Sin turno activo"]);
        }
        break;

    case 'reservar':
        if (empty($_SESSION['usuario'])) {
            echo json_encode(["success" => false, "message" => "No autenticado"]);
            break;
        }

        try {
            $ser_id = $_POST["ser_id"];

            if (empty($ser_id) || !is_numeric($ser_id)) {
                throw new Exception("Servicio inválido.");
            }

            $usu_id = (int) $_SESSION['usuario']['id'];
            $ser_id = (int) $ser_id;

            if ($turno->usuario_tiene_turno_activo($usu_id)) {
                throw new Exception("Ya tienes un turno activo. Debes esperar a que finalice antes de solicitar otro.");
            }

            if ($turno->usuario_tiene_turno_en_servicio($usu_id, $ser_id)) {
                throw new Exception("Ya tienes un turno activo para este servicio.");
            }


            $result = $turno->insert_turno($usu_id, $ser_id);

            if (!$result['success']) {
                throw new Exception($result['error']);
            }

            $response = [
                "success"    => true,
                "message"    => "Turno reservado correctamente.",
                "tur_id"     => $result['id'],
                "tur_n_tur"  => $result['n_turno']
            ];
        } catch (Exception $e) {
            $response = ["success" => false, "message" => $e->getMessage()];
        }

        echo json_encode($response);
        break;

    case 'cancelar':
        if (empty($_SESSION['usuario'])) {
            echo json_encode(["success" => false, "message" => "No autenticado"]);
            break;
        }

        try {
            $tur_id = $_POST["tur_id"];

            if (empty($tur_id) || !is_numeric($tur_id)) {
                throw new Exception("ID de turno inválido.");
            }

            $usu_id = (int) $_SESSION['usuario']['id'];
            $tur_id = (int) $tur_id;
            $result = $turno->cambiar_estado($tur_id, 3);

            if (!$result['success']) {
                throw new Exception($result['error']);
            }

            $response = ["success" => true, "message" => "Turno cancelado correctamente."];
        } catch (Exception $e) {
            $response = ["success" => false, "message" => $e->getMessage()];
        }

        echo json_encode($response);
        break;



    case 'mostrar':
        $tur_id = $_POST['tur_id'];
        $dato = $turno->get_turno_x_id($tur_id);
        // PREFIJO
        $tur_pnom = strtoupper(substr($dato["ser_nom"], 0, 3));
        $tur_pre = $tur_pnom. str_pad($dato["tur_n_tur"], 3, "0", STR_PAD_LEFT);
        // ------
        $dato["tur_pre"] = $tur_pre;
        echo json_encode($dato);
        break;

    case 'cambiar_estado':
        $tur_id     = (int) $_POST['tur_id'];
        $ser_id = (int) $_POST['ser_id'];
        $estado = (int) $_POST['estado'];

        // Validar que el estado sea válido
        $permitidos = [2, 3, 4];
        if (!in_array($estado, $permitidos)) {
            echo json_encode(["success" => false, "mensaje" => "Estado no válido"]);
            break;
        }

        // Regla: no dos 'atendiendo' al mismo tiempo por servicio
        if ($estado === 2) {
            $hayAtendiendo = $turno->servicio_tiene_turno_activo($ser_id);
            if ($hayAtendiendo) {
                echo json_encode(["success" => false, "mensaje" => "Ya hay un turno en atención para este servicio"]);
                break;
            }
        }

        // Regla plus jeje :(, asi no malogra de los turnos de las cards
        if ($estado === 2) {
            $siguiente = $turno->get_siguiente_pendiente($ser_id);
            if ($siguiente && (int)$siguiente['tur_id'] !== $tur_id) {
                echo json_encode(["success" => false, "mensaje" => "Respetar el orden de la cola"]);
                break;
            }
        }   

        $ok = $turno->cambiar_estado($tur_id, $estado);
        echo json_encode(["success" => $ok]);
        break;

    case 'info_cola':        
        $tur_id = $_POST['tur_id'];
        $ser_id = $_POST['ser_id'];
        
        $posicion    = $turno->get_posicion_cola($tur_id, $ser_id);
        $en_atencion = $turno->get_en_atencion($ser_id);
        $dur_prom    = $_POST['dur_prom'];
        $espera      = $posicion * $dur_prom;

        $cod_en_atencion = null;
        if ($en_atencion) {
            $ser_nom_clean = strtr($en_atencion['ser_nom'], [
                'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u',
                'Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U',
            ]);
            $pre = strtoupper(substr($ser_nom_clean, 0, 3));
            $cod_en_atencion = $pre . str_pad($en_atencion['tur_n_tur'], 3, '0', STR_PAD_LEFT);
        }

        echo json_encode([
            "success"     => true,
            "posicion"    => $posicion,
            "espera_min"  => $espera,
            "en_atencion" => $cod_en_atencion,
        ]);
        break;

    default:
        break;
}
?>