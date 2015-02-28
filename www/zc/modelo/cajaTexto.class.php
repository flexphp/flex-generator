<?php

class cajaTexto {

    /**
     * Identificador unico del elemento dentro del formulario
     * @var string
     */
    public $_id;

    /**
     * Etiqueta que acompana la caja de texto, descripcion
     * @var string
     */
    public $_etiqueta;

    /**
     * Bandera para definir si es un camp obligatorio
     * @var string
     */
    public $_obligatorio = 'false';

    /**
     * Signo que identifica los campos obligatorios
     * @var string
     */
    public $_signoObligatorio = '';

    /**
     * Mensaje mostrado al cliente si el campo es obligatorio y no se ha diligenciado
     * @var string
     */
    public $_msjObligatorio = '';

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
     * Campo tipo html a mostrar en pantalla
     * @var string
     */
    private $_cajaTexto;

    /**
     * Contrucutor de la caja de texto, define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     * @throws Exception
     */
    function __construct($caracteristicas) {
        /**
         * Id del objeto dentro del formulario
         */
        if (!isset($caracteristicas[ZC_ID])) {
            throw new Exception(__FUNCTION__ . ': Es necesario el id para el objeto');
        } else {
            $this->_id = $caracteristicas[ZC_ID];
        }
        $this->_etiqueta = (!isset($caracteristicas[ZC_ELEMENTO_ETIQUETA])) ? $this->_id : $caracteristicas[ZC_ELEMENTO_ETIQUETA];
        if (isset($caracteristicas[ZC_OBLIGATORIO]) && $caracteristicas[ZC_OBLIGATORIO] == ZC_OBLIGATORIO_SI) {
            $this->_signoObligatorio = '*';
            $this->_obligatorio = 'true';
            $this->_msjObligatorio = (!isset($caracteristicas[ZC_OBLIGATORIO_ERROR])) ? ZC_OBLIGATORIO_ERROR_PREDETERMINADO : $caracteristicas[ZC_OBLIGATORIO_ERROR];
        }

        $this->tipo($caracteristicas[ZC_DATO]);
    }

    /**
     * Crear y define el elemento HTML a devolver
     */
    function crearCajaTexto() {
        $this->_cajaTexto = "
            <div class='row'>
                <div class='col-md-2'>
                    <label for='{$this->_id}'>{$this->_etiqueta}{$this->_signoObligatorio}</label>
                </div>
                <div class='col-md-3'>
                    <input" .
                " type='text'" .
                " class='form-control'" .
                " id='{$this->_id}'" .
                " name='{$this->_id}'" .
                " data-parsley-type='{$this->_tipo}'" .
                " data-parsley-type-message='{$this->_msjTipo}'" .
                " data-parsley-required='{$this->_obligatorio}'" .
                " data-parsley-required-message='{$this->_msjObligatorio}'" .
                "/>
                    <span class='help-block'></span>
                </div>
            </div>
        ";
    }

    /**
     * Defien el tipo de validacion segun el dato a recibir, la validacion se
     * hace a nivel del cliente, la validacion se hace con expresiones regulares
     * @param string $tipo
     */
    private function tipo($tipo) {
        switch ($tipo) {
            case ZC_DATO_NUMERICO:
                $this->_tipo = "digits";
                $this->_msjTipo = "Se esperan numeros";
                break;
            case ZC_DATO_FECHA:
                $formato = 'DD/MM/YYYY';
                $this->_tipo = "^(?:(?:0?[1-9]|1\d|2[0-8])(\/|-)(?:0?[1-9]|1[0-2]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:31(\/|-)(?:0?[13578]|1[02]))|(?:(?:29|30)(\/|-)(?:0?[1,3-9]|1[0-2])))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(29(\/|-)0?2)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$";
                $this->_msjTipo = "Se espera fecha ($formato)";
                break;
            case ZC_DATO_EMAIL:
                $this->_tipo = "email";
                $this->_msjTipo = "Se espera correo";
                break;
            case ZC_DATO_URL:
                $this->_tipo = "url";
                $this->_msjTipo = "Se espera solo texto";
                break;
            case ZC_DATO_ALFANUMERICO:
            default:
                $this->_tipo = "alphanum";
                $this->_msjTipo = "Dato no valido";
                break;
        }
    }

    /**
     * Muestra la caja de texto en pantalla
     */
    function imprimirCajaTexto() {
        echo $this->_cajaTexto;
    }

    /**
     * Retorna el codigo HTML creado de la caja de texto
     * @return string
     */
    function devolverCajaTexto() {
        return $this->_cajaTexto;
    }

}
