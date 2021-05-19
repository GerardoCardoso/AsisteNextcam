<!DOCTYPE html>
<html lang="es-MX">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="resources/images/nextcam_favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" type="text/css" href="resources/libraries/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="resources/libraries/fontawesome/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="resources/libraries/adminlte/adminLTE.css">
        <link rel="stylesheet" type="text/css" href="resources/libraries/blue/blue.css">
        <link rel="stylesheet" type="text/css" href="resources/css/index.css">
        <link rel="stylesheet" type="text/css" href="resources/css/clock.css">
        <link rel="stylesheet" type="text/css" href="resources/libraries/noty/lib/themes/sunset.css">
        <link rel="stylesheet" type="text/css" href="resources/libraries/noty/lib/noty.css">
        <script src="resources/libraries/jquery/jquery-3.6.0.min.js"></script>
        <script src="resources/libraries/noty/lib/noty.js"></script>
        <title>Asiste Nextcam</title>
    </head>
    <body class="hold-transition lockscreen">
        <?php
            date_default_timezone_set('America/Mexico_City');
            
            // Formato de 24 horas
            $horaInicio= 8;
            $horaFinal= 23;

            $horaActual= intval(date("H"));

            if($horaActual >= $horaInicio && $horaActual <= $horaFinal){
                include('view/asiste.php');
                // Insertar los registros del día anterior en la base de datos remota.
                require 'model/conexion.php';
                respaldarAsistencias();
            }else{
                header('location: https://www.nextcam.com.mx/');
            }
        ?>

        <div class="mini-footer">
            <small>© Copyright Nextcam <?=date("Y")?>. Todos los derechos reservados.</small>
        </div>
    </body>
</html>