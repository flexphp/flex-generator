public function {_nombreAccion_}() {
        // Valida que el usuario este logueado
        $this->validarSesion();
        if (!$this->input->is_ajax_request()) {
            // No es un llamado valido (no es desde un Ajax, sino por la url)
            redirect('404');
        }
{_asignacionCliente_}
        // Error por defecto
        $rpta['error'] = 'Error, datos inesperados';
        if ($datos['accion'] == '{_nombreAccion_}') {
            // Hace el llamado al WS {_nombreAccion_}
            $rpta = $this->modelo->{_nombreAccion_}($datos);
{_comandoEspecial_}
        }
        // Permite manejar o no Cache en la pagina
        $this->output->set_output(json_encode($rpta));
    }
