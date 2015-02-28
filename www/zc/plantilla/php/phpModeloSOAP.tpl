<?php

class {_nombreModelo_} extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library("nusoap");
    }

    {_clienteWS_}
    {_procesarWS_}
    {_validacion_}
}
