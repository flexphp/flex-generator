/**
 * Ejecucion del login
 */
$(document).ready(function () {
    //Campos que se validan del formulario
    var formasValidar = $('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset]');
    //Agrega descripciones de ayuda a las cajas de texto
    $('body').tooltip({ selector: '[data-toggle=tooltip]' });
    // Oculta ventana con mensajes de error
    $('.alert').hide();
    // Accion del boton limpiar
    $(':input[type=reset]').click(function(e){
        ZCAccionReiniciarFormulario(e, '{_idFormulario_}');
    });

    // Habilita la validacion del formulario
    $('#{_idFormulario_} .zc-accion').click(function (e) {
        e.preventDefault();
        $('.parsley-errors-list').show();
        // Tipo de accion que tiene el boton
        var nombreAccion = ($(this).attr('zc-accion-tipo'));
        // Selecciona la accion dependiendo el boton seleccionado
        {_llamadosAjax_}
    });
    // Activar botones de acciones
    ZCActivarBotonPrincipal('{_idFormulario_}');
});