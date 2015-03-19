            /**
             * Definicion de la accion {_nombreAccion_}
             */
            if(nombreAccion === '{_nombreAccion_}') {
                $.ajax({
                    // A la accion se le concatena la palabra cliente, asi se llama en la funcion
                    url: $('#URLProyecto').val()+'index.php/{_nombreControlador_}/{_nombreAccion_}/',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $('#{_nombreFormulario_}').serialize()+'&accion='+nombreAccion,
                    beforeSend: function(){
                        // Inactivar el boton, solo permite un envio a la vez
                        $('#'+nombreAccion).addClass('disabled').prop('disabled', true);
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
                            // Establece el id devuelto durante el proceso de insercion
                            $('#zc-id-{_nombreFormulario_}').val(rpta.infoEncabezado);
                        }
                    },
                    complete: function(){
                        // Activar el boton cuando se completa la accion, con error o sin error
                        $('#'+nombreAccion).removeClass('disabled').prop('disabled', false);
                    },
                    error: function(rpta){
                        $('#error-{_nombreFormulario_}').text('Error en el servicio');
                        $('.alert-danger').show();
                    }
                });
            }
