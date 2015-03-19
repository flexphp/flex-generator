            /**
             * Definicion de la accion {_nombreAccion_}
             */
            if(nombreAccion === '{_nombreAccion_}') {
                // Valida que exista condicion de busca
                var condicion = ZCAccionModificarCondicion('{_nombreFormulario_}');
                if(condicion == ''){
                    $('#error-{_nombreFormulario_}').text('Error durante la actualizacion');
                    $('.alert-danger').show();
                }else{
                    $.ajax({
                        url: $('#URLProyecto').val()+'index.php/{_nombreControlador_}/{_nombreAccion_}/',
                        type: 'POST',
                        dataType: 'JSON',
                        // Envia los campos y el id del registro a actualizar
                        data: $('#{_nombreFormulario_}').serialize()+'&condicion='+condicion+'&accion='+nombreAccion,
                        beforeSend: function(){
                            // Inactivar el boton, solo permite un envio a la vez
                            $('#'+nombreAccion).addClass('disabled').prop('disabled', true);
                            // Oculta ventana con mensajes
                            $('.alert').hide();
                            // Limpia resultados anteriores
                            $('#listado-{_nombreFormulario_}').html('');
                            // Mostrar cargando
                            $('#'+nombreAccion+' span').addClass('glyphicon-refresh glyphicon-refresh-animate');
                        },
                        success: function(rpta){
                            if(rpta.error !== undefined && '' !== rpta.error){
                                // Muestra mensaje de error
                                $('#error-{_nombreFormulario_}').text(rpta.error);
                                $('.alert-danger').show();
                            }else{
                                
                            }
                        },
                        complete: function(){
                            // Activar el boton cuando se completa la accion, con error o sin error
                            $('#'+nombreAccion).removeClass('disabled').prop('disabled', false);
                            // Ocultar cargando
                            $('#'+nombreAccion+' span').addClass('glyphicon-refresh glyphicon-refresh-animate');
                        },
                        error: function(rpta){
                            $('#error-{_nombreFormulario_}').text('Error en el servicio');
                            $('.alert-danger').show();
                        }
                    });
                }
            }