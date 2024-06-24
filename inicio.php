<?php
require "vendor/autoload.php"; // Carga las dependencias de Composer

session_start();
require_once 'generador.php';
require_once 'correo.php';

if (!isset($_SESSION['username'])) {
   header('Location: index.html');
   exit;
}

if(isset($_POST['pdf'])) {
    generador();
}

if(isset($_POST['correo'])) {
    correo($conn);
}
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-type" content="text/html"; charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Login/Style.css">
    <title>inicio</title>
</head>

<body>
    <div class="content">
        <p> Hola de nuevo, <?php echo $_SESSION['username']; ?> !!! </p>
    </div>

    <div class="navtop">
        <a href="cerrar_sesion.php">
            <p>Salir</p>
        </a>
    </div>

    <form method="POST" action="inicio.php">
        <input type="hidden" name="pdf" value="1">
        <button type="submit" value="generador">Generar PDF </button>
    </form>
    <br>
    <form method="POST" action="inicio.php"> 
    <input type="hidden" name="correo" value="1">
        <button type="submit" value="correo">Enviar correo </button>
    </form>

</body>

</html>

