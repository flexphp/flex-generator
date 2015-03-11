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

    // Evita eliminar filtros repetidos
    var cantidadFiltros = $('.zc-filtros-aplicados-'+filtro).length;
    console.log(filtro+operador+valor);

    // Agrega un campo oculto al formaulario con los valores a enviar
    $('#' + formulario).append("<div class='row zc-filtros-aplicados-"+filtro+"' id='zc-filtros-aplicados-"+filtro+'-'+cantidadFiltros+"'>"+
    "<div class='col-md-1'></div>"+
    // Etiqueta del campo
    "<div class='col-md-2 text-center'>"+textoFiltro+"</div>" +
    // Operador
    "<div class='col-md-2 text-center'>"+textoOperador+"</div>" +
    // Valor
    "<div class='col-md-3 text-center'>"+valor+"</div>" +
    // Boton para quitar filtro
//    "<div class='col-md-2'><button class='btn btn-warning zc-filtros-quitar' id='quitar-"+filtro+'-'+cantidadFiltros+"' name='quitar-"+filtro+'-'+cantidadFiltros+"'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span>Quitar</button></div>" +
    "<div class='col-md-2'><button onclick='javascript:ZCAccionQuitarFiltro(event ,this);'class='btn btn-warning zc-filtros-quitar' id='quitar-"+filtro+'-'+cantidadFiltros+"' name='quitar-"+filtro+'-'+cantidadFiltros+"'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span>Quitar</button></div>" +
    "<div class='col-md-1'><input id='filtros-seleccionados[]' name='filtros-seleccionados' type='hidden' value='"+filtro+"|?|"+operador+"|?|"+valor+"'/></div>" +
    "<div class='col-md-1'></div>" +
    "</div>");
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

