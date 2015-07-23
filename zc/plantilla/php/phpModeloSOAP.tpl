<?php

class {_nombreModelo_} extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library(array('nusoap', 'session'));
        $this->load->library('zc', array(get_class()));
    }

{_llamadosModelo_}
    /**
     * Hace el llamado al ws, segun los parametros dados
     * @param string $login Nombre de usuario para loguearse en el sistema
     * @param string $clave Clave para logueo en el sistema
     * @param string $serverURL 
     * @param string $serverScript
     * @param string $metodoALlamar
     * @param array $parametros
     * @return array Respuesta del WS
     */
    function llamarWS($login, $clave, $serverURL, $serverScript, $metodoALlamar, $parametros) {
        return $this->zc->llamarWS($login, $clave, $serverURL, $serverScript, $metodoALlamar, $parametros);
    }
}