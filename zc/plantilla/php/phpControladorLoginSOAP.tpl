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
        'modelo' => '{_nombreModelo_}',
        'vista' => '{_nombreVista_}',
        'controlador' => '{_nombreControlador_}',
        'id' => '',
        'paginaActual' => '',
        'navegacion' => '',
    );

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model($this->_data['modelo']);
    }

    /**
     * Accion por defecto
     */
    public function index() {
        $this->_data['vista'] = '{_nombreVista_}';
        $this->load->view($this->_data['vista']);
    }

{_accionServidor_}

}
