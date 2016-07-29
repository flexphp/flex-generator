            // Definicion de la accion {_nombreAccion_}
            if (nombreAccion === '{_nombreAccion_}') {
                $.ajax({
                    // A la accion se le concatena la palabra cliente, asi se llama en la funcion
                    url: URLControlador + '{_nombreAccion_}/',
                    method: 'POST',
                    dataType: 'JSON',
                    data: $('#{_idFormulario_}').serialize()+'&accion='+nombreAccion,
                    beforeSend: function(){
                        // Inactivar los campos para evitar modificaciones antes del envio
                        desactivarCampos();
                    },
                    success: function(rpta){
                        if (ZCRespuestaConError(rpta)) {
                            // Muestra mensaje de error
                            ZCAsignarErrores(rpta);
                        } else {
                            {_accionCliente_}
                        }
                    },
                    complete: function(){
                        // Activar los campos cuando se completa la solicitud, con error o sin error
                        activarCampos();
                    },
                    error: function(rpta){
                        ZCAsignarErrores('Error en el servicio');
                    }
                });
            }
