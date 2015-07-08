        // Definicion de la accion {_nombreAccion_}
        if(nombreAccion === '{_nombreAccion_}' && $('#{_idFormulario_}').parsley().validate()) {
            var miURL = $('#URLProyecto').val()+'index.php/{_nombreControlador_}/{_nombreAccion_}/1';
            ZCAccionPaginar(miURL, '{_idFormulario_}');
        }
