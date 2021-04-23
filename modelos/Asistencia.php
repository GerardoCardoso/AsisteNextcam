<?php 
//incluir la conexion de base de datos
require "../admin/config/Conexion.php";

class Asistencia{
	//implementamos nuestro constructor
  public function __construct(){}

  public function verificarcodigo_persona($codigo_persona){
    $sql = "SELECT * FROM usuarios WHERE codigo_persona='$codigo_persona'";
    return ejecutarConsultaSimpleFila($sql);
  }

  public function seleccionarcodigo_persona($codigo_persona){
      $sql = "SELECT tipo FROM asistencia WHERE codigo_persona = '$codigo_persona' ORDER BY fecha_hora DESC LIMIT 1";
    return ejecutarConsulta($sql);
  }

  public function registrar_entrada($codigo_persona,$tipo){
    date_default_timezone_set('America/Mexico_City');
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $fech = $fecha.' '.$hora;
      $sql = "INSERT INTO asistencia (codigo_persona, fecha_hora, tipo, fecha) VALUES ('$codigo_persona', '$fech', '$tipo', '$fecha')";
    return ejecutarConsulta($sql);
  }

  public function registrar_salida($codigo_persona,$tipo){
    date_default_timezone_set('America/Mexico_City');
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $fech = $fecha.' '.$hora;
    $sql = "INSERT INTO asistencia (codigo_persona, fecha_hora, tipo, fecha) VALUES ('$codigo_persona', '$fech', '$tipo', '$fecha')";
      return ejecutarConsulta($sql);
  }



  //listar registros
  public function listar(){
    $sql="SELECT * FROM asistencia";
    return ejecutarConsulta($sql);
  }
}
?>