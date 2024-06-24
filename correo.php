<?php
require "vendor/autoload.php"; // Carga las dependencias de Composer

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


$servername="localhost";
$username="root";
$password="";
$dbname="correos";

$conn = mysqli_connect($servername,$username,$password,$dbname);

if(!$conn){
    die("Error de conexion: " . mysqli_connect_error());
}

function correo($conn){


    $sql = "SELECT indice, nombre, dni, email, archivoname, envio , fecha FROM emails WHERE envio = 0 LIMIT 10";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()) {
            sendMail($row,$conn);
        }
    }

    $slq_update = "UPDATE emails SET envio = 1, fecha = CURRENT_TIME WHERE envio = 0 LIMIT 10";
    $conn->query($slq_update);

    correospendientes($conn);
 }

 function sendMail($data,$conn){
    $mail = new PHPMailer(true);
   
    try{ 
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->Host ='smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure ='tls';
        $mail->Username ='noreply.eijeusc@gmail.com';
        $mail->Password  ='jzqwjnadfpdbqldx';
        $mail->Port = 587;
        $mail->setFrom('noreply.eijeusc@gmail.com', 'EIJE2024');
    
        $data['nombre'] = mb_convert_encoding($data['nombre'], 'ISO-8859-1');

        $mail->addAddress('manuel.aular@avaforum.com');
        // $mail->addAddress($data['email']);

        $mail->Subject = 'Certificado asistencia EIJE 2024';
        $mail->Body = "Se le entrega el Siguiente Certificado: ". $data['email'];
        $mail->addAttachment('documentos/pdf/'. $data['archivoname']);
        $mail->send();
        
        echo "Mensaje enviado a: ". $data['email'] ."<br>";
    } catch (Exception $e){
        echo "Exception: ". $e->getMessage() ."<br>";
        echo "Mailer Error: {$mail->ErrorInfo}" . "<br>";

        $slq_update= "UPDATE emails SET envio = 0, fecha = CURRENT_TIME WHERE indice = ". $data['indice'];
        $conn->query($slq_update);
    } 
 }


 function correospendientes($conn){
    
    $sql = "SELECT email FROM emails WHERE envio = 0 ";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        echo "<br>Correos que falta por enviar <br> ";
        while($row = $result->fetch_assoc()) {
            echo "Correos pendientes: " . $row['email']. "<br>";
        }
    }else{
        echo "No hay correos pendientes. <br>";
    }

 }

?>