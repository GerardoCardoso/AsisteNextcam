<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Asiste | Nextcam</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../admin/public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../admin/public/css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../admin/public/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../admin/public/css/blue.css">
    <link href="http://nextcam.com.mx/assets/img/favicon.png" rel="icon">
  </head>
<body class="hold-transition lockscreen">

<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
<?php 
 //include '../ajax/asistencia.php' ?>
    <div name="movimientos" id="movimientos">
    </div> 


  <div align="center">
  <img src="http://odin.nextcam.com.mx/dist/img/icon.png" width="15%" height="15%" align="center">
</div>
  <div class="lockscreen-logo">
    <a href="#"><b>Asiste</b> Nextcam</a>
  </div>
  <!-- User name -->

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image
    <div class="lockscreen-image">
      <img src="../admin/files/negocio/user.png" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form  action="" autocomplete="new-password" name="formulario" id="formulario" method="POST">
      <div class="input-group">
        <input type="text" class="form-control" name="codigo_persona" autofocus="true" id="codigo_persona" autocomplete="new-password" placeholder="ID de asistencia" required>

        <div class="input-group-btn">
          <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    Ingresa tu ID de asistencia
  </div>
  <div class="text-center">
<div style="text-align:center;padding:1em 0;"> <iframe src="https://www.zeitverschiebung.net/clock-widget-iframe-v2?language=es&size=medium&timezone=America%2FMexico_City" width="100%" height="115" frameborder="0" seamless></iframe> </div>
  </div>
</div>
<!-- /.center -->


    <!-- jQuery -->
    <script src="../admin/public/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../admin/public/js/bootstrap.min.js"></script>
     <!-- Bootbox -->
    <script src="../admin/public/js/bootbox.min.js"></script>

    <script type="text/javascript" src="scripts/asistencia.js"></script>
    <script language="javascript">
          const d = document;
          const $movimientos = d.querySelector("#movimientos");
          let $etiquetaEntrada;
          let $etiquetaSalida;
          const $button = d.querySelector("button[type='submit']");
          const oldBgColor = d.body.style.backgroundColor;
          
          function getOldBg(){
              while($movimientos.childNodes.length>=1){
                $movimientos.removeChild($movimientos.firstChild);
              }
              d.body.style.background = oldBgColor;              
          }
      
          const observer = new MutationObserver((mutationList)=>{
            mutationList.forEach((mutation)=>{
              $etiquetaEntrada=d.getElementsByClassName("entrada");
              $etiquetaSalida=d.getElementsByClassName("salida");
              if($etiquetaEntrada.length>0){
                   let currentDate = new Date();
                   let hour = currentDate.getHours();
                   let minutes = currentDate.getMinutes();
                   if(hour===6 || hour===7 || hour===8){
                      d.body.style.backgroundColor="green";
                      setTimeout(getOldBg,5000);
                   }else if(hour===9 && minutes<=9){
                      d.body.style.backgroundColor="yellow";
                      setTimeout(getOldBg,5000);
                   }else if((hour===9 && minutes>=10) || hour===10 || (hour===11 && minutes<=30)){
                      d.body.style.backgroundColor="red";
                      setTimeout(getOldBg,5000);
                   }else{
                      setTimeout(getOldBg,5000);
                   }
                   //To test
                   //if(hour===13 && (minutes>=2 && minutes<=59)){
                   //    d.body.style.backgroundColor="green";
                   //    setTimeout(getOldBg,5000);
                   //}
              }
              if($etiquetaSalida.length>=1){
                  setTimeout(getOldBg,2000);
              }
              //console.log(mutation.addedNodes);
              //console.log("Nodos agregados");
              //console.log(mutation.removedNodes.length);
              //console.log("Nodos eliminados");
            });
          });
      
          observer.observe($movimientos,{ 
               attributes: true, 
               childList: true, 
               subtree: true,
               characterData: false,
               attributeOldValue: false,
               characterDataOldValue: false
          });
    </script>

  </body>
</html> 
