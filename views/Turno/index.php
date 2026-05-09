<?php require_once("../Main/sesion.php"); ?>
<!doctype html>
<html lang="es">
<head>
    <?php require_once("../Main/mainhead.php"); ?>
    <title>Banca Unión - Mis Turnos</title>
</head>
<body data-topbar="colored">
    <div id="layout-wrapper">

        <?php require_once("../Main/mainheader.php"); ?>
        <?php require_once("../Main/mainleftsiderbar.php"); ?>

        <div class="main-content">
            <div class="page-content">

                <!-- Page-Title -->
                <div class="page-title-box">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="page-title mb-1">Historial de Turnos</h4>
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item active">Mis Turnos</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="page-content-wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">

                                        <table id="turno_data" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Servicio</th>
                                                    <th>Fecha</th>
                                                    <?php if ($usu_rol === 1) { ?>
                                                            <th>Usuario</th>
                                                    <?php } ?>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Cargado dinámicamente desde turno.js -->
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
            <?php require_once("../Main/mainfooter.php"); ?>
        </div>
    </div>

    <?php require_once("modalMantenimiento.php"); ?>
    <?php require_once("../Main/mainjs.php"); ?>
    <script src="./turno.js"></script>
</body>
</html>