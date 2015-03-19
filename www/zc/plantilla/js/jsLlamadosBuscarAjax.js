            /**
             * Definicion de la accion {_nombreAccion_}
             */
            if(nombreAccion === '{_nombreAccion_}') {
                var filtrosAEnviar = ZCAccionBuscarFiltro('{_nombreFormulario_}');
//                Descomentarioar para validar al menos un filtros de busqueda
//                if(filtrosAEnviar == ''){
//                    $('#error-{_nombreFormulario_}').text('{_mensajeError_}');
//                    $('.alert-danger').show();
//                    return false;
//                }
                $.ajax({
                    url: $('#URLProyecto').val()+'index.php/{_nombreControlador_}/{_nombreAccion_}/',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        // Envia filtros de busqueda al servidor
                        filtros: filtrosAEnviar,
                        accion: nombreAccion
                    },
                    beforeSend: function(){
                        // Inactivar el boton, solo permite un envio a la vez
                        $('#'+nombreAccion).addClass('disabled').prop('disabled', true);
                        // Oculta ventana con mensajes
                        $('.alert').hide();
                        // Limpia resultados anteriores
                        $('#listado-{_nombreFormulario_}').html('');
                        // Mostrar cargando
                        $('#cargando-{_nombreFormulario_}').removeClass('hidden');
                    },
                    success: function(rpta){
                        if(rpta.error !== undefined && '' !== rpta.error){
                            // Muestra mensaje de error
                            $('#error-{_nombreFormulario_}').text(rpta.error);
                            $('.alert-danger').show();
                        }else{
                            ZCListarResultados('listado-{_nombreFormulario_}', rpta);
                        }
                    },
                    complete: function(){
                        // Activar el boton cuando se completa la accion, con error o sin error
                        $('#'+nombreAccion).removeClass('disabled').prop('disabled', false);
                        // Ocultar cargando
                        $('#cargando-{_nombreFormulario_}').addClass('hidden');
                    },
                    error: function(rpta){
                        $('#error-{_nombreFormulario_}').text('Error en el servicio');
                        $('.alert-danger').show();
                    }
                });
            }