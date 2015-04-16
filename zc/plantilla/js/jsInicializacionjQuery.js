/**
 * Cada uno de las acciones definidas por el cliente en {_idFormulario_}
 */
$(document).ready(function () {
    //Campos que se validan del formulario
    var formasValidar = $('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset], select[class=zc-filtros-busqueda], #zc-filtros-cantidad-filtros');
    //Agrega descripciones de ayuda a las cajas de texto
    $('body').tooltip({ selector: '[data-toggle=tooltip]' });
    // Oculta ventana con mensajes de error
    $('.alert').hide();
    // Accion del boton limpiar
    $(':input[type=reset]').click(function(e){
        ZCAccionReiniciarFormulario(e, '{_idFormulario_}');
    });

    // Inicializa los cajas de texto para las fechas
    $('.zc-caja-fecha').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    // Inicializa los filtros de busqueda
    $('.zc-filtros-busqueda').change(function(e){
        ZCCamposDeBusqueda(e, '{_idFormulario_}', this);
    });

    // Accion boton cancelar
    $('.zc-boton-cancelar').click(function(e){
        if(ZCAccionCancelar(e, '{_idFormulario_}', formasValidar)){
            if(confirm('No se guardaran los cambios, desea continuar?')){
                history.back();
            }
        }else{
            history.back();
        }
    });

    // Accion boton zc-filtros-agregar para filtros de busqueda
    $('.zc-filtros-agregar').click(function(e){
        ZCAccionAgregarFiltro(e, '{_idFormulario_}', this);
    });

    // Accion boton zc-filtros-quitar para filtros de busqueda
    $('.zc-filtros-quitar').click(function(e){
        ZCAccionQuitarFiltro(e, this);
    });

    // Accion boton zc-filtros-ocultar para ocultar los filtros seleccionados
    $('.zc-filtros-ocultar').click(function(e){
        ZCAccionOcultarFiltro(e, '{_idFormulario_}', this);
    });

    // Accion boton zc-filtros-mostrar para mostrar los filtros seleccionados
    $('.zc-filtros-mostrar').click(function(e){
        ZCAccionMostrarFiltro(e, '{_idFormulario_}', this);
    });

    // Accion boton zc-nuevo-registro permite agregar un nuevo registro
    $('.zc-nuevo-registro').click(function(e){
        ZCAccionNuevoRegistro(e, '{_nombreControlador_}', this);
    });

    // Accion boton zc-modificar-registro permite modificar el registro
    $('.zc-editar-registro').click(function(e){
        ZCAccionModificarRegistro(e, '{_nombreControlador_}', this);
    });

    // Se agrega la validacion cuando los elementos pierden el foco
    $('#{_idFormulario_}').find($(formasValidar)).focusout(function (e) {
        // Manejo de la barra de progreso
        ZCBarraProgreso('{_idFormulario_}', formasValidar);
    });

    // Habilita la validacion del formulario
    $('#{_idFormulario_} .zc-accion').click(function (e) {
        e.preventDefault();
        $('.parsley-errors-list').show();
        var nombreAccion = ($(this).attr('zc-accion-tipo'));
        if($('#{_idFormulario_}').parsley().validate()){
            // Accion seleccionada por el usuario
            // Selecciona la accion dependiendo el boton seleccionado
            {_llamadosAjax_}
        }
    });

    // Busqueda predefinida, se deja al final cuando ya se ha cargado todo
    ZCAccionBuscarPredefinido('{_idFormulario_}');
    // Botones a mostrar
    ZCAccionBotones('{_idFormulario_}', '{_accionAgregar_}', '{_accionModificar_}', '{_accionBorrar_}', '{_accionPrecargar_}');
    // Menu actual
    ZCMenuActual('{_nombreControlador_}');
});

function ZCAccionPrecargar(formulario, id, precargar, modificar){
    $.ajax({
        url: $('#URLProyecto').val()+'index.php/{_nombreControlador_}/' + precargar + '/',
        type: 'POST',
        dataType: 'JSON',
        data: {
            // Envia filtros de busqueda al servidor
            id: id,
            accion: precargar
        },
        beforeSend: function(){
            // Desactiva todos los campos
            $('input, textarea, select, button').addClass('disabled').prop('disabled', true);
            // Oculta ventana con mensajes
            $('.alert').hide();
            // Mostrar cargando
            $('#'+modificar+' span').addClass('glyphicon-refresh glyphicon-refresh-animate');
        },
        success: function(rpta){
            if(rpta.error !== undefined && '' !== rpta.error){
                // Muestra mensaje de error
                $('#error-{_idFormulario_}').text(rpta.error);
                $('.alert-danger').show();
            }else{
                ZCAccionPrecargarResultado('{_idFormulario_}', rpta);
            }
        },
        complete: function(){
            // Activar los campos para la modificacion
            $('input, textarea, select, button').removeClass('disabled').prop('disabled', false);
            // Ocultar cargando
            $('#'+modificar+' span').removeClass('glyphicon-refresh glyphicon-refresh-animate');
        },
        error: function(rpta){
            $('#error-{_idFormulario_}').text('Error en el servicio');
            $('.alert-danger').show();
        }
    });
}
