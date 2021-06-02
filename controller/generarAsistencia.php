<?php 
    require_once "../model/conexion.php";
    require_once "../model/asistencia.php";

    $asistencia= new Asistencia();

    if(isset($_POST['codigo_persona']) && !empty($_POST['codigo_persona'])){
        date_default_timezone_set('America/Mexico_City');
        $fecha= date("Y-m-d");
        $hora= date("H:i:s");
        $horaActual= intval(date("H"));
        $fechaActual= date("Y-m-d");
        
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
        
        // Aplicar excepción a becario u otro personal.
        $personalExcepcion= '000927018';
        $entradaLimite= 13;
        
        if($personalExcepcion == $codigo_persona){
            $entradaLimite= 16;
        }

        if($result > 0){
            // La solicitud se procesó y el usuario existe.
            $output["success"]= true;

            $result2= $asistencia->seleccionarcodigo_persona($codigo_persona);
            $result3= mysqli_fetch_array($result2);

            // Obtener de la base de datos la ultima entrada registrada del usuario.
            $tipo= (isset($result3[0]) && !empty($result3[0])) ? $result3[0] : "";

            if(empty($tipo) || strtolower($tipo) == 'salida'){
                // Validar si aun se puede registrar la entrada.
                if($horaActual > 7 && $horaActual < $entradaLimite){
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
                // Preguntar si la fecha de Entrada es del día de hoy
                if($result3['fecha'] == $fechaActual){
                    // Si se va a registrar la salida, verificar que la hora actual sea mayor o igual a la 1 de la tarde.
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
                }else{
                    // Registrar la salida con la fecha de ayer con la hora máxima admitida (11:00pm)
                    if($asistencia->registrarMovimiento($codigo_persona,"Salida",$result3['fecha'],"23:00:00")){
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
                }
            }
        }
        
        print json_encode($output);
    }
?>