        // Definicion de la accion {_nombreAccion_}
        if(nombreAccion === '{_nombreAccion_}' && $('#{_idFormulario_}').parsley().validate()) {
            // Valida que exista id a modificar
            var id = ZCAccionCondicion('{_idFormulario_}');
            if (id == '') {
                ZCAsignarErrores('Error durante la accion');
            } else {
                $.ajax({
                    url: URLControlador + '{_nombreAccion_}/',
                    method: 'POST',
                    dataType: 'JSON',
                    // Envia los campos y el id del registro a actualizar
                    data: $('#{_idFormulario_}').serialize()+'&id='+id+'&accion='+nombreAccion,
                    beforeSend: function(){
                        // Inactivar los campos para evitar modificaciones antes del envio
                        desactivarCampos();
                    },
                    success: function(rpta){
                        if (ZCRespuestaConError(rpta)) {
                            ZCAsignarErrores(rpta);
                        } else {
                            // Carga el listado cmostrando el registro insertado
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
        }
