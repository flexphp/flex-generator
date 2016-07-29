        // Definicion de la accion {_nombreAccion_}
        if (nombreAccion === '{_nombreAccion_}' && $('form').parsley().validate()) {
            $.ajax({
                url: URLControlador + '{_nombreAccion_}/',
                method: 'POST',
                dataType: 'JSON',
                data: $('form').serialize()+'&accion='+nombreAccion,
                beforeSend: function() {
                    // Inactivar los campos para evitar modificaciones antes del envio
                    desactivarCampos();
                },
                success: function(rpta){
                    if (ZCRespuestaConError(rpta)) {
                        // Muestra mensaje de error
                        ZCAsignarErrores(rpta); 
                        // Limpia el valor de las contrasenas
                        $(':password').val('');
                    } else {
                        window.location.assign('inicio');
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
