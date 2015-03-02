function {_nombreAccion_}(){
    	/**
	 * Definir los tipo de variables que se devolveran en la repuesta
	 */
	$this->_SRV_WS->wsdl->addComplexType(
	    '{_nombreAccion_}Respta', 
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

	/**
	 * Definir la manera de devolver el resultado
	 */
	$this->_SRV_WS->wsdl->addComplexType(
	    '{_nombreAccion_}ResptaArray', 
	    'complexType', 
	    'array', 
	    '', 
	    'SOAP-ENC:Array', 
	    array(), 
	    array(
	        array(
	            'ref' => 'SOAP-ENC:arrayType',
	            'wsdl:arrayType' => 'tns:{_nombreAccion_}Respta[]'
	        )
	    ),
	    'tns:{_nombreAccion_}Respta'
	);

	/**
	 * Parametros que recibe la funcion
	 */
	${_nombreAccion_}Parametros = array(
	        {_asignacionCliente_}
	);

	/**
	 * Definir el tipo de respuesta que devuelve el servidor
	 */
	${_nombreAccion_}Returns = array(
	    'return' => 'tns:{_nombreAccion_}ResptaArray'
	);

	/**
	 * Registrar la funcion en el servidor
	 */
	$this->_SRV_WS->register(
	        '{_nombreFuncion_}', // Nombre de la funcion
	        ${_nombreAccion_}Parametros, // Parametros de entrada
	        ${_nombreAccion_}Returns, // Parametros de salida
	        $this->_miURL
	);

	function {_nombreFuncion_}({_asignacionFuncion_}){
	    file_put_contents(getcwd() . '/application/logs/{_nombreControlador_}_' . date('Ymd') . '.log', 'Parametros WSS ' . __FUNCTION__ . print_r(get_defined_vars(), 1) . "\n", FILE_APPEND);
	    // Incializacion de variables de respuesta
	    $Resultado[0] = array(
	        'cta' => 0,
	        'infoEncabezado' => '',
	        'error' => '',
	    );
	    $i = 0;

	    $resultado = '';
	    $row = $resultado;
	    $cta = 1;
					
            if(is_array($row)){
                $Resultado[$i]['infoEncabezado'] = json_encode($row);
            } else {
                $Resultado[$i]['infoEncabezado'] = $row;
            }
            $Resultado[0]['cta'] = $cta;

            file_put_contents(getcwd() . '/application/logs/{_nombreControlador_}_' . date('Ymd') . '.log', 'Resultado: ' . print_r($Resultado, 1) . "\n", FILE_APPEND);

	    return new soapval('return', 'tns:{_nombreAccion_}ResptaArray', $Resultado);
	}

	$HTTP_RAW_POST_DATA = file_get_contents('php://input');
	file_put_contents(getcwd() . '/application/logs/{_nombreControlador_}_' . date('Ymd') . '.log', 'Argumentos WSS ' . $HTTP_RAW_POST_DATA . "\n", FILE_APPEND);
	$this->_SRV_WS->service($HTTP_RAW_POST_DATA);
    }