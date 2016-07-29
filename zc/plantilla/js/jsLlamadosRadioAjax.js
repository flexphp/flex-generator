/**
 * Carga mediante Ajax la lista desplegable {_nombreRadio_}
 */
$(document).ready(function () {
    $.ajax({
        // A la accion se le concatena la palabra cliente, asi se llama en la funcion
        url: URLControlador + 'ajax',
        method: 'POST',
        dataType: 'JSON',
        data: 'accion=ajax&tablas={_nombreTabla_}&campos={_nombreCampos_}',
        beforeSend: function(){
            // Agregar aqui el codigo
        },
        success: function(rpta){
            if (ZCRespuestaConError(rpta)) {
                // Muestra mensaje de error
                ZCAsignarErrores(rpta); 
            } else {
                // Agrega las opciones al select
                ZCPrecargarRadio("{_nombreContenedor_}", "{_nombreRadio_}", rpta);
            }
        },
        complete: function(){
            // Agregar aqui el codigo
        },
        error: function(){
            ZCAsignarErrores('Error en el servicio');
        }
    });
});
