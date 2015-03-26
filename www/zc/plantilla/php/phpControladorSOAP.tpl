<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class {_nombreControlador_} extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('{_nombreModelo_}');
        $this->load->library('pagination');
    }

    /**
     * Accion por defecto
     */
    public function index() {
        $this->listar();
    }

    /**
     * Pagina los resultados mostrados en la consulta
     */
    public function pagina($cta = 0) {
        $config['base_url'] = base_url() . '/index.php/{_nombreControlador_}/listar/pagina/';
        $config['total_rows'] = $cta;

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }


    /**
     * Formulario de busqueda para la tabla {_nombreControlador_}
     */
    public function listar() {
        // Si se pasa el numero de pagina no se tiene en cuenta
        $data['busquedaPredefinida'] = (is_numeric($this->uri->segment(3)))? 'id|?|=|?|'.$this->uri->segment(3) : '';
        $data['paginaActual'] = ($this->uri->segment(3) === 'pagina')? $this->uri->segment(4) : 1;
        $this->load->view('{_nombreVistaListar_}', $data);
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
        $data['id'] = ($this->uri->segment(3) === FALSE) ? '' : $this->uri->segment(3);
        $this->load->view('{_nombreVista_}', $data);
    }

{_accionServidor_}

}
