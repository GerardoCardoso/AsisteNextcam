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

    function procesoRespaldo($yesterdayDate, $conexion, $remoteConexion){
        date_default_timezone_set('America/Mexico_City');
        $horaActual= date("H");

        if(intval($horaActual) > 8 && intval($horaActual) <= 23){
            $remoteQuery= "SELECT * FROM asistencia WHERE fecha='$yesterdayDate' ORDER BY idasistencia DESC";
            
            $result= mysqli_query($remoteConexion, $remoteQuery);
            $countRows= intval(mysqli_num_rows($result));
            
            // Si no hay registros del dia anterior en la bd remota. Transferir todos los registros..
            if($countRows < 1){
                echo "<script>console.log('3. No se encontraron asistencias en la base de datos (remota) del $yesterdayDate. Se elaborará a continuación un respaldo.')</script>";
                // Realizar una consulta con todas las asistencias del dia anterior en la bd (local).
                $localQuery= "SELECT * FROM asistencia WHERE fecha='$yesterdayDate'";
                $result2= mysqli_query($conexion, $localQuery);
                
                // Si hay registros en la base de datos (local)..
                if(intval(mysqli_num_rows($result2)) > 0){
                    echo "<script>console.log('4. Se encontraron asistencias en la base de datos (local) del día $yesterdayDate. Se iniciará el proceso de respaldo.')</script>";
                    $insertedRows=0;
                    $allRight= true;

                    while($row= mysqli_fetch_array($result2)){
                        $remoteQuery= "INSERT INTO asistencia (codigo_persona, fecha_hora, tipo, fecha) VALUES ('$row[codigo_persona]','$row[fecha_hora]','$row[tipo]','$row[fecha]')";
                        if(mysqli_query($remoteConexion, $remoteQuery)){
                            $allRight= true;
                        }else{
                            $allRight= false;
                            break;
                        }

                        $insertedRows++;
                    }

                    if(!$allRight){
                        $text= "Hubo un error al transferir una asistencia local a la base de datos remota.. query => ".$remoteQuery." mysql_error => ".mysqli_error($remoteConexion);
                        echo "<script>console.warn('3. $text')</script>";
                    }else{
                        if($insertedRows > 0){
                            echo "<script>console.log('5. Se elaboró un respaldo de asistencias del $yesterdayDate exitosamente.')</script>";
                        }
                        if($insertedRows == 0){
                            echo "<script>console.log('5. No hubo asistencias que respaldar del $yesterdayDate.')</script>";
                        }
                    }
                }else{
                    echo "<script>console.warn('4. No hay asistencias en la base de datos (local) del día $yesterdayDate. Por lo tanto no se pudo realizar un proceso de respaldo.')</script>";
                }
            }else{
                echo "<script>console.log('3. Ya se encuentran respaldadas las asistencias del $yesterdayDate en la base de datos (remota).')</script>";
            }
            
            mysqli_close($conexion);
            mysqli_close($remoteConexion);
        }
    }

    function respaldarAsistencias(){
        date_default_timezone_set('America/Mexico_City');
        global $localHost;

        if(strtolower($localHost) == "localhost" || $localHost == "127.0.0.1"){
            global $conexion;
            global $localDB;

            if($conexion->ping()){
                echo "<script>console.log('1. Se estableció conexión con la base de datos (local). $localDB')</script>";
                
                /**
                 *  Variables Remotas: Base de Datos
                 */
                
                $remoteHost= "31.220.104.219";
                $remoteDB= "u762310939_nextcam";
                $remoteUser= "u762310939_gerardo";
                $remotePass= "PaP3L1T05";

                $remoteConexion= new mysqli($remoteHost, $remoteUser, $remotePass, $remoteDB);

                if($remoteConexion->ping()){
                    echo "<script>console.log('2. Se estableció conexión con la base de datos (remota). $remoteDB')</script>";
                    // Pregunto si el dia actual es lunes
                    if(mb_strtolower(date("l")) == "monday"){
                        // Hacer el respaldo del sábado
                        $yesterdayDate = date('Y-m-d',strtotime("-2 days"));
                        procesoRespaldo($yesterdayDate, $conexion, $remoteConexion);
                    }else{
                        // Hacer el respaldo del día anterior
                        $yesterdayDate = date('Y-m-d',strtotime("-1 days"));
                        procesoRespaldo($yesterdayDate, $conexion, $remoteConexion);
                    }
                }else{
                    echo "<script>console.warn('No se pudo establecer conexion con la base de datos remota. $remoteDB')</script>";
                    exit;
                }
            }else{
                echo "<script>console.warn('No se pudo establecer conexion con la base de datos local. $localDB')</script>";
                exit;
            }
        }else{            
            echo "<script>console.warn('No se puede crear un respaldo de asistencias debido a que el host principal no es local.')</script>";
            exit;
        }
    }
?>