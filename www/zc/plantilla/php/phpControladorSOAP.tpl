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
        $this->load->view('{_nombreVista_}');
    }

{_accionServidor_}

}
