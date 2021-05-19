<?php
    require_once "../model/conexion.php";
    require_once "../model/usuario.php";

    $usuario= new Usuario();

    if(isset($_POST['codigo_persona']) && !empty($_POST['codigo_persona'])){
        $codigoPersona= $_POST['codigo_persona'];

        $nombreCompleto= $usuario->nombrecompleto_usuario($codigoPersona);
        if(empty($nombreCompleto)){
            print json_encode([ "nombre_completo" => "Usuario no encontrado"]);
        }else{
            print json_encode($nombreCompleto);
        }
    }
?>