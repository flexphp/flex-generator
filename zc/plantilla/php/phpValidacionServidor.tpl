function {_nombreValidacion_}($dato){
        $rpta['error'] = array();
        $validarDato = true;

        // Los campos a validar deben llegar en forma de arreglo
        if(!is_array($dato)){
            $rpta['error'] = 'Error durante la validacion.';
            return $rpta;
        }

        // Algunas acciones no necesitan validacion datos
        if(in_array($dato['accion'], explode(',', '{_accionesSinValidacion_}'))){
            // Pasa automaticamente las validaciones de:
            // obligatorio
            // longitud (minima y maxima)
            // Tipo de dato, solo para los email y url, pueden ingresa solo una parte
            $validarDato = false;
        }

        {_elementosFormulario_}

        return $rpta;
    }