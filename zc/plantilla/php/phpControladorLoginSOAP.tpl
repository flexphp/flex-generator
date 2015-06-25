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
        'formulario' => '{_idFormulario_}',
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
        $this->enSesion();
        $this->load->view($this->_data['vista']);
    }
    
    /**
     * Si el usuario ya tiene session iniciada lo envia a la pagina de inicio
     */
    public function enSesion() {
        if ($this->session->userdata('zc_logueado') === true) {
            // Esta logueado en la aplicacion
            redirect('inicio');
        } 
    }

    /**
     * Se define ya que es necesario, por la misma contruccion del sistema
     * por favor no eliminar
     */
    public function validarSesion() {
        $this->enSesion();
    }

    /**
     * Crea la sesion para el usuario
     */
    public function loguear($rpta = array()) {
        if (count($rpta) == 0) {
            // No es un llamado valido (no es desde un Ajax, sino por la url)
            redirect('404');
        }
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

    /**
     * Termina la session para el usuario
     */
    public function desloguear() {
        $this->session->sess_destroy();
        redirect('/{_nombreControlador_}', 'location');
    }

{_accionServidor_}

}
