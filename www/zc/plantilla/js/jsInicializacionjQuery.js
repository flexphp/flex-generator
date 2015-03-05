/**
 * Cada uno de las acciones definidas por el cliente en {_nombreFormulario_}
 */
$(document).ready(function () {
    //Agrega descripciones de ayuda a las cajas de texto
    $('body').tooltip({ selector: '[data-toggle=tooltip]' });
    
    //Campos que se validan del formulario
    var formasValidar = $('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset]');
    
    // Se agrega la validacion cuando los elementos pierden el foco
    $('#{_nombreFormulario_}').find($(formasValidar)).focusout(function (e) {
        // Manejo de la barra de progreso
        ZC_actualizarProgreso('{_nombreFormulario_}', formasValidar);
    });
    
    // Habilita la validacion del formulario
    $('#{_nombreFormulario_} .btn-default').click(function () {
        if($('#{_nombreFormulario_}').parsley().validate()){
            // Accion seleccionada por el usuario
            var nombreAccion = ($(this).attr('id'));
            // Selecciona la accion dependiendo el boton seleccionado
            {_accionesCliente_}
        }
    });
});