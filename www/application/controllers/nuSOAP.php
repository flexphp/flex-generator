<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class nuSOAP {

    function nuSOAP() {
        die(APPPATH . '-->' . EXT);
        require_once APPPATH . 'libraries/nuSOAP/nusoap' . EXT;
    }

}
