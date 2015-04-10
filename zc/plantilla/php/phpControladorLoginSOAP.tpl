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
        $this->load->library('session');
        $this->load->model($this->_data['modelo']);
    }

    /**
     * Accion por defecto
     */
    public function index() {
        $this->_data['vista'] = '{_nombreVista_}';
        $this->load->view($this->_data['vista']);
    }

    public function loguear($rpta = array()) {
        // Asigna datos de session
        if (isset($rpta['infoEncabezado'][0]) && isset($rpta['cta']) && $rpta['cta'] > 0) {
            $session = $rpta['infoEncabezado'][0];
            $session['zc_logueado'] = true;
            $this->session->set_userdata($session);
        } else {
            // Reasigna el error devuelto por el webservice
            $rpta['error'] = 'Datos incorrectos, intentelo nuevamente';
        }
        return $rpta;
    }

    public function desloguear() {
        $this->session->sess_destroy();
        redirect('/{_nombreControlador_}', 'location');
    }

{_accionServidor_}

}
