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

    function respaldarAsistenciasFR($fechaReferencia){
        date_default_timezone_set('America/Mexico_City');
        global $localHost;
        $functionResponse= array(
            "success" => false,
            "mensajeFinal" => "",
            "procedimientos" => []
        );

        if(strtolower($localHost) == "localhost" || $localHost == "127.0.0.1"){
            global $conexion;
            global $localDB;

            if($conexion->ping()){
                $functionResponse['procedimientos'][]= "Se estableció conexión con la base de datos (local). $localDB";
                
                /**
                 *  Variables Remotas: Base de Datos
                 */
                
                $remoteHost= "31.220.104.219";
                $remoteDB= "u762310939_nextcam";
                $remoteUser= "u762310939_gerardo";
                $remotePass= "PaP3L1T05";

                $remoteConexion= new mysqli($remoteHost, $remoteUser, $remotePass, $remoteDB);

                if($remoteConexion->ping()){
                    $functionResponse['procedimientos'][]= "Se estableció conexión con la base de datos (remota). $remoteDB";
                    
                    /**
                     *  Hacer el respaldo de asistencia por la fecha de referencia.
                     */
                    
                    /**
                     * 1. Buscar si hay asistencias (select) por la fecha de referencia en la base de datos (local).
                     * 2. Si las hay, hacer un delete from asistencia where fecha='' en la base de datos (remota).
                     * 3. Recorrer las filas obtenidas de la base de datos (local) e irlas insertando en (remota).
                     * 4. Fin
                     */

                    $queryLocal= "SELECT * FROM asistencia WHERE fecha='$fechaReferencia'";
                    $responseLocal= mysqli_query($conexion, $queryLocal);

                    if(intval(mysqli_num_rows($responseLocal)) > 0){
                        $functionResponse['procedimientos'][]= "Se encontraron asistencias en la base de datos (local) del $fechaReferencia. Se realizara el proceso de respaldo.";
                        
                        $queryRemoto= "DELETE FROM asistencia WHERE fecha='$fechaReferencia'";
                        $responseRemoto= mysqli_query($remoteConexion, $queryRemoto);
                        
                        $flag= false;
                        $asistenciaRegistrada= 0;

                        if($responseRemoto){
                            $functionResponse['procedimientos'][]= "Se realizo una depuración de asistencias del $fechaReferencia en la base de datos (remota).";
                            
                            $functionResponse['procedimientos'][]= "Se comenzo el proceso de respaldo de asistencias del $fechaReferencia.";
                            // Insertar las asistencias del local al remoto.
                            while($localRow= mysqli_fetch_array($responseLocal)){
                                $remoteQuery= "INSERT INTO asistencia (codigo_persona, fecha_hora, tipo, fecha) 
                                               VALUES ('$localRow[codigo_persona]','$localRow[fecha_hora]','$localRow[tipo]','$localRow[fecha]')";
                                               
                                if(mysqli_query($remoteConexion, $remoteQuery)){
                                    $flag= true;
                                }else{
                                    $flag= false;
                                    break;
                                }

                                $asistenciaRegistrada++;
                            }

                            $functionResponse['procedimientos'][]= "Termino el proceso de respaldo de asistencias del $fechaReferencia.";

                            // Si todas las asistencias fueron registradas sin problemas.
                            if($flag){
                                $functionResponse['success']= true;
                                $functionResponse['mensajeFinal']= "El respaldo de asistencias del $fechaReferencia se realizó con éxito.";
                                $functionResponse['procedimientos'][]= $functionResponse['mensajeFinal'];
                                $functionResponse['procedimientos'][]= "Se respaldaron en total $asistenciaRegistrada asistencias en la base de datos (remota).";

                            }else{
                                $functionResponse['mensajeFinal']= "El respaldo de asistencias del $fechaReferencia no concluyó con éxito.";
                                $functionResponse['procedimientos'][]= $functionResponse['mensajeFinal'];
                            }
                        }else{
                            $functionResponse['procedimientos'][]= "Fallo al ejecutar el query \"$queryRemoto\" en la base de datos (remota). Causa => ".mysqli_error($remoteConexion);
                        }
                    }else{
                        $functionResponse['procedimientos'][]= "No se encontraron asistencias en la base de datos (local) del $fechaReferencia.";                        
                        $functionResponse['mensajeFinal']= $functionResponse['procedimientos'][count($functionResponse['procedimientos'])-1];
                    }
                    
                }else{
                    $functionResponse['procedimientos'][]= "No se pudo establecer conexion con la base de datos remota. $remoteDB";
                    $functionResponse['mensajeFinal']= $functionResponse['procedimientos'][count($functionResponse['procedimientos'])-1];
                }
            }else{
                $functionResponse['procedimientos'][]= "No se pudo establecer conexion con la base de datos local. $localDB";
                $functionResponse['mensajeFinal']= $functionResponse['procedimientos'][count($functionResponse['procedimientos'])-1];
            }
        }else{            
            $functionResponse['procedimientos'][]= "No se puede crear un respaldo de asistencias debido a que el host principal no es local.";
            $functionResponse['mensajeFinal']= $functionResponse['procedimientos'][count($functionResponse['procedimientos'])-1];
        }

        return $functionResponse;
    }
?>