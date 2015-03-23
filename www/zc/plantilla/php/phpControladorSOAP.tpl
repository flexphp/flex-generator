<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class {_nombreControlador_} extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('{_nombreModelo_}');
    }

    /**
     * Accion por defecto
     */
    public function index() {
        $this->listar();
    }

    /**
     * Formulario de busqueda para la tabla {_nombreControlador_}
     */
    public function listar() {
        $data['busquedaPredefinida'] = ($this->uri->segment(3) === FALSE)? '' : 'id|?|=|?|'.$this->uri->segment(3);
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
