/**
     * Ejecuta la accion del boton {_nombreAccion_}
     * @param array $datos
     * @return array Respuesta del servidor WS
     */
    function {_nombreAccion_}Cliente($datos) {
        // Establece ubicacion del WS
        $server = base_url() . 'index.php/';
        $serverURL = $server . '{_servidorAccion_}/';
        $serverScript = '{_nombreAccion_}';
        $metodoALlamar = '{_nombreAccion_}Servidor';
        // Parametros recibidos por el WS a llamar
        $parametros = array(
            {_asignacionCliente_}
        );
        // Hace el llamado al WS
        return $this->llamarWS($this->session->userdata('Login'), $this->session->userdata('Clave'), $serverURL, $serverScript, $metodoALlamar, $parametros);
    }
