<?php
session_start();
if(empty($_SESSION['usuario'])){
    header('Location: inicio_sesion.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Banca Unión</title>
        <link rel="stylesheet" href="styles.css">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="app">
            <nav class="navbar">
                <p>Hola, User...</p>
                <p>Banca Unión</p>
            </nav>
            <div class="main">
                <aside class="sidebar">
                    <h2>Banca Unión</h2>
                    <a href="index.php?vista=inicio">Inicio</a>
                    <a href="index.php?vista=perfil">Mi perfil</a>
                    <a href="index.php?accion=cerrar_sesion">Cerrar sesión</a>
                </aside>

                <section class="content">
                </section>

            </div>
        </div>
        <script src="app.js"></script>
    </body>
</html>