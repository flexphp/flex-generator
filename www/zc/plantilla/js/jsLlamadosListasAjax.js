/**
 * Definicion de la accion {_nombreAccion_}
 */
$(document).ready(function () {
    $.ajax({
        // A la accion se le concatena la palabra cliente, asi se llama en la funcion
        url: $('#URLProyecto').val()+'index.php/{_nombreControlador_}/ajax',
        type: 'POST',
        dataType: 'JSON',
        data: 'accion=ajax&tablas={_nombreTabla_}&campos={_nombreCampos_}',
        beforeSend: function(){
            // Inactivar la lista desplegable mientras se cargan los valores
            $('#{_nombreSelect_}').addClass('disabled').prop('disabled', true);
        },
        success: function(rpta){
            if(rpta.error !== undefined && '' !== rpta.error){
                // Muestra mensaje de error
                console.log(rpta.error);
            }else{
                // Agrega las opciones al select
                ZCPrecargarSeleccion('{_nombreSelect_}', rpta);
            }
        },
        complete: function(){
            // Activar el boton cuando se completa la accion, con error o sin error
            $('#{_nombreSelect_}').removeClass('disabled').prop('disabled', false);
        },
        error: function(){
            console.log('Error en el servicio');
        }
    });
});
