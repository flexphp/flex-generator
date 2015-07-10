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
        'vista' => '{_nombreVistaListar_}',
        'controlador' => '{_llamadoControlador_}',
        'id' => '',
        'paginaActual' => '',
        'navegacion' => '',
    );

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model($this->_data['modelo'], 'modelo');
        $this->load->library(array('session', 'pagination'));
        // Establece el menu de navegacion
        $this->_data['navegacion'] = $this->load->view('{_paginaNavegacion_}.html', null, true);
    }

    /**
     * Accion por defecto
     */
    public function index() {
        $this->listar();
    }

    /**
     * Agregar nuevo registro en la tabla {_nombreControlador_}
     */
    public function nuevo() {
        $this->editar();
    }

    /**
     * Editar un registro ya existente en la tabla {_nombreControlador_}
     */
    public function editar() {
        $this->validarSesion();
        $this->_data['vista'] = '{_nombreVista_}';
        $this->_data['id'] = ($this->uri->segment(3) === FALSE) ? '' : $this->uri->segment(3);
        $this->load->view($this->_data['vista'], $this->_data);
    }

    /**
     * Formulario de busqueda para la tabla {_nombreControlador_}
     */
    public function listar() {
        $this->validarSesion();
        $this->_data['vista'] = '{_nombreVistaListar_}';
        $this->_data['busquedaPredefinida'] = (is_numeric($this->uri->segment(3)))? $this->_data['controlador'] . '|?|id|?|=|?|'.$this->uri->segment(3) : '';
        $this->_data['paginaActual'] = ($this->uri->segment(3) === 'paginar')? $this->uri->segment(4) : '';
        $this->load->view($this->_data['vista'], $this->_data);
    }

    /**
     * Pagina los resultados mostrados en la consulta
     * @param int $cta Numero de registros devueltos por la consulta
     */
    public function paginar($cta = 0) {
        $config['base_url'] = base_url() . '/index.php/' . $this->_data['controlador'] . '/buscar/';
        $config['total_rows'] = $cta;
        $config['form'] = $this->_data['formulario'];
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
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

{_accionServidor_}

}