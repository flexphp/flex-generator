            /**
             * Definicion de la accion {_nombreAccion_}
             */
            if(nombreAccion === '{_nombreAccion_}') {
                {_inicializacionCliente_}
                $.ajax({
                    // A la accion se le concatena la palabra cliente, asi se llama en la funcion
                    url: $('#URLProyecto').val()+'index.php/{_nombreControlador_}/{_nombreAccion_}Cliente/',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $('#{_nombreFormulario_}').serialize()+'&accion='+nombreAccion,
                    success: function(rpta){
                        if(undefined != rpta.error && '' != rpta.error){
                            $('#{_nombreFormulario_}Error').text(rpta.error);
                        }else{
                            {_accionCliente_}
                        }
                    },
                    error: function(rpta){
                        $('#{_nombreFormulario_}Error').text('Error en el servicio');
                    }
                });
            }
