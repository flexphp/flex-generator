/**
 * Actualiza la barra de progreso segun se vayan diligenciando los datos
 * @param {string} formulario Id del formulario al que se la va validar el progreso
 * @param {object} formasValidar Id del formulario al que se la va validar el progreso
 * @returns {Boolean}
 */
function ZCBarraProgreso(formulario, formasValidar) {
    // Cantidad de campos encontrados en el formulario
    var contadorCampos = 0;
    // Cantidad de campos validos
    var contadorCamposValidos = 0;
    // Valida que los cambpos checkbox y radio no se cuenten mas de una vez
    var nombres = [];
    // Todos campos del formulario
    $('#'+formulario).find($(formasValidar)).each(function() {
        //Verifica que no se halla contado antes
        //Salta elementos que no tengan id definido
        if (nombres.indexOf(this.name) === -1 && this.id !== '') {
            nombres.push(this.name);
            contadorCampos++;
            if ($('#'+this.id).parsley().isValid('#'+this.id)) {
                contadorCamposValidos++;
            }
        }
    });
    // Calcula un numero entero por encima
    var progreso = Math.ceil((contadorCamposValidos*100)/contadorCampos);
    $('#progreso-'+formulario).css('width', progreso + '%').attr('aria-valuenow', progreso);
    $('#msj-progreso-'+formulario).html(progreso + '%');
    if (progreso === 100) {
        // Deja clases de exito
        $('#progreso-'+formulario).addClass('progress-bar-success').removeClass('progress-bar-striped').removeClass('active');
    } else {
        // Deja clases iniciales
        $('#progreso-'+formulario).removeClass('progress-bar-success').addClass('progress-bar-striped').addClass('active');
    }
}

/**
 * Deja el formulario en los valores iniciales
 * @param {event} e
 * @param {string} formulario
 * @returns {undefined}
 */
function ZCAccionReiniciarFormulario(e, formulario) {
    e.preventDefault();
    $('.parsley-errors-list').hide();
    $('#error-'+formulario).text('');
    $('.alert').hide();
    $('#'+formulario).trigger('reset');
}

/**
 * Valida los campos durande la accion cancelar, si alguno tiene datos, muestra mensaje de advertencia
 * @param {event} e
 * @param {string} formulario Id del formulario al que se la va validar el progreso
 * @param {object} formasValidar Id del formulario al que se la va validar el progreso
 * @returns {Boolean}
 */
function ZCAccionCancelar(e, formulario, formasValidar) {
    e.preventDefault();
    // Todos campos del formulario
    var existenCamposDiligenciados = false;
    var valorCampo = '';
    var nombres = [];
    $('#'+formulario).find($(formasValidar)).each(function() {
        if (nombres.indexOf(this.name) === -1) {
            nombres.push(this.name);
            switch(true) {
                case $('#'+this.id).attr('type') == 'checkbox':
                    var marcados = 0;
                    if ($('#'+this.id).prop("checked")) {
                        marcados++;
                    }
                    valorCampo = (marcados === 0) ? '' : 'Lleno';
                    break;
                case $('#'+this.id).attr('type') === 'radio':
                    valorCampo = $('input[name='+this.name+']:checked').val();
                    break;
                default:
                    valorCampo = $.trim($('#'+this.id).val());
                    break;
            }
            if (valorCampo !== '' && valorCampo !== undefined) {
                existenCamposDiligenciados = true;
            }
        }
    });

    return existenCamposDiligenciados;
}

/**
 * Menejo de los filtros de busqueda
 * @param {event} e Evento disparador
 * @param {string} formulario Identificador del formulario
 * @param {type} id
 * @returns {Boolean}
 */
function ZCCamposDeBusqueda(e, formulario, id) {
    e.preventDefault();
    if (!$(id).length) {
        return false;
    }
    // Filtro actual
    var filtro = $(id).val();
    // Determina el tipo de operador a aplicar segun el campo (texto, numerico, lista)
    var operadorFiltro = $('.zc-filtros-busqueda option:selected').attr('zc-operador');
    // Oculta la lista de filtros que no aplican
    $('.zc-operador').addClass('hidden');
    // Solo deja a la vista el tipo de filtro para el campo seleccionado
    $('#operador-' + operadorFiltro).removeClass('hidden');
    // Oculta todos los campos de la lista de campos
    $('.zc-campos').addClass('hidden');
    // Muestra el campos seleccionado
    $('#campo-' + filtro).removeClass('hidden');
    // Deja el valor nuevamente en vacio para una nueva asignacion
    $('#' + filtro).val('');
}

/**
 * Agrega un filtro de busqueda seleccionado por el usuario
 * @param {event} e Evento disparador
 * @param {string} formulario
 * @param {$} id
 * @returns {undefined}
 */
function ZCAccionAgregarFiltro(e, formulario, id) {
    e.preventDefault();
    // Nombre del controlador
    var controlador = $('#zc-controlador').val()
    // Determina el campo por el ual se desea filtrar
    var filtro = $.trim($('.zc-filtros-busqueda option:selected').val());
    // Determina el tipo de operador a aplicar segun el campo (texto, numerico, lista)
    var operadorFiltro = $('.zc-filtros-busqueda option:selected').attr('zc-operador');
    // Determina el filtro aplicado (=,>,>=,etc)
    var operador = $('#zc-operador-' + operadorFiltro).val();
    // Valor y nombre del filtro selecionado
    var valor = textoValor = '';

    // Determina como debe extraer el valor del campo
    switch(true) {
        case $('input[name^='+filtro+']').attr('type') === 'checkbox':
            $('input[name^='+filtro+']:checked').each(function() {
                valor = $('#'+this.id).val();
                textoValor = $('#'+this.id).attr('zc-texto');
            });
            break;
        case $('input[name='+filtro+']').attr('type') === 'radio':
            valor = $('input[name='+filtro+']:checked').val();
            textoValor = (valor !== undefined) ? $('#'+filtro+'_'+valor).attr('zc-texto') : '';
            break;
        case $('#'+filtro).prop('type') === 'select-one':
            valor = $.trim($('#'+filtro).val());
            textoValor = (valor !== '') ? $('#' + filtro + " option:selected").html() : '';
            break;
        //case $('#'+filtro).prop('type') === 'select-multiple':
        //    break;
        default:
            valor = $.trim($('#'+filtro).val());
            textoValor = valor;
            break;
    }

    if (!$('#'+formulario).parsley().validate()) {
        // Valida que sea un valor permitido
        return false;
    }

    var textoFiltro = $('.zc-filtros-busqueda option:selected').html();
    var textoOperador = $('#zc-operador-' + operadorFiltro + ' option:selected').html();
    var textoValor = (textoValor !== '') ? textoValor : '<i>(vacio)</i>';

    // Evita eliminar filtros repetidos, se maneja como numero
    var cantidadFiltros = parseInt($('#zc-filtros-cantidad-filtros').val());
    var identificadorFiltro = filtro+'-'+cantidadFiltros;
    // Suma al numero de filtros, nunca se repite el id, asi no importa el orden en el que son eliminados
    $('#zc-filtros-cantidad-filtros').val((cantidadFiltros+1));

    // Agrega un campo oculto al formulario con los valores a enviar
    $('#' + formulario).append("<div class='row zc-filtros-disponibles' id='zc-filtros-aplicados-" + identificadorFiltro + "'>" +
    "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center'>" +
    // Etiqueta del campo
    textoFiltro + ' ' +
    // Operador
    textoOperador + ' ' +
    // Valor
    textoValor + ' ' +
    // Boton para quitar filtro
    "<button title='Quitar filtro de busqueda' onclick=\"javascript:ZCAccionQuitarFiltro(event , '" + identificadorFiltro + "');\" class='btn btn-warning zc-filtros-quitar'><span class='glyphicon glyphicon-minus-sign' aria-hidden='true'></span><span class='hidden-xs'>Quitar</span></button>" +
    "<input id='filtros-seleccionados-" + identificadorFiltro + "' name='filtros-seleccionados-" + identificadorFiltro + "' class='zc-filtros-seleccionado' type='hidden' value='"+ controlador + '|?|' + filtro+"|?|"+operador+"|?|"+valor+"'/>" +
    "</div>" +
    "</div>");
    // Deja en blanco nuevamente el campo para el ingreso de datos
    $('#' + filtro).val('');
}

/**
 * Quitar filtro de busqueda de los filtros seleccionados
 * @param {event} e Evento disparador
 * @param {$} filtro
 * @returns {undefined}
 */
function ZCAccionQuitarFiltro(e, filtro) {
    e.preventDefault();
    $('div').remove('#zc-filtros-aplicados-'+filtro);
    // Oculta mensajes de error, evita error generado por el paso anterior
    $('.alert').hide();
}

/**
 * Ejecuta la accion de busqueda con los filtros seleccionados
 * @param {string} formulario
 * @returns {undefined}
 */
function ZCAccionBuscarFiltro(formulario) {
    var filtrosAEnviar = '';
    $('#'+formulario).find($('.zc-filtros-seleccionado')).each(function() {
        //Verifica que no se halla contado antes
        //Salta elementos que no tengan id definido
        var filtro = $('#'+this.id).val();
        if (filtro !== '' && filtro !== undefined) {
            filtrosAEnviar += (filtrosAEnviar !== '') ? '|??|' : '';
            filtrosAEnviar += filtro;
        }
        if (this.id === 'filtros-seleccionados-0') {
            //Elimina el filtro predefinido, esto evita utilizar el filtro en otras busquedas
            $('#'+this.id).val('');
        }
    });
    //Establece el valor de los filtros a utilizar en la busqueda
    return filtrosAEnviar;
}

/**
 * Valores de busqueda predefinidos, se usa en la accion crear
 * @param {string} formulario Nombre del formaulario a utilizar
 * @returns {String}
 */
function ZCAccionBuscarPredefinido(formulario) {
    var filtrosPredefinidos = $('#zc-filtros-predefinidos').val();
    if (filtrosPredefinidos !== undefined && filtrosPredefinidos !== '') {
        //Establece el valor de los filtros a utilizar en la busqueda
        $('#'+formulario).append('<input id="filtros-seleccionados-0" type="hidden" class="zc-filtros-seleccionado" value="'+filtrosPredefinidos+'">');
        // Ejecuta accion boton buscar
        $('#'+formulario).find('.zc-accion').trigger('click');
    }
    return true;
}

/**
 * Oculta los filtros de busqueda
 * @param {event} e
 * @param {string} formulario
 * @param {string} id
 * @returns {undefined}
 */
function ZCAccionOcultarFiltro(e, formulario, id) {
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
function ZCAccionMostrarFiltro(e, formulario, id) {
    e.preventDefault();
    $('#'+formulario).find($('.zc-filtros-disponibles')).removeClass('hidden');
    $('#'+formulario).find($('.zc-filtros-ocultar')).removeClass('hidden');
    $('#'+formulario).find($('.zc-filtros-mostrar')).addClass('hidden');
}

/**
 * Crea el listado de campos devueltos por el servidor, agrega enlace para la edicion del registro
 * @param {string} formulario Nombre del formulario donde se creara la el listado
 * @param {json} rpta Valores devueltos por la consulta
 * @returns {undefined}
 */
function ZCListarResultados(formulario, rpta) {
    var tabla = "<table class='table table-bordered table-hover'>";
    var encabezados = '';
    var columnas = '';
    var id = -1;

    for(var i = 0; i < rpta.cta; ++i) {
        for (var key in rpta.infoEncabezado[i]) {
            if (rpta.infoEncabezado[i].hasOwnProperty(key)) {
                // El campo Id no se muestra
                if (i === 0 && $.trim(key) !== '' && key !== 'id') {
                    //Crea los encabezados
                    encabezados += '<th>'+key+'</th>';
                }
                if (key === 'id'  && id === -1) {
                    // Agrega el id del registro para el caso de modificacion
                    id = rpta.infoEncabezado[i][key];
                } else {
                    // Valida que exista el valor, de lo contrario lo deja vacio
                    columnas += '<td>' + ((!rpta.infoEncabezado[i][key]) ? '' : rpta.infoEncabezado[i][key]) +  '</td>';
                }
            }
        }
        if (i === 0) {
            tabla += '<tr>'+encabezados+'</tr>';
        }
        if (!rpta.infoEncabezado[i]) {
            // No existen mas registros, termina el ciclo
            break;
        }
        // Agrega accion de enlace a la edicion del registro
        tabla += '<tr style="cursor: pointer;" onclick="ZCAccionModificarRegistro(event, this);" zc-id-registro="'+id+'">'+columnas+'</tr>';
        columnas = '';
        id = -1;
    }

    tabla += "</table>";
    $('#listado-'+formulario).html(tabla);
    $('#paginacion-'+formulario).html(rpta.paginacion);
}

/**
 * Devuelve el id del registro actual, el cual se desea modificar
 * @param {String} formulario
 * @returns {String}
 */
function ZCAccionCondicion(formulario) {
    var id = $.trim($('#zc-id-'+formulario).val());
    return id;
}

/**
 * Direcciona a la pagina para agregar un nuevo registro
 * @param {event} e Evento disparador
 * @param {type} id
 * @returns {Boolean}
 */
function ZCAccionNuevoRegistro(e) {
    e.preventDefault;
    // Nombre del controlador
    var controlador = $('#zc-controlador').val();
    // Direcciona a la pagina de agregar
    window.location.assign($('#URLProyecto').val() + 'index.php/' + controlador + '/nuevo');
}

/**
 * Direcciona a la pagina para la edicion del registro
 * @param {event} e Evento disparador
 * @param {object} enlace This del enlace dado
 * @returns {Boolean}
 */
function ZCAccionModificarRegistro(e, enlace) {
    e.preventDefault;
    // Id del registro a modificar
    var id = $(enlace).attr('zc-id-registro');
    // Nombre del controlador
    var controlador = $('#zc-controlador').val();
    // Direcciona a la pagina de agregar
    window.location.assign($('#URLProyecto').val()+'index.php/'+controlador+'/editar/'+id);
}

/**
 * Determina los botones a mostrar en el formulario, depende de si es actualizacion o nuevo registro
 * @param {String} formulario Formulario donde estan los botones
 * @param {String} agregar Nombre de la funcion agregar
 * @param {String} modificar Nombre de la funcion modificar
 * @param {String} borrar Nombre de la funcion borrar
 * @param {String} precargar Nombre de la funcion precargar
 * @returns {String}
 */
function ZCAccionBotones(formulario, agregar, modificar, borrar, precargar) {
    // Id del registro a modificar
    var id = $.trim($('#zc-id-'+formulario).val());

    if ('' === id) {
        // Oculta los botones de modificar y borrar
        $('#'+modificar).addClass('hidden');
        $('#'+borrar).addClass('hidden');
    } else {
        // Oculta el boton de agregar
        $('#'+agregar).addClass('hidden');
        // Consulta la informacion en la base de datos
        ZCAccionPrecargar(formulario, id, precargar, modificar);
    }
    // Activar botones de acciones
    ZCActivarBotonPrincipal(formulario);
}

/**
 * Precarga los datos devueltos por la consulta a la base de datos
 * @param {string} formulario Nombre del formulario
 * @param {json} rpta Datos devueltos por el servidor
 * @returns {undefined}
 */
function ZCAccionPrecargarResultado(formulario, rpta) {
    for (var campo in rpta.infoEncabezado) {
        switch(true) {
        case $('input[name^='+campo+']').attr('type') === 'checkbox':
            $('#'+formulario + ' #'+campo+'_'+rpta.infoEncabezado[campo]).prop('checked', true);
            break;
        case $('input[name='+campo+']').attr('type') === 'radio':
            $('#'+formulario + ' #'+campo+'_'+rpta.infoEncabezado[campo]).prop('checked', true);
            break;
        case $('#'+campo).prop('type') === 'select-one':
            $('#'+formulario + ' #'+campo).val(rpta.infoEncabezado[campo]);
            break;
        case $('#'+campo).prop('type') === 'select-multiple':
            $('#'+formulario + ' #'+campo).val(rpta.infoEncabezado[campo]);
            break;
        case $('#'+campo).attr('type') === 'password':
            // No se deja obligatorios, si la persona lo diligencia se cambia en el servidor, de lo contrario se
            // deja el valor que estaba
            $('#'+formulario + ' #'+campo + ', #x'+campo).removeAttr('data-parsley-required');
            // Las contrasenas las dejan vacias
            $('#'+formulario + ' #'+campo + ', #x'+campo).val('');
            break;
        default:
            $('#'+formulario + ' #'+campo).val(rpta.infoEncabezado[campo]);
            break;
        }
    }
}

/**
 * Accion que da accion a los botones de paginacion
 * @param {event} e Evento disparado
 * @param {string} formulario Nombre del formulario
 * @param {$} id Objeto javascript
 * @returns {undefined}
 */
function ZCAccionPaginarResultado(e, formulario, id) {
    e.preventDefault();
    var href = $(id).attr('href');
    ZCAccionPaginar(href, formulario);
}

/**
 * Carga la lista de resultados y la paginacion, se usa en la accion buscar y en
 * los botones de paginacion
 * @param {string} miURL URL donde se hace la peticion para la recarga de los datos
 * @param {string} formulario Nombre del formulario de trabajo actual
 * @returns {undefined}
 */
function ZCAccionPaginar(miURL, formulario) {
    var nombreAccion = 'buscar';
    // Filtros usados para la busqueda
    var filtrosAEnviar = ZCAccionBuscarFiltro(formulario);
    // Extrae el numero de la pagina
    var paginaActual = miURL.substring(miURL.lastIndexOf('/')+1);

    $.ajax({
        // Para construir la paginacion se necesita el numero de la pagina en la url
        url: miURL,
        type: 'POST',
        dataType: 'JSON',
        data: {
            // Envia filtros de busqueda al servidor
            filtros: filtrosAEnviar,
            pagina: paginaActual,
            accion: nombreAccion
        },
        beforeSend: function() {
            // Inactivar el boton, solo permite un envio a la vez
            $('#'+nombreAccion).addClass('disabled').prop('disabled', true);
            // Oculta ventana con mensajes
            $('.alert').hide();
            // Limpia resultados anteriores
            $('#listado-'+formulario).html('');
            // Limpia la paginacion
            $('#paginacion-'+formulario).html('');
            // Mostrar cargando
            $('#cargando-'+formulario).removeClass('hidden');
        },
        success: function(rpta) {
            if (rpta.error !== undefined || (typeof rpta.error === 'object' && Object.keys(rpta.error).length > 0)) {
                // Muestra mensaje de error
                $('#error-'+formulario).text(rpta.error);
                $('.alert-danger').show();
            } else {
                ZCListarResultados(formulario, rpta);
            }
        },
        complete: function() {
            // Activar el boton cuando se completa la accion, con error o sin error
            $('#'+nombreAccion).removeClass('disabled').prop('disabled', false);
            // Ocultar cargando
            $('#cargando-'+formulario).addClass('hidden');
        },
        error: function(rpta) {
            $('#error-'+formulario).text('Error en el servicio');
            $('.alert-danger').show();
        }
    });
}

/**
 * Carga de forma ajax los posibles valores del campo
 * @param {string} lista Nombre del campo que se esta contruyendo
 * @param {json} rpta Respuesta de los valores devueltos por el ervidor
 * @returns {undefined}
 */
function ZCPrecargarSeleccion(lista, rpta) {
    // Valor de campo
    var id = '';
    // Descripcion del campo
    var valor = '';
    var opcion = '';
    for(var i = 0; i < rpta.cta; ++i) {
        for (var key in rpta.infoEncabezado[i]) {
            if (rpta.infoEncabezado[i].hasOwnProperty(key)) {
                if (key === 'id') {
                    id = rpta.infoEncabezado[i][key];
                    continue;
                } else {
                    valor = rpta.infoEncabezado[i][key]
                }
            }
        }
        opcion += '<option value="' + id + '">' + valor + '</option>';
        // Reinicia los valores
        id = '';
        valor = '';
    }
    // Agrega las opciones al campo
    $('#'+lista).append(opcion);
}

/**
 * Carga de forma ajax los posibles valores del campo
 * @param {string} contenedor Nombre del div donde se agregaran las opciones
 * @param {string} radio Nombre del campo que se esta contruyendo
 * @param {string} obligatorio indica si es obligatorio el campo
 * @param {string} msjObligatorio Mensaje deerror para los campos obligatorios
 * @param {json} rpta Respuesta de los valores devueltos por el ervidor
 * @returns {undefined}
 */
function ZCPrecargarRadio(contenedor, radio, obligatorio, msjObligatorio, rpta) {
    // Valor de campo
    var id = '';
    // Descripcon del campo
    var valor = '';
    var opcion = '';
    var htmlExtra = '';
    for(var i = 0; i < rpta.cta; ++i) {
        for (var key in rpta.infoEncabezado[i]) {
            if (rpta.infoEncabezado[i].hasOwnProperty(key)) {
                if (key === 'id') {
                    id = rpta.infoEncabezado[i][key];
                    continue;
                } else {
                    valor = rpta.infoEncabezado[i][key]
                }
            }
        }
        if(0 == i && '' != obligatorio) {
            // Solo se anade en el primer elemento
            htmlExtra = obligatorio + ' ' + msjObligatorio;
        } else {
            htmlExtra = '';
        }

        opcion += "<label class='radio-inline' for='" + id + "'>" +
                    "<input" +
                    " type='radio'" +
                    " class='radio'" +
                    // Permite extraer rapidamente la descripcion de la opcion, se usa en el buscador
                    " zc-texto='" + valor +"'" +
                    // Identificador campo
                    " id='" + radio + "_" + id + "'" +
                    " name='" + radio + "'" +
                    " value='" + valor + "'" +
                    htmlExtra +
                    "/>" +
                    valor +
                    "</label>";
        // Reinicia los valores
        id = '';
        valor = '';
    }
    // Agrega las opciones al campo
    $('#' + contenedor).append(opcion);
}

/**
 * Resalta el menu actual
 * @returns {undefined}
 */
function ZCMenuActual() {
    // Nonbre del controlador
    var controlador = $('#zc-controlador').val();
    $('#zc-menu-' + controlador).addClass('active').css('font-weight', 'bold');
}

/**
 * Activa la accion por defecto al dar enter en cualquier campo del formulario
 * @param {string} formulario
 */
function ZCActivarBotonPrincipal(formulario) {
    $('body').delegate(':text, :password', 'keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            // Ejecuta boton primario no oculto
            $('#'+formulario+' .btn-primary').not('.hidden').trigger('click');
        }
    });
}

/**
 * Consulta los datos del registros y los precarga para poder modificar los datos
 * @param {string} formulario
 * @param {int} id
 * @param {string} precargar
 * @param {string} modificar
 */
function ZCAccionPrecargar(formulario, id, precargar, modificar) {
    // Nonbre del controlador
    var controlador = $('#zc-controlador').val();
    $.ajax({
        url: $('#URLProyecto').val()+'index.php/' + controlador + '/' + precargar + '/',
        type: 'POST',
        dataType: 'JSON',
        data: {
            // Envia filtros de busqueda al servidor
            id: id,
            accion: precargar
        },
        beforeSend: function() {
            // Desactiva todos los campos
            $('input, textarea, select, button').addClass('disabled').prop('disabled', true);
            // Oculta ventana con mensajes
            $('.alert').hide();
            // Mostrar cargando
            $('#'+modificar+' span').addClass('glyphicon-refresh glyphicon-refresh-animate');
        },
        success: function(rpta) {
            if (rpta.error !== undefined && '' !== rpta.error) {
                // Muestra mensaje de error
                $('#error-' + formulario).text(rpta.error);
                $('.alert-danger').show();
            } else {
                ZCAccionPrecargarResultado(formulario, rpta);
            }
        },
        complete: function() {
            // Activar los campos para la modificacion
            $('input, textarea, select, button').removeClass('disabled').prop('disabled', false);
            // Ocultar cargando
            $('#'+modificar+' span').removeClass('glyphicon-refresh glyphicon-refresh-animate');
        },
        error: function(rpta) {
            $('#error-' + formulario).text('Error en el servicio');
            $('.alert-danger').show();
        }
    });
}

/**
 * Asigna los errores devueltos por el servidor a cada uno de los campos
 * @param {string} formulario
 * @param {json} rpta
 */
function ZCAsignarErrores(formulario, rpta) {
    var msjError = '';
    if (typeof rpta.error === 'object') {
        // Error en un campo
        $.each(rpta.error, function(campo, error) {
            var label = $("label[for='" + campo + "']").text();
            if (label != ''){
                // Solo si el label existe
                msjError += label + ': ' + error + '<br />';
            }
            // Agrega clase error
            $('#' + campo).addClass('parsley-error');
        });
    } else {
        // Error sin asociacion de campo
        msjError = rpta.error;
    }
    // Establecer error e ir al principio de la pagina
    $('#error-' + formulario).html(msjError);
    $('html, body').animate({ scrollTop: 0 }, 'slow');
}

/**
 * Inicializacion de las restricciones del formulario
 * @param {string} campo
 */
function init(init, formulario, campo) {
    // Nonbre del controlador
    var controlador = $('#zc-controlador').val();
    $.ajax({
        url: $('#URLProyecto').val()+'index.php/' + controlador + '/' + init + '/',
        type: 'POST',
        dataType: 'JSON',
        data: {
            // Envia filtros de busqueda al servidor
            campo: ((campo !== undefined) ? campo : ''),
            accion: init
        },
        beforeSend: function() {
            // Desactiva todos los campos
            $('input, textarea, select, button').addClass('disabled').prop('disabled', true);
            // Oculta ventana con mensajes
            $('.alert').hide();
            // Mostrar cargando
            $('button span').addClass('glyphicon-refresh glyphicon-refresh-animate');
        },
        success: function(rpta) {
            if (rpta.error !== undefined && '' !== rpta.error) {
                // Muestra mensaje de error
                $('#error-' + formulario).text(rpta.error);
                $('.alert-danger').show();
            } else {
                initParsley(formulario, rpta);
            }
        },
        complete: function() {
            // Activar los campos para la modificacion
            $('input, textarea, select, button').removeClass('disabled').prop('disabled', false);
            // Ocultar cargando
            $('button span').removeClass('glyphicon-refresh glyphicon-refresh-animate');
        },
        error: function(rpta) {
            $('#error-' + formulario).text('Error en el servicio');
            $('.alert-danger').show();
        }
    });
}

/**
 * Inicializa los campos con las restricciones parsley
 * @param {string} formulario Identificador del formulario con los campos a inicilizar
 * @param {json} rpta Restricciones de los campos segun el servidor
 */
function initParsley(formulario, rpta){
    // Deshabilitar configuracion anterior
    var busqueda = ($('#zc-filtros-predefinidos').length > 0) ? true : false;

    $('#' + formulario).parsley().destroy();
    for (var campo in rpta.infoEncabezado) {
        for (var cont in rpta.infoEncabezado[campo]) {
            if(busqueda && (
                cont == 'required' || cont == 'required-message'
                || cont == 'min' || cont == 'min-message'
                || cont == 'max' || cont == 'max-message'
                || cont == 'minlength' || cont == 'minlength-message'
                || cont == 'maxlength' || cont == 'maxlength-message'
                )
            ) {
                // Para los formularios de busquedo no se tienen en cuenta estas restricciones
                continue;
            }
            var valor = rpta.infoEncabezado[campo][cont];
            if (cont == 'required' && valor != 'false') {
                // A los campos obligatorios se les agrega el simbolo
                var label = $("label[for='" + campo + "']").append('<font style="color: red;">*</font>');
            }
            // Asigna la restriccion al campo
            $('#' + campo).attr('data-parsley-' + cont, valor);
        }
    }
    // Asignar configuracion
    $('#' + formulario).parsley();
}