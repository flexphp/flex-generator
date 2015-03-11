/**
 * Cada uno de las acciones definidas por el cliente en {_nombreFormulario_}
 */
$(document).ready(function () {
    //Campos que se validan del formulario
    var formasValidar = $('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset], select[class=zc-filtros-busqueda]');
    //Agrega descripciones de ayuda a las cajas de texto
    $('body').tooltip({ selector: '[data-toggle=tooltip]' });
    // Oculta ventana con mensajes de error
    $('.alert').hide();
    // Accion del boton limpiar
    $(':input[type=reset]').click(function(e){
        e.preventDefault();
        $('.parsley-errors-list').hide();
        $('#error-{_nombreFormulario_}').text('');
        $('.alert').hide();
        $('#{_nombreFormulario_}').trigger('reset');
    });

    // Inicializa los cajas de texto para las fechas
    $('.zc-caja-fecha').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    // Inicializa los filtros de busqueda
    $('.zc-filtros-busqueda').change(function(e){
        ZCCamposDeBusqueda(e, this);
    });

    // Accion boton cancelar
    $('.zc-boton-cancelar').click(function(e){
        if(ZCAccionCancelar(e, '{_nombreFormulario_}', formasValidar)){
            if(confirm('No se guardaran los cambios, continuar?')){
                history.back();
            }
        }else{
            history.back();
        }
    });

    // Accion boton zc-filtros-agregar para filtros de busqueda
    $('.zc-filtros-agregar').click(function(e){
        ZCAccionAgregarFiltro(e, '{_nombreFormulario_}', this);
    });

    // Accion boton zc-filtros-quitar para filtros de busqueda
    $('.zc-filtros-quitar').click(function(e){
        ZCAccionQuitarFiltro(e, this);
    });

    // Se agrega la validacion cuando los elementos pierden el foco
    $('#{_nombreFormulario_}').find($(formasValidar)).focusout(function (e) {
        // Manejo de la barra de progreso
        ZCBarraProgreso('{_nombreFormulario_}', formasValidar);
    });

    // Habilita la validacion del formulario
    $('#{_nombreFormulario_} .btn').not(':input[type=reset], .zc-boton-cancelar, .zc-filtros-agregar, .zc-filtros-quitar').click(function () {
        $('.parsley-errors-list').show();
        if($('#{_nombreFormulario_}').parsley().validate()){
            // Accion seleccionada por el usuario
            var nombreAccion = ($(this).attr('id'));
            // Selecciona la accion dependiendo el boton seleccionado
            {_accionesCliente_}
        }
    });
});