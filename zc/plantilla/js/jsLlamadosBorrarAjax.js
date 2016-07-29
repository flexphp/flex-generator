        // Definicion de la accion {_nombreAccion_}
        if (nombreAccion === '{_nombreAccion_}') {
            // Valida que exista id a borrar
            var id = ZCAccionCondicion('{_idFormulario_}');
            if (id == '') {
                ZCAsignarErrores('Error durante la accion');
            } else if (confirm('El registro sera eliminado, desea continuar?')) {
                $.ajax({
                    url: URLControlador + '{_nombreAccion_}/',
                    method: 'POST',
                    dataType: 'JSON',
                    // Envia los campos y el id del registro a actualizar
                    data: $('#{_idFormulario_}').serialize()+'&id='+id+'&accion='+nombreAccion,
                    beforeSend: function(){
                        // Inactivar los campos para evitar modificaciones antes del envio
                        desactivarCampos()
                    },
                    success: function(rpta){
                        if (ZCRespuestaConError(rpta)) {
                            // Muestra mensaje de error
                            ZCAsignarErrores(rpta);
                        } else {
                            // Carga el listado cmostrando el registro insertado
                            window.location.assign(URLControlador + 'listar/');
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
