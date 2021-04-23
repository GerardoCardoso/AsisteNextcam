<?php 
require_once "../modelos/Asistencia.php";

$asistencia= new Asistencia();

$codigo_persona= isset($_POST["codigo_persona"]) ? limpiarCadena($_POST["codigo_persona"]) : "";
$iddepartamento= isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";

switch ($_GET["op"]) {
	case 'registrar_asistencia':
    $result=$asistencia->verificarcodigo_persona($codigo_persona);

    if($result > 0) {
      date_default_timezone_set('America/Mexico_City');
      $fecha= date("Y-m-d");
      $hora= date("H:i:s");

      $result2=$asistencia->seleccionarcodigo_persona($codigo_persona);
      $result3= mysqli_fetch_array($result2);

      // Validar si en la base de datos ya se registro una entrada
      $par = $result3[0];

      if (strtolower($par) == 'salida'){                 
          $tipo= "Entrada";
          $rspta= $asistencia->registrar_entrada($codigo_persona,$tipo);

        echo $rspta ? '<h1 class="entrada"><strong>Nombre: </strong> '. $result['nombre'].' '.$result['appaterno'].'</h1><div class="alert alert-success entrada"> Ingreso registrado '.$hora.'</div>' : 'No se pudo registrar el ingreso';
      }else{ 
          $tipo= "Salida";
          $rspta= $asistencia->registrar_salida($codigo_persona,$tipo);

        echo $rspta ? '<h1 class="salida"><strong>Nombre: </strong> '. $result['nombre'].' '.$result['appaterno'].'</h1><div class="alert alert-warning salida"> Salida registrada '.$hora.'</div>' : 'No se pudo registrar la salida';             
      } 
    }else{
         echo '<div class="alert alert-danger">
                   <i class="icon fa fa-warning"></i> No hay empleado registrado con esa código, Llama al administrador
               </div>';
    }
	break;
}
?>