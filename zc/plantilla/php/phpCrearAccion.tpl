    /**
     * Ejecuta la accion del boton {_nombreAccion_}
     * @param array $datos
     * @return array respuesta del servidor WS
     */
    function {_nombreAccion_}Cliente($datos){
        $tiempo_inicio = microtime(true);

        /**
         * Variable a regresar
         */
        $return = array();

        $server = base_url() . 'index.php/';
        $serverURL = $server . '{_servidorAccion_}/';
        $serverScript = '{_nombreAccion_}';
        $metodoALlamar = '{_nombreAccion_}Servidor';

        /**
         * Parametros de la funcion a llamar
         */
        $Parametros = array(
            {_asignacionCliente_}
        );

        $_CLI_WS = new nusoap_client($serverURL . $serverScript . '?wsdl', 'wsdl');

        $error = $_CLI_WS->getError();
        if ($error) {
            $return['errorWS'] = $error;
        }
        {_comandoEspecial_}
        /**
         * Llamado a la funcion {_nombreAccion_} en el servidor
         */
        $_rpta = $_CLI_WS->call(
            $metodoALlamar, // Funcion a llamar
            $Parametros, // Parametros pasados a la funcion
            "uri:{$serverURL}{$serverScript}", // namespace
            "uri:{$serverURL}{$serverScript}/$metodoALlamar"       // SOAPAction
        );

        // Verificacion que los parametros estan bien, y si lo estan devolver respuesta.
        if ($_CLI_WS->fault) {
            $return['errorWS'] = $_rpta['faultstring'];
        } else {
            $error = $_CLI_WS->getError();
            if ($error) {
                $return['errorWS'] = $error;
            } else {
                $return['rptaWS'] = $_rpta;
            }
        }
        $tiempo_fin = microtime(true);
        $return['timeWS'] = ($tiempo_fin - $tiempo_inicio);
        $return['metodoWS'] = $metodoALlamar;
        return $this->zc->procesarRespuestaWS($return);
    }