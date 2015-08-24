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
        ZCAccionNuevoRegistro(e);
    });
    // Accion boton zc-modificar-registro permite modificar el registro
    $('.zc-editar-registro').click(function(e){
        ZCAccionModificarRegistro(e, this);
    });
    {_procesoBarraProgreso_}
    // Habilita la validacion del formulario
    $('#{_idFormulario_} .zc-accion').click(function (e) {
        e.preventDefault();
        $('.parsley-errors-list').show();
        // Tipo de accion que tiene el boton
        var nombreAccion = ($(this).attr('zc-accion-tipo'));
        // Selecciona la accion dependiendo el boton seleccionado
        {_llamadosAjax_}
    });
    // Inicializa los cajas de texto para las fechas
    $('.zc-caja-fecha').datetimepicker({
        format: 'YYYY-MM-DD',
        toolbarPlacement: 'top',
        showClear: true
    });
    // Inicializa los cajas de texto para las fecha hora
    $('.zc-caja-fecha-hora').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        toolbarPlacement: 'top',
        sideBySide: true,
        showClear: true
    });
    // Inicializa los cajas de texto para las hora
    $('.zc-caja-hora').datetimepicker({
        format: 'HH:mm:ss',
        toolbarPlacement: 'top',
        showClear: true
    });
    {_navegacion_}
    // Botones a mostrar
    ZCAccionBotones('{_idFormulario_}', '{_accionAgregar_}', '{_accionModificar_}', '{_accionBorrar_}', '{_accionPrecargar_}');
    // Busqueda predefinida, se deja al final cuando ya se ha cargado todo
    ZCAccionBuscarPredefinido('{_idFormulario_}');
    // Inicializa las restricciones de los campos del formulario {_idFormulario_}
    init('{_accionInit_}', '{_idFormulario_}');
});