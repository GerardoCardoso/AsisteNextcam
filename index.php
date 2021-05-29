<!DOCTYPE html>
<html lang="es-MX">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="resources/images/nextcam_favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="resources/libraries/fontawesome/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="resources/libraries/adminlte/adminLTE.css">
        <link rel="stylesheet" type="text/css" href="resources/libraries/blue/blue.css">
        <link rel="stylesheet" type="text/css" href="resources/css/index.css">
        <link rel="stylesheet" type="text/css" href="resources/css/clock.css">
        <link rel="stylesheet" type="text/css" href="resources/css/btnRespaldo.css">
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
            }else{
                header('location: https://www.nextcam.com.mx/');
            }
        ?>
        
        <div class="mini-footer">
            <small>© Copyright Nextcam <?=date("Y")?>. Todos los derechos reservados. v290521</small>
        </div>
    </body>
</html>