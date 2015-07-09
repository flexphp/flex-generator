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
        'vista' => '{_nombreVista_}',
        'navegacion' => '',
    );

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->_data['navegacion'] = $this->load->view('{_paginaNavegacion_}.html', null, true);
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
        // Valida que el usuario este logueado
        $this->validarSesion();
        $this->load->view($this->_data['vista'], $this->_data);
    }

    /**
     * Validar que el usuario este en sesion
     */
    public function validarSesion() {
        if ($this->session->userdata('zc_logueado') !== true) {
            // No esta logueado, pide iniciar sesion
            redirect('{_paginaLogin_}');
        } 
    } 
}