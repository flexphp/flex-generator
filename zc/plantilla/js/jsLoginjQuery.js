/**
 * Ejecucion del login
 */
$(document).ready(function () {
    // Agrega descripciones de ayuda a las cajas de texto
    $('body').tooltip({selector: '[data-toggle=tooltip]'});
    // Oculta ventana con mensajes de error
    $('.alert').hide();
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