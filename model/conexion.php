<?php
    /**
     *  Variables Locales: Base de Datos
     */
    
    $localHost= "localhost";
    $localDB= "u762310939_nextcam";
    $localUser= "root";
    $localPass= "";
    
    $conexion= new mysqli($localHost, $localUser, $localPass, $localDB);

    function ejecutarConsulta($query){
        global $conexion;
        $result= $conexion->query($query);

        return $result;
    } 

    function ejecutarConsultaSimpleFila($query){
        global $conexion;
        $result= $conexion->query($query);
        $row= $result->fetch_assoc();

        return $row;
    }
    
    function ejecutarConsulta_retornarID($query){
        global $conexion;
        $conexion->query($query);

        return $conexion->insert_id;
    }

    function limpiarCadena($str){
        global $conexion;
        $str= mysqli_real_escape_string($conexion,trim($str));

        return htmlspecialchars($str);
    }

    function setUTF8Encode($conexion= null){
        global $conexion;
        mysqli_query($conexion, "SET NAMES utf8");
    }

    function respaldarAsistencias(){
        global $localHost;

        if(strtolower($localHost) == "localhost" || $localHost == "127.0.0.1"){
            global $conexion;

            if($conexion != null || !empty($conexion)){
                date_default_timezone_set('America/Mexico_City');
                $horaActual= date("H");
                
                /**
                 *  Variables Remotas: Base de Datos
                 */
                
                $remoteHost= "31.220.104.219";
                $remoteDB= "u762310939_nextcam";
                $remoteUser= "u762310939_gerardo";
                $remotePass= "PaP3L1T05";

                $remoteConexion= new mysqli($remoteHost, $remoteUser, $remotePass, $remoteDB);

                //$remoteConexion= $conexion;
                if($remoteConexion != null || !empty($remoteConexion)){

                    // Pregunto si el dia actual es lunes
                    if(strtolower(date("l")) == "monday"){
                        $yesterdayDate = date('Y-m-d',strtotime("-2 days"));

                        if(intval($horaActual) > 8 && intval($horaActual) <= 23){
                            $remoteQuery= "SELECT COUNT(*) AS SI_HAY FROM asistencia WHERE fecha='$yesterdayDate' ORDER BY idasistencia DESC";
                            
                            $result= mysqli_query($remoteConexion, $remoteQuery);
                            $row= mysqli_fetch_array($result);
                            
                            // Si no hay registros del dia anterior en la bd remota. Transferir todos los registros..
                            if(intval($row['SI_HAY']) == 0){
                                // Realizar una consulta con todas las asistencias del dia anterior en la bd local.
                                $localQuery= "SELECT * FROM asistencia WHERE fecha='$yesterdayDate'";
                                $result2= mysqli_query($conexion, $localQuery);
                                
                                // Si hay registros en la base de datos local..
                                if(!empty($localRows)){
                                    $allRight= true;
                                    while($lr= mysqli_fetch_array($result2)){
                                        $remoteQuery= "INSERT INTO asistencia (codigo_persona, fecha_hora, tipo, fecha) VALUES ('$lr[codigo_persona]','$lr[fecha_hora]','$lr[tipo]','$lr[fecha]')";
                                        if(!(mysqli_query($remoteConexion, $remoteQuery))){
                                            $allRight= false;
                                            break;
                                        }
                                    }
    
                                    if(!$allRight){
                                        die("Hubo un error al transferir una asistencia local a la base de datos remota.. query => ".$remoteQuery." mysql_error => ".mysqli_error($remoteConexion));
                                    }
                                }else{
                                    exit;
                                }
                            }
                            
                            mysqli_close($remoteConexion);
                        }
                    }

                    $yesterdayDate = date('Y-m-d',strtotime("-1 days"));

                    if(intval($horaActual) > 8 && intval($horaActual) <= 23){
                        $remoteQuery= "SELECT COUNT(*) AS SI_HAY FROM asistencia WHERE fecha='$yesterdayDate' ORDER BY idasistencia DESC";
                        
                        $result= mysqli_query($remoteConexion, $remoteQuery);
                        $row= mysqli_fetch_array($result);
                        
                        // Si no hay registros del dia anterior en la bd remota. Transferir todos los registros..
                        if(intval($row['SI_HAY']) == 0){
                            // Realizar una consulta con todas las asistencias del dia anterior en la bd local.
                            $localQuery= "SELECT * FROM asistencia WHERE fecha='$yesterdayDate'";
                            $result2= mysqli_query($conexion, $localQuery);
                            
                            // Si hay registros en la base de datos local..
                            if(!empty($localRows)){
                                $allRight= true;
                                while($lr= mysqli_fetch_array($result2)){
                                    $remoteQuery= "INSERT INTO asistencia (codigo_persona, fecha_hora, tipo, fecha) VALUES ('$lr[codigo_persona]','$lr[fecha_hora]','$lr[tipo]','$lr[fecha]')";
                                    if(!(mysqli_query($remoteConexion, $remoteQuery))){
                                        $allRight= false;
                                        break;
                                    }
                                }

                                if(!$allRight){
                                    die("Hubo un error al transferir una asistencia local a la base de datos remota.. query => ".$remoteQuery." mysql_error => ".mysqli_error($remoteConexion));
                                }
                            }else{
                                exit;
                            }
                        }
                        
                        mysqli_close($remoteConexion);
                    }
                }else{
                    die("No se pudo establecer conexion con la base de datos remota..");
                }
            }else{
                die("No se pudo establecer conexion con la base de datos local..");
            }
        }else{
            die("No se puede crear un resplado de asistencias debido a que el host principal no es local..");
        }
    }
?>