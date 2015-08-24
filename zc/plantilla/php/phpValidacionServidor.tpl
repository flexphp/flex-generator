function {_nombreValidacion_}($dato){
        if(!is_array($dato)){
            // Los campos a validar deben llegar en forma de arreglo
            $rpta['error'] = 'Error durante la validacion.';
            return $rpta;
        }

        $rpta['error'] = array();
        // Algunas acciones no necesitan validacion datos
        // Pasa automaticamente las validaciones de:
        if (!in_array($dato['accion'], explode(',', '{_accionesSinValidacion_}'))) {
            $this->zc->validarCampo($dato, $this->configuracionCampo());
        }

        if ($this->zc->cantidadErrores() > 0) {
            // Existen errores
            $rpta['error'] = $this->zc->devolverErrores();
        }
        return $rpta;
    }