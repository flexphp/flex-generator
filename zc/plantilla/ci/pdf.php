<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'libraries/fpdf17/fpdf.php';

class pdf extends FPDF {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Encabezado de cada una de las hojas creadas
     */
    function Header(){
        $this->SetLeftMargin(25);
        $this->Ln(6);
    }

    /**
     * Pie de pagina para cada uno de las hojas del documento
     */
    function Footer(){
        $this->SetY(-25);
        $this->SetX(15);
    }

    /**
     * Transforma el archivo a base 64 para poder enviarlo por el WS
     * @param $ruta string Ruta hacia a el archivo PDF creado
     */
    function pdf2base64($archivoBase64) {
        return base64_encode($archivoBase64);
    }
}