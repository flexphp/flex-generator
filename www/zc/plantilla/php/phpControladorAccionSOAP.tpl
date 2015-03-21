public function {_nombreAccion_}() {
        {_asignacionCliente_}
        if (!isset($datos['accion']) || !$datos['accion']) {
            // No es un llamado valido (no es desde un Ajax, sino por la url)
            header('Location: ../index.php');
        } else if (isset($rpta['error']) && '' != $rpta['error']) {
            // Pasa al final
        } else if ($datos['accion'] == '{_nombreAccion_}') {
            $rpta = $this->{_nombreModelo_}->{_nombreAccion_}Cliente($datos);
        } else if (isset($datos['accion'])) {
            $rpta['error'] = 'Error, datos inesperados';
        }
        echo json_encode($rpta, true);
    }