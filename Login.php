<?php

session_start();

//credenciales

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'registro';


//conexion a la base de datos

$conexion = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (!$conexion) {

    // Error al conectarse
    exit('Error al conectarse con la base de datos: ' . mysqli_connect_error());
}

$username = $_POST['username'];
$password = $_POST['password'];

 //consulta
$query= mysqli_query($conexion,"SELECT * FROM cuentas WHERE username = '".$username."' and password = '".$password."'");

$result = mysqli_num_rows($query);
mysqli_close($conexion);

//verificacion 
if($result == 1){
    $_SESSION['username'] = $username;
    //redirigir
    header('Location: inicio.php');
}else{
    echo "Nombre de Usuario o contraseña incorrectos";
}

?>