
var timeout;

function previewProcess(event){
    var serverSelf= window.location.href;

    $('#asiste-usuario').css('display', 'block');
    $('#asiste-usuario #text').text('Buscando...');

    if($(event.currentTarget).val() == ""){
        $('#asiste-usuario').css('display', 'none');
        return;
    }else{
        clearTimeout(timeout);
        timeout= setTimeout(function(){
            var codigoPersona= $(event.currentTarget).val();
        
            // Lanzar un ajax solicitando el nombre completo del usuario.
            $.ajax({
                url: serverSelf+'controller/previewUsuario.php',
                type: 'POST',
                data: { codigo_persona: codigoPersona }
            }).done(function(response){
                try{
                    response= JSON.parse(response);
                    $('#asiste-usuario #text').text(response.nombre_completo);
                }catch(exception){}
            });
        }, 585);
    }
}

$('input[name="codigopersona"]').on('keydown', function(event){
    var keycode= event.originalEvent.keyCode;
    // 37 -> flecha izquierda | 39 -> flecha derecha
    if(keycode != 37 && keycode != 39){
        previewProcess(event);
    }
});

$('input[name="codigopersona"]').on('change', function(event){
    previewProcess(event);
});