<?php

class Turno extends Conectar {

    public function get_all() {
        try {
            $conectar = parent::Conexion();
            $sql = "SELECT t.*, s.ser_nom, u.usu_nom
                    FROM b_turno t
                    INNER JOIN b_servicio s ON t.tur_ser_id = s.ser_id
                    INNER JOIN b_usuario u  ON t.tur_usu_id = u.usu_id
                    ORDER BY t.tur_id DESC";
            $stmt = $conectar->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("ERROR en get_all: " . $e->getMessage());
            return [];
        }
    }

    public function get_turnos_by_usuario($usu_id) {
        try {
            $conectar = parent::Conexion();
            $sql = "SELECT t.*, s.ser_nom
                    FROM b_turno t
                    INNER JOIN b_servicio s ON t.tur_ser_id = s.ser_id
                    WHERE t.tur_usu_id = ?
                    ORDER BY t.tur_id DESC";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id);
            $stmt->execute();
            $listad = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $listad;
        } catch (Throwable $e) {
            error_log("ERROR en get_turnos_by_usuario: " . $e->getMessage());
            return [];
        }
    }

    public function get_turno_activo($usu_id) {
        try {
            $conectar = parent::Conexion();
            $sql = "SELECT t.*, s.ser_nom, s.ser_dur_prom
                    FROM b_turno t
                    INNER JOIN b_servicio s ON t.tur_ser_id = s.ser_id
                    WHERE t.tur_usu_id = ?
                    AND t.tur_est IN (1, 2)
                    LIMIT 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Throwable $e) {
            error_log("ERROR en get_turno_activo: " . $e->getMessage());
            return ["success" => false, "object" => null, "error" => "Error al obtener turno activo"];
        }
    }

    public function get_turno_x_id($tur_id) {
        try {
            $conectar = parent::Conexion();
            $sql = "SELECT t.*, s.ser_nom, u.usu_nom
                    FROM b_turno t
                    INNER JOIN b_servicio s ON t.tur_ser_id = s.ser_id
                    INNER JOIN b_usuario u  ON t.tur_usu_id = u.usu_id
                    WHERE t.tur_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $tur_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("ERROR en get_by_id: " . $e->getMessage());
            return null;
        }
    }

    public function usuario_tiene_turno_activo($usu_id) {
        try {
            $conectar = parent::Conexion();
            $sql  = "SELECT tur_id FROM b_turno WHERE tur_usu_id = ? AND tur_est IN (1, 2) LIMIT 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id);
            $stmt->execute();
            return $stmt->fetch() !== false;
        } catch (Throwable $e) {
            error_log("ERROR en usuario_tiene_turno_activo: " . $e->getMessage());
            return true;
        }
    }

    public function usuario_tiene_turno_en_servicio($usu_id, $ser_id) {
        try {
            $conectar = parent::Conexion();
            $sql  = "SELECT tur_id FROM b_turno WHERE tur_usu_id = ? AND tur_ser_id = ? AND tur_est IN (1, 2) LIMIT 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id);
            $stmt->bindValue(2, $ser_id);
            $stmt->execute();
            return $stmt->fetch() !== false;
        } catch (Throwable $e) {
            error_log("ERROR en usuario_tiene_turno_en_servicio: " . $e->getMessage());
            return true;
        }
    }

    public function insert_turno($usu_id, $ser_id) {
        try {
            $conectar = parent::Conexion();

            $stmt = $conectar->prepare("SELECT COALESCE(MAX(tur_n_tur), 0) + 1 FROM b_turno WHERE tur_ser_id = ?");
            $stmt->bindValue(1, $ser_id);
            $stmt->execute();
            $siguiente = $stmt->fetchColumn();

            $stmt = $conectar->prepare("INSERT INTO b_turno (tur_usu_id, tur_ser_id, tur_n_tur, tur_est) VALUES (?, ?, ?, 1)");
            $stmt->bindValue(1, $usu_id);
            $stmt->bindValue(2, $ser_id);
            $stmt->bindValue(3, $siguiente);
            $stmt->execute();

            $id = $conectar->lastInsertId();

            return ["success" => true, "id" => $id, "n_turno" => $siguiente];
        } catch (Throwable $e) {
            error_log("ERROR en insert_turno: " . $e->getMessage());
            return ["success" => false, "error" => "Error al insertar turno"];
        }
    }

    public function servicio_tiene_turno_activo($ser_id) {
        try {
            $conectar = parent::Conexion();
            $sql = "SELECT tur_id FROM b_turno WHERE tur_ser_id = ? AND tur_est = 2 LIMIT 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $ser_id);
            $stmt->execute();
            return $stmt->fetch() !== false;
        } catch (Throwable $e) {
            error_log("ERROR en servicio_tiene_turno_en_atencion: " . $e->getMessage());
            return true;
        }
    }

    //1: pendiente 2: en atencion 3: cancelado 4: atendido
    public function cambiar_estado($tur_id, $tur_est) {
        try {
            $conectar = parent::Conexion();
            if ($tur_est == 3) {
                $sql = "UPDATE b_turno
                        SET
                            tur_est = ?,
                            tur_fec_edi = NOW(),
                            tur_fec_del = NOW()
                        WHERE tur_id = ?";
            } else {
                $sql = "UPDATE b_turno
                        SET
                            tur_est = ?,
                            tur_fec_edi = NOW()
                        WHERE tur_id = ?";
            }
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $tur_est);
            $stmt->bindValue(2, $tur_id);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return ["success" => false, "error" => "No se pudo cambiar el estado del turno"];
            }
            return ["success" => true];
            
        } catch (Throwable $e) {
            error_log("ERROR en cambiar_estado: " . $e->getMessage());
            return ["success" => false, "error" => "Error al cambiar estado"];
        }
    }

    public function get_en_atencion($ser_id) {
        try {
            $conectar = parent::Conexion();
            $sql = "SELECT t.*, s.ser_nom, u.usu_nom
                    FROM b_turno t
                    INNER JOIN b_servicio s ON t.tur_ser_id = s.ser_id
                    INNER JOIN b_usuario u  ON t.tur_usu_id = u.usu_id
                    WHERE t.tur_ser_id = ? AND t.tur_est = 2
                    LIMIT 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $ser_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            error_log("ERROR en get_en_atencion: " . $e->getMessage());
            return null;
        }
    }

    public function get_posicion_cola($tur_id, $ser_id) {
        try {
            $conectar = parent::Conexion();
            $sql = "SELECT COUNT(*) FROM b_turno 
                    WHERE tur_ser_id = ? 
                    AND tur_est = 1
                    AND tur_id < ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $ser_id);
            $stmt->bindValue(2, $tur_id);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Throwable $e) {
            error_log("ERROR en get_posicion_cola: " . $e->getMessage());
            return 0;
        }
    }

    public function get_count_pendientes($ser_id) {
        try {
            $conectar = parent::Conexion();
            $sql = "SELECT COUNT(*) FROM b_turno WHERE tur_ser_id = ? AND tur_est = 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $ser_id);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (Throwable $e) {
            error_log("ERROR en get_count_pendientes: " . $e->getMessage());
            return 0;
        }
    }

    public function get_siguiente_pendiente($ser_id) {
        try {
            $conectar = parent::Conexion();
            $sql = "SELECT t.*, s.ser_nom
                    FROM b_turno t
                    INNER JOIN b_servicio s ON t.tur_ser_id = s.ser_id
                    WHERE t.tur_ser_id = ? AND t.tur_est = 1 
                    ORDER BY t.tur_id ASC LIMIT 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $ser_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            error_log("ERROR en get_siguiente_pendiente: " . $e->getMessage());
            return null;
        }
    }
}
?>