            /**
             * Definicion de la accion {_nombreAccion_}
             */
            if(nombreAccion === '{_nombreAccion_}') {
                {_inicializacionCliente_}
                $.ajax({
                    // A la accion se le concatena la palabra cliente, asi se llama en la funcion
                    url: $('#URLProyecto').val()+'index.php/{_nombreControlador_}/{_nombreAccion_}/',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $('#{_nombreFormulario_}').serialize()+'&accion='+nombreAccion,
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
                    }
                });
            }
