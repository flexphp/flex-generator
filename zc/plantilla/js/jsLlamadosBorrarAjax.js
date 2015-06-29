        // Definicion de la accion {_nombreAccion_}
        if(nombreAccion === '{_nombreAccion_}') {
            // Valida que exista id a borrar
            var id = ZCAccionCondicion('{_idFormulario_}');
            if(id == ''){
                $('#error-{_idFormulario_}').text('Error durante la accion');
                $('.alert-danger').show();
            }else{
                $.ajax({
                    url: $('#URLProyecto').val()+'index.php/{_nombreControlador_}/{_nombreAccion_}/',
                    type: 'POST',
                    dataType: 'JSON',
                    // Envia los campos y el id del registro a actualizar
                    data: $('#{_idFormulario_}').serialize()+'&id='+id+'&accion='+nombreAccion,
                    beforeSend: function(){
                        // Inactivar el boton, solo permite un envio a la vez
                        $('#'+nombreAccion).addClass('disabled').prop('disabled', true);
                        // Oculta ventana con mensajes
                        $('.alert').hide();
                        // Limpia resultados anteriores
                        $('#listado-{_idFormulario_}').html('');
                        // Mostrar cargando
                        $('#'+nombreAccion+' span').addClass('glyphicon-refresh glyphicon-refresh-animate');
                    },
                    success: function(rpta){
                        if(rpta.error !== undefined &&  Object.keys(rpta.error).length > 0){
                            // Muestra mensaje de error
                            ZCAsignarErrores('{_idFormulario_}', rpta);
                            $('.alert-danger').show();
                        }else{
                            // Carga el listado cmostrando el registro insertado
                            window.location.assign($('#URLProyecto').val()+'index.php/{_nombreControlador_}/listar/');
                        }
                    },
                    complete: function(){
                        // Activar el boton cuando se completa la accion, con error o sin error
                        $('#'+nombreAccion).removeClass('disabled').prop('disabled', false);
                        // Ocultar cargando
                        $('#'+nombreAccion+' span').addClass('glyphicon-refresh glyphicon-refresh-animate');
                    },
                    error: function(rpta){
                        $('#error-{_idFormulario_}').text('Error en el servicio');
                        $('.alert-danger').show();
                    }
                });
            }
        }