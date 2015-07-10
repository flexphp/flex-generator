<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Nusoap {

    function __construct() {
        /**
         * Define zona horaro utilizada por ala aplicacion
         */
        date_default_timezone_set('America/Bogota');
        require_once APPPATH . 'libraries/nusoap/Nusoap.php';
    }

}
