        // Definicion de la accion {_nombreAccion_}
        if (nombreAccion === '{_nombreAccion_}' && $('#{_idFormulario_}').parsley().validate()) {
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
                        // Establece el id devuelto durante el proceso de insercion
                        $('#zc-id-{_idFormulario_}').val(rpta.info);
                        // Carga el listado con el registro insertado
                        window.location.assign(URLControlador + 'listar/' + rpta.info);
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
