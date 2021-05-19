<?php 
    require_once "../model/conexion.php";
    require_once "../model/asistencia.php";

    $asistencia= new Asistencia();

    if(isset($_POST['codigo_persona']) && !empty($_POST['codigo_persona'])){
        date_default_timezone_set('America/Mexico_City');
        $fecha= date("Y-m-d");
        $hora= date("H:i:s");
        $horaActual= intval(date("H"));
        
        $codigo_persona= limpiarCadena($_POST["codigo_persona"]);
        $result= $asistencia->verificarcodigo_persona($codigo_persona);
        //Estructura de salida
            $output= array(
            "success" => false,
            "user" => [
                "nombre" => "",
                "appaterno" => "",
                "amaterno" => ""
            ],
            "movHora" => "",
            "tipoMov" => "",
            "message" => "No hay empleado registrado con este código, Llama al administrador."
            );

        if($result > 0){
            // La solicitud se procesó y el usuario existe.
            $output["success"]= true;

            $result2= $asistencia->seleccionarcodigo_persona($codigo_persona);
            $result3= mysqli_fetch_array($result2);

            // Obtener de la base de datos la ultima entrada registrada del usuario.
            $tipo= (isset($result3[0]) && !empty($result3[0])) ? $result3[0] : "";

            if(empty($tipo) || strtolower($tipo) == 'salida'){
                // Validar si aun se puede registrar la entrada.
                if($horaActual > 7 && $horaActual < 13){
                    if($asistencia->registrarMovimiento($codigo_persona,"Entrada",$fecha,$hora)){
                        $output["user"]["nombre"]= $result['nombre'];
                        $output["user"]["appaterno"]= $result['appaterno'];
                        $output["user"]["amaterno"]= $result['amaterno'];
                        $output["movHora"]= $hora;
                        $output["tipoMov"]= "entrada";
                    }else{
                        // No se pudo registrar el ingreso.
                        $output["success"]= false;
                        $output["tipoMov"]= "entrada";
                    }
                }else{
                    $output["success"]= false;
                    $output["tipoMov"]= "entrada";
                    $output["message"]= "El tiempo de registro de entrada no esta disponible.";
                }
            }else{
                // Si se va a registrar la salida, verificar que 
                if($horaActual >= 13){
                    // Validar si aun se puede registrar la salida.
                    if($asistencia->registrarMovimiento($codigo_persona,"Salida",$fecha,$hora)){
                        $output["user"]["nombre"]= $result['nombre'];
                        $output["user"]["appaterno"]= $result['appaterno'];
                        $output["user"]["amaterno"]= $result['amaterno'];
                        $output["movHora"]= $hora;
                        $output["tipoMov"]= "salida";
                    }else{
                        // No se pudo registrar la salida.
                        $output["success"]= false;
                        $output["tipoMov"]= "salida";
                    }
                }else{
                    $output["success"]= false;
                    $output["tipoMov"]= "salida";
                    $output["message"]= "El tiempo de registro de salida no esta disponible.";
                }
            }
        }
        
        print json_encode($output);
    }
?>