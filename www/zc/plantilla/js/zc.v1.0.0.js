/**
 * Actualiza la barra de progreso segun se vayan diligenciando los datos
 * @param string formulario Id del formulario al que se la va validar el progreso
 * @returns {Boolean}
 */
function ZCBarraProgreso(formulario, formasValidar){
    // Cantidad de campos encontrados en el formulario
    var contadorCampos = 0;
    // Cantidad de campos validos
    var contadorCamposValidos = 0;
    // Valida que los cambpos checkbox y radio no se cuenten mas de una vez
    var nombres = [];
    // Todos campos del formulario
    $('#'+formulario).find($(formasValidar)).each(function(){
        //Verifica que no se halla contado antes
        //Salta elementos que no tengan id definido
        if(nombres.indexOf(this.name) == -1 && this.id != ''){
            nombres.push(this.name);
            contadorCampos++;
            if ($('#'+this.id).parsley().isValid('#'+this.id)){
                contadorCamposValidos++;
            }
        }
    });
    // Calcula un numero entero por encima
    var progreso = Math.ceil((contadorCamposValidos*100)/contadorCampos);
    $('#progreso-'+formulario).css('width', progreso + '%').attr('aria-valuenow', progreso);
    $('#msj-progreso-'+formulario).html(progreso + '%');
    if(progreso === 100){
        // Deja clases de exito
        $('#progreso-'+formulario).addClass('progress-bar-success').removeClass('progress-bar-striped').removeClass('active');
    }else{
        // Deja clases iniciales
        $('#progreso-'+formulario).removeClass('progress-bar-success').addClass('progress-bar-striped').addClass('active');
    }
}

function ZCAccionReiniciarFormulario(e, formulario){
    e.preventDefault();
    $('.parsley-errors-list').hide();
    $('#error-'+formulario).text('');
    $('.alert').hide();
    $('#'+formulario).trigger('reset');
}

/**
 * Valida los campos durande la accion cancelar, si alguno tiene datos, muestra mensaje de advertencia
 * @param string formulario Id del formulario al que se la va validar el progreso
 * @returns {Boolean}
 */
function ZCAccionCancelar(formulario, formasValidar){
    // Todos campos del formulario
    var existenCamposDiligenciados = false;
    var valorCampo = '';
    var nombres = [];
    $('#'+formulario).find($(formasValidar)).each(function(){
        if(nombres.indexOf(this.name) == -1){
            nombres.push(this.name);
            switch(true){
                case $('#'+this.id).attr('type') == 'checkbox':
                    var marcados = 0;
                    if ($('#'+this.id).prop("checked")){
                        marcados++;
                    }
                    valorCampo = (marcados == 0) ? '' : 'Lleno';
                    break;
                case $('#'+this.id).attr('type') == 'radio':
                    valorCampo = $('input[name='+this.name+']:checked').val();
                    break;
                default:
                    valorCampo = $.trim($('#'+this.id).val());
                    break;
            }
            if (valorCampo != '' && valorCampo != undefined){
                existenCamposDiligenciados = true;
            }
        }
    });

    return existenCamposDiligenciados;
}

/**
 * Menejo de los filtros de busqueda
 * @param {e} Evento disparador
 * @param {type} id
 * @returns {Boolean}
 */
function ZCCamposDeBusqueda(e, id){
    e.preventDefault();
    if(!$(id).length){
        console.log('Elemento no encontrado');
        return false;
    }

    var filtro = $(id).val();
    // Oculta tod los posibles filtros
    $('.zc-filtros').addClass('hidden');
    // Muestra el filtro seleccionado de la lista
    $('.zc-filtros-'+filtro).removeClass('hidden');
    // Establece elvalor seleccionado del filtro
    $('.zc-filtros-busqueda').val(filtro);
    // Deja el valor nuevamente en vacio para una nueva asignacion
    $('#'+filtro).val('');
}

/**
 * Agrega un filtro de busqueda seleccionado por el usuario
 * @param {e} Evento disparador
 * @param {string} formulario
 * @param {$} id
 * @returns {undefined}
 */
function ZCAccionAgregarFiltro(e, formulario, id){
    e.preventDefault();
    var filtro = $(id).attr('id').replace('agregar-', '');
    var operador = $('#operador-' + filtro).val();
    var valor = $('#' + filtro).val();

    if (!$('#'+formulario).parsley().validate()){
        console.log('Campo no valido');
        return false;
    }

    var textoFiltro = $('.zc-filtros-busqueda:first option:selected').html();
    var textoOperador = $('#operador-' + filtro + " option:selected").html();
    var textoValor = (valor != '') ? valor : '<i>(vacio)</i>';

    // Evita eliminar filtros repetidos, se maneja como numero
    var cantidadFiltros = parseInt($('#zc-filtros-cantidad-filtros').val());
    var identificadorFiltro = filtro+'-'+cantidadFiltros;
    // Suma al numero de filtros, nunca se repite el id, asi no importa el orden en el que son eliminados
    $('#zc-filtros-cantidad-filtros').val((cantidadFiltros+1));

    // Agrega un campo oculto al formaulario con los valores a enviar
    $('#' + formulario).append("<div class='row zc-filtros-disponibles' id='zc-filtros-aplicados-"+identificadorFiltro+"'>"+
    "<div class='col-md-1'></div>"+
    // Etiqueta del campo
    "<div class='col-md-2 text-center'>"+textoFiltro+"</div>" +
    // Operador
    "<div class='col-md-2 text-center'>"+textoOperador+"</div>" +
    // Valor
    "<div class='col-md-2 text-center'>"+textoValor+"</div>" +
    // Boton para quitar filtro
    "<div class='col-md-1'><button onclick='javascript:ZCAccionQuitarFiltro(event ,this);'class='btn btn-warning zc-filtros-quitar' id='quitar-"+identificadorFiltro+"' name='quitar-"+identificadorFiltro+"'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></button></div>" +
    "<div class='col-md-1'><input id='filtros-seleccionados-" + identificadorFiltro + "' name='filtros-seleccionados-" + identificadorFiltro + "' class='zc-filtros-seleccionado' type='hidden' value='"+filtro+"|?|"+operador+"|?|"+valor+"'/></div>" +
    "<div class='col-md-1'></div>" +
    "<div class='col-md-1'></div>" +
    "<div class='col-md-1'></div>" +
    "</div>");
    // Deja en blaco nuevamente el campo para el ingreso de datos
    $('#' + filtro).val('');
}

/**
 * Quitar filtro de busqueda de los filtros seleccionados
 * @param {e} Evento disparador
 * @param {$} id
 * @returns {undefined}
 */
function ZCAccionQuitarFiltro(e, id){
    e.preventDefault();
    var filtro = $(id).attr('id').replace('quitar-', '');
    console.log(filtro);
    $('div').remove('#zc-filtros-aplicados-'+filtro);
}

/**
 * Ejecuta la accion de busqueda con los filtros seleccionados
 * @param {event} e
 * @param {string} formulario
 * @param {string} id
 * @returns {undefined}
 */
function ZCAccionBuscarFiltro(e, formulario, id){
    e.preventDefault();
    var filtro = '';
    var filtrosAEnviar = '';
    $('#'+formulario).find($('.zc-filtros-seleccionado')).each(function(){
        //Verifica que no se halla contado antes
        //Salta elementos que no tengan id definido
        var filtro = $('#'+this.id).val();
        if(filtro != ''){
            console.log(filtro);
            filtrosAEnviar += (filtrosAEnviar != '') ? '|??|' : '';
            filtrosAEnviar += filtro;
        }
    });
    
    //Si existen filtros validos, envia solicitud al servidor
    if(filtrosAEnviar != ''){
        console.log(filtrosAEnviar);
    }
    
}

/**
 * Oculta los filtros de busqueda
 * @param {event} e
 * @param {string} formulario
 * @param {string} id
 * @returns {undefined}
 */

function ZCAccionOcultarFiltro(e, formulario, id){
    e.preventDefault();
    $('#'+formulario).find($('.zc-filtros-disponibles')).addClass('hidden');
    $('#'+formulario).find($('.zc-filtros-mostrar')).removeClass('hidden');
    $('#'+formulario).find($('.zc-filtros-ocultar')).addClass('hidden');
}

/**
 * Mostrar los filtros de busqueda
 * @param {event} e
 * @param {string} formulario
 * @param {string} id
 * @returns {undefined}
 */

function ZCAccionMostrarFiltro(e, formulario, id){
    e.preventDefault();
    $('#'+formulario).find($('.zc-filtros-disponibles')).removeClass('hidden');
    $('#'+formulario).find($('.zc-filtros-ocultar')).removeClass('hidden');
    $('#'+formulario).find($('.zc-filtros-mostrar')).addClass('hidden');
}