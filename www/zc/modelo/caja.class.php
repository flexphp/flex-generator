<?php

/**
 * Clase base para la creacion de elemento HTML
 */
require_once 'elementos.class.php';

/**
 * Crea cajas texto
 */
class caja extends elementos{

    /**
     * Tipo de datos que recibira el campo, ver AZ_DATO_*
     * @var string
     */
    public $_tipo = '';

    /**
     * Mensaje mostrado al cliente si el campo no es del tipo esperado
     * @var string
     */
    public $_msjTipo = '';

    /**
     * Contrucutor de la caja de texto, define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     * @throws Exception
     */
    function __construct($caracteristicas) {
        parent::__construct($caracteristicas);
        $this->obligatorio($this->_prop[ZC_OBLIGATORIO], $this->_prop[ZC_OBLIGATORIO_ERROR]);
        $this->tipo($this->_prop[ZC_DATO], $this->_prop[ZC_DATO_ERROR]);
        $this->longitud($this->_prop[ZC_LONGITUD_MINIMA], $this->_prop[ZC_LONGITUD_MAXIMA], $this->_prop[ZC_LONGITUD_MINIMA_ERROR], $this->_prop[ZC_LONGITUD_MAXIMA_ERROR]);
    }

    /**
     * Crear y define el elemento HTML a devolver
     * El estilo de creacion permite crear dos columnas para la recoleecion de datos
     * Cada una inicia con una columna en blanco (margen) izquierdo
     * 5 columnas repartidas con 2 para la etiqueta del elemento y 3 para la forma de ignreso
     * 5 columnas repartidas con 2 para la etiqueta del elemento y 3 para la forma de ignreso
     * Cada una inicia con una columna en blanco (margen) derecho
     */
    function crear() {
        $this->_html = "
            <div class='row'>
                <div class='col-md-1'></div>
                <div class='col-md-2 text-right'>
                    <label for='{$this->_id}'>{$this->_etiqueta}{$this->_signoObligatorio}</label>
                </div>
                <div class='col-md-3'>
                    <input" .
                " type='text'" .
                " class='form-control'" .
                // Identificador
                " id='{$this->_id}'" .
                " name='{$this->_id}'" .
                // Validacion obligatorio
                " {$this->_obligatorio}" .
                " {$this->_msjObligatorio}" .
                // Validacion tipo de dato
                " {$this->_tipo}" .
                " {$this->_msjTipo}" .
                // Validacion longitudes
                " {$this->_longitud}" .
                " {$this->_msjLongitud}" .
                // Ayuda visual
                " data-placement='{$this->_posicionTitle}'" .
                " data-toggle='tooltip'" .
                " data-original-title='{$this->_etiqueta}'" .
                "/>
                    <span class='help-block'></span>
                </div>
                <div class='col-md-5'></div>
                <div class='col-md-1'></div>
            </div>
        ";
    }

    /**
     * Defien el tipo de validacion segun el dato a recibir, la validacion se
     * hace a nivel del cliente, la validacion se hace con expresiones regulares
     * @param string $tipo
     */
    private function tipo($tipo, $msjTipo = '') {
        $this->_msjTipo = ('' != $msjTipo) ? $msjTipo : ZC_DATO_ERROR_PREDETERMINADO;
        switch ($tipo) {
            case ZC_DATO_NUMERICO:
                $this->_tipo = "data-parsley-type='digits'";
                $this->_msjTipo = "data-parsley-type-message='Debe ser numero: {$this->_msjTipo}'";
                break;
            case ZC_DATO_FECHA:
                $formato = 'DD/MM/YYYY';
                $formatoRegExp = "^(?:(?:0?[1-9]|1\d|2[0-8])(\/|-)(?:0?[1-9]|1[0-2]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:31(\/|-)(?:0?[13578]|1[02]))|(?:(?:29|30)(\/|-)(?:0?[1,3-9]|1[0-2])))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(29(\/|-)0?2)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$";
                $this->_tipo = "data-parsley-type='$formatoRegExp'";
                $this->_msjTipo = "data-parsley-type-message='Debe ser fecha ($formato): {$this->_msjTipo}'";
                break;
            case ZC_DATO_EMAIL:
                $this->_tipo = "data-parsley-type='email'";
                $this->_msjTipo = "data-parsley-type-message='Debe ser correo: {$this->_msjTipo}'";
                break;
            case ZC_DATO_URL:
                $this->_tipo = "data-parsley-type='url'";
                $this->_msjTipo = "data-parsley-type-message='Debe ser url: {$this->_msjTipo}'";
                break;
            case ZC_DATO_ALFANUMERICO:
            default:
                $this->_tipo = '';
                $this->_msjTipo = '';
                break;
        }
    }    
}
