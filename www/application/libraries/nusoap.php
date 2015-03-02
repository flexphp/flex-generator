<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class nusoap {

    function nusoap() {
        /**
         * Define zona horaro utilizada por ala aplicacion
         */
        date_default_timezone_set('America/Bogota');
        require_once APPPATH . 'libraries/nusoap/nusoap' . EXT;
    }

}
