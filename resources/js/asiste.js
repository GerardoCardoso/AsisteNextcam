$(document).ready(function(){
    $('input[name="codigopersona"]').val('');
});

$('#asiste-formulario').submit(function(event){
    event.preventDefault();
    registrar_asistencia();

    $('button[type="submit"]').attr('disabled', false);
    $('input[name="codigopersona"]').attr('disabled', false);
    $('input[name="codigopersona"]').val('');

    $('#asiste-usuario').css('display', 'none');
    $('#asiste-usuario #text').text('');
});

function registrar_asistencia(){
    var serverSelf= window.location.href;

    const codigoPersona= $('input[name="codigopersona"]').val();
    $('button[type="submit"]').attr('disabled', true);
    $('input[name="codigopersona"]').attr('disabled', true);
  
     $.ajax({
     	url: serverSelf+"controller/generarAsistencia.php",
     	type: 'POST',
     	data: {codigo_persona: codigoPersona}
     }).done(function(response){
        try{
            response= JSON.parse(response);
        }catch(exception){}

        mostrarAlerta(response);
     });
}

function mostrarAlerta(res){
  // Si la solicitud se proceso con éxito y el usuario existe.
  if(res.success){
    if(res.tipoMov != ""){
        new Noty({
            type: (res.tipoMov=="entrada")?'success':'warning',
            theme: 'sunset',
            layout: 'topRight',
            text:   `<b class="text-dark text-custom">${res.user.nombre} ${res.user.appaterno} ${res.user.amaterno}</b> <br/>
                     <i class="fa fa-check text-dark text-custom"></i>
                     <span class="text-dark text-custom">${(res.tipoMov=="entrada")?'Entrada':'Salida'} Registrada</span> <br/>
                     <i class="fa fa-clock-o text-dark text-custom"></i>
                     <span class="text-dark text-custom">${res.movHora}</span>
                    `,
            progressBar: true,
            timeout: 3000
        }).show();
    }else{
        new Noty({
            type: 'error',
            theme: 'sunset',
            layout: 'topRight',
            text:   `<i class="fa fa-exclamation-circle text-dark text-custom"></i>
                     <span class="text-dark text-custom">No se pudo registrar la ${res.tipoMov}</span> <br/>                     
                    `,
            progressBar: true,
            timeout: 3000
        }).show();
    }  
  }else{
    // No se encontro el usuario en el sistema.
    new Noty({
        type: 'error',
        theme: 'sunset',
        layout: 'topRight',
        text:   `<i class="icon fa fa-warning text-dark text-custom"></i>
                 <span class="text-dark text-custom">${res.message}</span> <br/>                     
                `,
        progressBar: true,
        timeout: 3000
    }).show();
  }

  return;
}