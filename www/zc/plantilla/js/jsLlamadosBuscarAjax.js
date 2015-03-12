            /**
             * Definicion de la accion {_nombreAccion_}
             */
            if(nombreAccion === '{_nombreAccion_}') {
                var filtrosAEnviar = ZCAccionBuscarFiltro('{_nombreFormulario_}');
                if(filtrosAEnviar == ''){
                    $('#error-{_nombreFormulario_}').text('{_mensajeError_}'); 
                    $('.alert-danger').show();
                }else{
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
                            // Oculta ventana con mensajes
                            $('.alert').hide();
                        },
                        success: function(rpta){
                            console.log(rpta);
                            if(rpta.error != undefined && '' != rpta.error){
                                // Muestra mensaje de error
                                $('#error-{_nombreFormulario_}').text(rpta.error); 
                                $('.alert-danger').show();
                            }else{
                                {_accionCliente_}
                            }
                        },
                        error: function(rpta){
                            $('#error-{_nombreFormulario_}').text('Error en el servicio');
                            $('.alert-danger').show();
                        }
                    });
                }
            }