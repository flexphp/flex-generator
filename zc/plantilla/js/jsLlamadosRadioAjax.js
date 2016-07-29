/**
 * Carga mediante Ajax la lista desplegable {_nombreRadio_}
 */
$(document).ready(function () {
    $.ajax({
        // A la accion se le concatena la palabra cliente, asi se llama en la funcion
        url: '{_nombreControlador_}/ajax',
        type: 'POST',
        dataType: 'JSON',
        data: 'accion=ajax&tablas={_nombreTabla_}&campos={_nombreCampos_}',
        beforeSend: function(){
            // Agregar aqui el codigo
        },
        success: function(rpta){
            if(rpta.error === undefined){
                // Agrega las opciones al select
                ZCPrecargarRadio("{_nombreContenedor_}", "{_nombreRadio_}", rpta);
            }
        },
        complete: function(){
            // Agregar aqui el codigo
        },
        error: function(){
            console.log('Error en el servicio');
        }
    });
});
