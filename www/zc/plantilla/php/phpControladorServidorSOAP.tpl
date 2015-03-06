<?php

// Solo muestra errores fatales
// Necesario para devolver los datos correctos en la respuesta del servidor
error_reporting(1);

class {_nombreControlador_} extends CI_Controller{
    function {_nombreControlador_}(){
        parent::__construct();

        // Libreria para el manejode WS
        $this->load->library('nusoap');
        /**
         * Clase para el manejo de Servidor WS
         */
        $this->_miURL = '';
        $this->_SRV_WS = new soap_server();
        $this->_SRV_WS->configureWSDL('{_nombreControlador_}', $this->_miURL);
        $this->_SRV_WS->wsdl->schemaTargetNamespace = $this->_miURL;
        $this->_SRV_WS->wsdl->setDebugLevel(9);
     }

    {_accionesServidorWS_}
}