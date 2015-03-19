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

    public function index() {
        // Accion por defecto
        $this->listar();
    }
    
    public function listar() {
        $data['busquedaPredefinida'] = ($this->uri->segment(3) === FALSE)? '' : 'id|?|=|?|'.$this->uri->segment(3);
        $this->load->view('{_nombreVistaListar_}', $data);
    }
    
    public function agregar() {
        $this->load->view('{_nombreVista_}');
    }

{_accionServidor_}

}
