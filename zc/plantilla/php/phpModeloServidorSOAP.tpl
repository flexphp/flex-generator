function {_nombreAccion_}(){
        // Definir los tipo de variables que se devolveran en la repuesta
        $this->_SRV_WS->wsdl->addComplexType(
            '{_nombreAccion_}Rpta',
            'complexType',
            'struct',
            'all',
            '',
            array(
                'cta' => array(
                    'name' => 'cta',
                    'type' => 'xsd:string'
                ),
                'infoEncabezado' => array(
                    'name' => 'infoEncabezado',
                    'type' => 'xsd:string'
                ),
                'error' => array(
                    'name' => 'error',
                    'type' => 'xsd:string'
                )
            )
        );
        // Definir la manera de devolver el resultado
        $this->_SRV_WS->wsdl->addComplexType(
            '{_nombreAccion_}RptaArray',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(
                array(
                    'ref' => 'SOAP-ENC:arrayType',
                    'wsdl:arrayType' => 'tns:{_nombreAccion_}Rpta[]'
                )
            ),
            'tns:{_nombreAccion_}Rpta'
        );
        // Parametros que recibe la funcion
        ${_nombreAccion_}Parametros = array(
            {_asignacionCliente_}
        );
        // Definir el tipo de respuesta que devuelve el servidor
        ${_nombreAccion_}Returns = array(
            'return' => 'tns:{_nombreAccion_}RptaArray'
        );
        // Registrar la funcion en el servidor
        $this->_SRV_WS->register(
            '{_nombreFuncion_}', // Nombre de la funcion
            ${_nombreAccion_}Parametros, // Parametros de entrada
            ${_nombreAccion_}Returns, // Parametros de salida
            $this->_miURL,
            $this->_miSOAPACTION,
            $this->_miUSE,
            $this->_miSTYLE,
            'Servicio web creado para controlar la accion {_nombreAccion_} de {_nombreControlador_}.'
        );

        function {_nombreFuncion_}({_asignacionFuncion_}){
{_accionServidor_}
            // Existe error, devuelve el error
            $RptaWS['error'] = (isset($rpta['error'])) ? json_encode($rpta['error']) : '';
            // Si existe respuesta valida por parte del servidor
            $RptaWS['infoEncabezado'] = (isset($rpta['resultado'])) ? json_encode($rpta['resultado']) : '';
            $RptaWS['cta'] = (isset($rpta['cta'])) ? $rpta['cta'] : 0;
            
            file_put_contents(getcwd() . '/application/logs/ws_{_nombreControlador_}_' . date('Ymd') . '.log', __FUNCTION__ . "\n" . ' $data: ' . print_r(func_get_args(), 1) . "\n" . ' $RptaWS: ' . print_r($RptaWS, 1) . "\n", FILE_APPEND);
            // Es obligatorio devolver un array
            return new soapval('return', 'tns:{_nombreAccion_}RptaArray', array($RptaWS));
        }
        $this->_SRV_WS->service(file_get_contents('php://input'));
    }
