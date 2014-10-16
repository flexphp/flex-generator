<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class nusoap {

    function nusoap() {
        require_once APPPATH . 'libraries/nusoap/nusoap' . EXT;
    }

}
