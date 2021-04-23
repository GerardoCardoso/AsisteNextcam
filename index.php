<?php
date_default_timezone_set("America/Mexico_City");
$inicio= 8; # Desde las ocho de la mañana.
$fin= 23; # Hasta las 21 horas de la tarde.
 
$HoraActual = intval(date("H"));// Hora actual del Pais de residencia.
if ($HoraActual >= $inicio && $HoraActual < $fin) {
  # Aquí mostrara el acceso permitido al sistema
  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('location: vistas/asistencia.php');
	exit;
} else {
    # Mostrar mensaje de sistema bloqueado, etc.
   echo "<script type=\"text/javascript\">alert(\"No podemos continuar, consulte con su administrador\");window.location = 'https://www.nextcam.com.mx/';</script>";
}
?>
