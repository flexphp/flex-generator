        // Definicion de la accion {_nombreAccion_}
        if(nombreAccion === '{_nombreAccion_}' && $('#{_idFormulario_}').parsley().validate()) {
            $.ajax({
                // A la accion se le concatena la palabra cliente, asi se llama en la funcion
                url: URLControlador + '{_nombreAccion_}/',
                type: 'POST',
                dataType: 'JSON',
                data: $('#{_idFormulario_}').serialize()+'&accion='+nombreAccion,
                beforeSend: function(){
                    // Inactivar los campos par evitar modificaciones
                    desactivarCampos();
                    // $('#'+nombreAccion).addClass('disabled').prop('disabled', true);
                    // Oculta ventana con mensajes
                    // $('.alert').hide();
                    // Mostrar cargando
                    // $('#'+nombreAccion+' span').addClass('glyphicon-refresh glyphicon-refresh-animate');
                },
                success: function(rpta){
                    if (rpta.error !== undefined || (typeof rpta.error === 'object' && Object.keys(rpta.error).length > 0)) {
                        // Muestra mensaje de error
                        ZCAsignarErrores('{_idFormulario_}', rpta); 
                        $('.alert-danger').show();
                        // Limpia el valor de las contrasenas
                        $(':password').val('');
                    }else{
                        window.location.assign('inicio');
                    }
                },
                complete: function(){
                    // Activar los campos cuando se completa la solicitud, con error o sin error
                    activarCampos();
                    // $('#'+nombreAccion).removeClass('disabled').prop('disabled', false);
                    // Ocultar cargando
                    // $('#'+nombreAccion+' span').removeClass('glyphicon-refresh glyphicon-refresh-animate');
                },
                error: function(rpta){
                    $('#error-{_idFormulario_}').text('Error en el servicio');
                    $('.alert-danger').show();
                }
            });
        }
