<?php 
    require_once("../../config/conexion.php");
    require_once("../../models/Turno.php");
    require_once("../../models/Servicio.php");
    require_once("../Main/sesion.php"); 

    $turnoModel  = new Turno();
    $servicioModel = new Servicio();
    $usu_id      = $_SESSION['usuario']['id'];
    $turnoActivo = $turnoModel->get_turno_activo($usu_id);
    $tieneTurno = $turnoActivo !== false && $turnoActivo !== null;

    if ($tieneTurno) {
        // PREFIJO
        $tur_pnom = strtoupper(substr($turnoActivo["ser_nom"], 0, 3));
        $tur_pre = $tur_pnom. str_pad($turnoActivo["tur_n_tur"], 3, "0", STR_PAD_LEFT);
        // ------
        $turnoActivo["tur_pre"] = $tur_pre;

        $posicion    = $turnoModel->get_posicion_cola($turnoActivo['tur_id'], $turnoActivo['tur_ser_id']);
        $tur_en_atencion = $turnoModel->get_en_atencion($turnoActivo['tur_ser_id']);
        if ($tur_en_atencion){
            $tur_at_pnom = strtoupper(substr($tur_en_atencion["ser_nom"], 0, 3));
            $tur_at_pre = $tur_pnom. str_pad($tur_en_atencion["tur_n_tur"], 3, "0", STR_PAD_LEFT);
            // ------
            $tur_en_atencion["tur_pre"] = $tur_at_pre;       
        }
        $espera_min  = $posicion * (int) $turnoActivo['ser_dur_prom'];
    }

    // Solo para admin
    $servicios_estado = [];
    if ($usu_rol == 1) {
        $servicios = $servicioModel->get_servicios();
        foreach ($servicios as $ser) {
            $ser_nom = $ser['ser_nom'];
            // PARA EL CODIGO DEL TURNO ACTUAL
            $s_en_atencion = $turnoModel->get_en_atencion($ser['ser_id']);
            if ($s_en_atencion){
                // PREFIX
                $ser_pnom = strtoupper(substr($ser_nom, 0, 3));
                $ser_pre = $tur_pnom. str_pad($s_en_atencion["tur_n_tur"], 3, "0", STR_PAD_LEFT);
                // ------
                $s_en_atencion["tur_pre"] = $ser_pre;      
            }

            // PARA EL CODIGO DEL TURNO SIGUIENTE
            $t_siguiente = $turnoModel->get_siguiente_pendiente($ser['ser_id']);
            if ($t_siguiente){
                $t_nom = strtoupper(substr($ser_nom, 0, 3));
                $t_s_pre = $t_nom. str_pad($t_siguiente["tur_n_tur"], 3, "0", STR_PAD_LEFT);
                $t_siguiente["tur_pre"] = $t_s_pre;
            }
            $servicios_estado[] = [
                'nombre'      => $ser_nom,
                'en_atencion' => $s_en_atencion,
                'siguiente'   => $t_siguiente,
                'en_cola'     => $turnoModel->get_count_pendientes($ser['ser_id']),
            ];
        }
            
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once("../Main/mainhead.php"); ?>
    <title>Dashboard | Sistema de Banco ATC</title>
</head>
<body data-topbar="colored">
    <div id="layout-wrapper">

        <?php require_once("../Main/mainheader.php"); ?>
        <?php require_once("../Main/mainleftsiderbar.php"); ?>

        <div class="main-content">
        <?php if ($usu_rol == 2): ?>
            <div class="page-content">
                <div class="container-fluid pt-4" >
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">

                        <?php if ($tieneTurno): ?>
                            <div class="card" style="width: 360px; border: 1px solid var(--brand-lavender); border-top: 3px solid var(--brand-purple); border-radius: 4px;">
                                <div class="card-body text-center">

                                    <h5 class="card-title mb-1" style="color: var(--brand-deep); font-weight: 600;">Tu turno actual</h5>
                                    <p class="text-muted mb-3" style="font-size: 0.85rem;">Espera tu llamado</p>

                                    <div style="font-size: 3rem; font-weight: 700; color: var(--brand-deep); letter-spacing: 4px;">
                                        <?php echo htmlspecialchars($turnoActivo['tur_pre']);?>
                                    </div>

                                    <hr style="border-color: var(--brand-lavender);">

                                    <div class="text-start mt-3" id="info_cola">
                                        <p class="mb-1"><strong>Servicio:</strong> <?php echo htmlspecialchars($turnoActivo['ser_nom']); ?></p>
                                        <p class="mb-1"><strong>Duración estimada:</strong> <?php echo htmlspecialchars($turnoActivo['ser_dur_prom']); ?> min</p>
                                        <p class="mb-0">
                                            <strong>Estado:</strong>
                                            <?php if ($turnoActivo['tur_est'] == 1): ?>
                                                <span class="badge badge-pendiente">EN ESPERA</span>
                                            <?php else: ?>
                                                <span class="badge badge-atencion">EN ATENCIÓN</span>
                                            <?php endif; ?>
                                        </p>

                                        <?php if ($turnoActivo['tur_est'] == 1): ?>
                                            <hr style="border-color: var(--brand-lavender);">
                                            <p class="mb-1">
                                                <strong>En atención ahora:</strong>
                                                <?php if ($tur_en_atencion): ?>
                                                    <span style="color: var(--brand-purple);"><?php echo $tur_en_atencion['tur_pre']; ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Ninguno</span>
                                                <?php endif; ?>
                                            </p>
                                            <p class="mb-1"><strong>Tu posición en cola:</strong> #<?php echo $posicion + 1; ?></p>
                                            <p class="mb-0"><strong>Espera aprox:</strong> <?php echo $espera_min > 0 ? $espera_min . ' min' : 'Próximo en ser atendido'; ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($turnoActivo['tur_est'] == 1): ?>
                                        <a class="btn waves-effect waves-light mt-3 mb-4 w-100" id="btncancelar" data-tur-id="<?php echo $turnoActivo['tur_id']; ?>"
                                        style="background-color:#534AB7; color:#fff; border:none;">
                                            <i class="mdi mdi-close me-1"></i> Cancelar turno
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </div>

                        <?php else: ?>
                            <a href="../../views/Servicio/" class="btn btn-primary btn-lg px-5 py-3" style="font-size: 1.2rem;">
                                <i class="mdi mdi-calendar-plus me-2"></i> Reservar
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($usu_rol == 1): ?>
        <div class="page-content">
            <div class="container-fluid pt-4">
                <div class="row">
                    <?php foreach ($servicios_estado as $s): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card" style="border-top: 3px solid var(--brand-purple); border-radius: 4px;">
                            <div class="card-body">
                                <h5 style="color: var(--brand-deep); font-weight: 600;"><?php echo htmlspecialchars($s['nombre']); ?></h5>
                                <hr style="border-color: var(--brand-lavender);">
                                <p class="mb-1">
                                    <strong>En atención:</strong>
                                    <?php if ($s['en_atencion']): ?>
                                        <span class="badge badge-atencion">
                                            <?php echo $s['s_en_atencion']['tur_pre']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Ninguno</span>
                                    <?php endif; ?>
                                </p>
                                <p class="mb-1">
                                    <strong>Siguiente:</strong>
                                    <?php if ($s['siguiente']): ?>
                                        <span class="badge badge-pendiente">
                                            <?php echo $s['siguiente']['tur_pre']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Sin cola</span>
                                    <?php endif; ?>
                                </p>
                                <p class="mb-0">
                                    <strong>En cola:</strong> <?php echo $s['en_cola']; ?> turno(s)
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        </div>

        <?php require_once("../Main/mainfooter.php"); ?>
    </div>

    <?php require_once("../Main/mainjs.php"); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="./inicio.js"></script>
</body>
</html>