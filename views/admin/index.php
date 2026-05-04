<?php
session_start();
if(empty($_SESSION['usuario'])){
    header('Location: login/inicio_sesion.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Banca Unión</title>
        <link rel="stylesheet" href="../style_index.css">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="app">
            <nav class="navbar">
                <p>Hola, administrador <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>!</p>
                <p>Banca Unión</p>
            </nav>
            <div class="main">
                <aside class="sidebar">
                    <h2>Banca Unión</h2>
                    <a href="#" id="btn-inicio">Inicio</a>
                    <a href="#" id="btn-logout">Cerrar sesión</a>
                </aside>

                <section class="content">
                </section>

            </div>
        </div>
        <script src="index.js"></script>
    </body>
</html>