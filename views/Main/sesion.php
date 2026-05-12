<?php
session_start();
if (!isset($_SESSION["usuario"]["rol"]) || !isset($_SESSION["usuario"]["nombre"])) {
    header("location: ../../index.php");
    exit();
}

$usu_rol = $_SESSION["usuario"]["rol"];
$usu_nom = $_SESSION["usuario"]["nombre"];
error_log("USU_ROL: " . $usu_rol);
?>