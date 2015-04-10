<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class {_nombreControlador_} extends CI_Controller {

    /**
     * Datos de configuracion del controlador
     * @var array
     */
    private $_data = array(
        'formulario' => '{_nombreFormulario_}',
        'vista' => '{_nombreVista_}',
        'navegacion' => '',
    );

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->_data['navegacion'] = $this->load->view('navegacion.html', null, true);
    }

    /**
     * Accion por defecto
     */
    public function index() {
        $this->inicio();
    }

    /**
     * Pagina de bienvenida
     */
    public function inicio() {
        $this->load->view($this->_data['vista'], $this->_data);
    }

}
