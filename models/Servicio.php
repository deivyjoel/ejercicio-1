<?php

    class Servicio extends Conectar{

        public function get_servicio(){
            try{
                $conectar = parent::Conexion();

                $sql = "SELECT ser_id, ser_nom, ser_dur_prom FROM b_servicio WHERE ser_est=1";
                $stmt = $conectar->prepare($sql);
                $stmt->execute();

                return [
                    "success" => true,
                    "object" => $stmt->fetchAll(PDO::FETCH_ASSOC)
                ];
            } catch(Throwable $e){
            error_log("ERROR en register: " . $e->getMessage());
            return [
                "success" => false,
                "error" => "Error al listar servicios"];
            }
        }

    }

?>