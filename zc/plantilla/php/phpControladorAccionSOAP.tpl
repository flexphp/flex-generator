public function {_nombreAccion_}() {
        // Valida que el usuario este logueado
        $this->validarSesion();
        
        if (!$this->input->is_ajax_request()) {
            // No es un llamado valido (no es desde un Ajax, sino por la url)
            redirect('404');
        }
        {_asignacionCliente_}
        if (isset($rpta['error']) && '' != $rpta['error']) {
            // Pasa al final
        } else if ($datos['accion'] == '{_nombreAccion_}') {
            $rpta = $this->{_nombreModelo_}->{_nombreAccion_}Cliente($datos);
            {_comandoEspecial_}
        } else if (isset($datos['accion'])) {
            $rpta['error'] = 'Error, datos inesperados';
        }
        echo json_encode($rpta, true);
    }