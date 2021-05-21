<?php
    $finalResponse= array(
        "success" => false,
        "mensajeFinal" => "No ingreso una fecha de referencia."
    );

    if(isset($_POST['fechaReferencia']) && !empty($_POST['fechaReferencia'])){
        require "../model/conexion.php";        
        print json_encode(respaldarAsistenciasFR($_POST['fechaReferencia']));
    }else{
        $finalResponse['message']= "No ingreso una fecha de referencia.";
        print json_encode($finalResponse);
    }
?>