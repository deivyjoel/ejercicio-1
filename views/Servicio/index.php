<?php require_once("../Main/sesion.php"); ?>
<!doctype html>
<html lang="es">
<head>
    <?php require_once("../Main/mainhead.php"); ?>
    <title>Banca Unión - Servicios</title>
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
                                <h4 class="page-title mb-1">Servicios Disponibles</h4>
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item active">Servicios</li>
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
                                        <?php if ($usu_rol === 1){ ?>
                                            <button class="btn waves-effect waves-light mb-4" id="btnnuevo" 
                                                    style="background-color:#534AB7; color:#fff; border:none;">
                                                <i class="fa fa-plus-square me-2"></i> Nuevo Servicio
                                            </button>
                                        <?php } ?>

                                        <!-- Tabla DataTable -->
                                        <table id="servicio_data" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Duración Promedio</th>
                                                    <th>Reservar</th>
                                                    <?php if ($usu_rol === 1) { ?>
                                                            <th></th>
                                                            <th></th>
                                                    <?php } ?>
                                            </thead>
                                            <tbody>
                                                <!-- Cargado dinámicamente desde servicio.js -->
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page-content-wrapper -->

            </div>

            <?php require_once("../Main/mainfooter.php"); ?>
        </div>
    </div>

    <!-- SCRIPT -->
    <?php require_once("modalMantenimiento.php"); ?>
    <?php require_once("../Main/mainjs.php"); ?>
    <script src="./servicio.js"></script>
</body>
</html>