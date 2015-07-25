<?php

/**
 * Crea cajas texto
 */
class caja extends Aelemento {

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
     * Formato de fecha utilizado
     * @var string
     */
    private $_formatoFecha = 'YYYY-MM-DD';
    
    /**
     * Formato de fecha hora utilizado
     * @var string
     */
    private $_formatoFechaHora = 'YYYY-MM-DD HH:mm:ss';
    
    /**
     * Formato de hora utilizado
     * @var string
     */
    private $_formatoHora = 'HH:mm:ss';

    /**
     * Contrucutor de la caja de texto, define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     * @throws Exception
     */
    function __construct($caracteristicas) {
        parent::__construct($caracteristicas);
        $this->obligatorio($this->_prop[ZC_OBLIGATORIO], $this->_prop[ZC_OBLIGATORIO_ERROR]);
        $this->longitud($this->_prop[ZC_LONGITUD_MINIMA], $this->_prop[ZC_LONGITUD_MAXIMA], $this->_prop[ZC_LONGITUD_MINIMA_ERROR], $this->_prop[ZC_LONGITUD_MAXIMA_ERROR]);
        $this->tipo($this->_prop[ZC_DATO], $this->_prop[ZC_DATO_ERROR]);
        $this->autofoco($this->_prop[ZC_AUTOFOCO]);
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
        switch ($this->_prop[ZC_DATO]) {
            case ZC_DATO_FECHA:
                $this->crearFecha();
                break;
            case ZC_DATO_FECHA_HORA:
                $this->crearFechaHora();
                break;
            case ZC_DATO_HORA:
                $this->crearHora();
                break;
            case ZC_DATO_CONTRASENA:
                $this->crearContrasena();
                break;
            case ZC_DATO_AREA_TEXTO:
                $this->crearAreaDeTexto();
                break;
            case ZC_DATO_NUMERICO:
            case ZC_DATO_EMAIL:
            case ZC_DATO_URL:
            case ZC_DATO_TEXTO:
            default:
                $this->crearCaja();
                break;
        }
        return $this;
    }

    /**
     * Define el tipo de validacion segun el dato a recibir, la validacion se
     * hace a nivel del cliente, la validacion se hace con expresiones regulares
     * @param string $tipo
     */
    private function tipo($tipo, $msjTipo = '') {
        $this->_msjTipo = ('' != $msjTipo) ? $msjTipo : ZC_DATO_ERROR_PREDETERMINADO;
        switch ($tipo) {
            case ZC_DATO_NUMERICO:
                $this->_tipo = "data-parsley-type='digits'";
                $this->_msjTipo = "data-parsley-type-message='{$this->_msjTipo}'";
                break;
            case ZC_DATO_FECHA:
                $formatoRegExp = "^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$";
                $this->_tipo = "data-parsley-pattern='{$formatoRegExp}'";
                // $this->_msjTipo = "data-parsley-pattern-message='({$this->_formatoFecha}): {$this->_msjTipo}'";
                $this->_msjTipo = "data-parsley-pattern-message='{$this->_msjTipo}'";
                break;
            case ZC_DATO_FECHA_HORA:
                $formatoRegExp = "(\d{2}|\d{4})(?:\-)?([0]{1}\d{1}|[1]{1}[0-2]{1})(?:\-)?([0-2]{1}\d{1}|[3]{1}[0-1]{1})(?:\s)?([0-1]{1}\d{1}|[2]{1}[0-3]{1})(?::)?([0-5]{1}\d{1})(?::)?([0-5]{1}\d{1})";
                $this->_tipo = "data-parsley-pattern='{$formatoRegExp}'";
                // $this->_msjTipo = "data-parsley-pattern-message='({$this->_formatoFechaHora}): {$this->_msjTipo}'";
                $this->_msjTipo = "data-parsley-pattern-message='{$this->_msjTipo}'";
                break;
            case ZC_DATO_HORA:
                $formatoRegExp = "^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$";
                $this->_tipo = "data-parsley-pattern='{$formatoRegExp}'";
                $this->_msjTipo = "data-parsley-pattern-message='({$this->_formatoHora}): {$this->_msjTipo}'";
                break;
            case ZC_DATO_EMAIL:
                $this->_tipo = "data-parsley-type='email'";
                $this->_msjTipo = "data-parsley-type-message='{$this->_msjTipo}'";
                break;
            case ZC_DATO_URL:
                $this->_tipo = "data-parsley-type='url'";
                $this->_msjTipo = "data-parsley-type-message='{$this->_msjTipo}'";
                break;
            case ZC_DATO_TEXTO:
            default:
                $this->_tipo = '';
                $this->_msjTipo = '';
                break;
        }
    }

    /**
     * Crear cajas que no requiren inconos especiales
     */
    private function crearCaja() {
        $this->_html = "<input" .
                " type='text'" .
                " class='form-control'" .
                " autocomplete='off'" .
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
                // Autofoco
                " {$this->_autofoco}" .
                // Ayuda visual
                $this->ayuda() .
                "/>";
        return $this;
    }

    /**
     * Crea un elemento con el formato de fecha, aplica datapicker
     */
    private function crearFecha() {
        $this->_html = "<div class='input-group date zc-caja-fecha' id='fecha-{$this->_id}'>".
                "<input" .
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
                // Autofoco
                " {$this->_autofoco}" .
                // Ayuda visual
                $this->ayuda() .
                "/>" .
                "<span class='input-group-addon'>" .
                "<span class='glyphicon glyphicon-calendar'></span>" .
                "</span>" .
                "</div>";
        return $this;
    }
    
    /**
     * Crea un elemento con el formato de fecha hora, aplica datapicker
     */
    private function crearFechaHora() {
        $this->_html = "<div class='input-group date zc-caja-fecha-hora' id='fecha-hora-{$this->_id}'>
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
                // Autofoco
                " {$this->_autofoco}" .
                // Ayuda visual
                $this->ayuda() .
                "/> " .
                "<span class='input-group-addon'>" .
                "<span class='glyphicon glyphicon-calendar'></span>" .
                "</span>" .
                "</div>";
        return $this;
    }
    
    /**
     * Crea un elemento con el formato de hora, aplica datapicker
     */
    private function crearHora() {
        $this->_html = "<div class='input-group date zc-caja-hora' id='hora-{$this->_id}'>
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
                // Autofoco
                " {$this->_autofoco}" .
                // Ayuda visual
                $this->ayuda() .
                "/>" .
                "<span class='input-group-addon'>" .
                "<span class=\"glyphicon glyphicon-time\"></span>" .
                "</span>" .
                "</div>";
        return $this;
    }

    /**
     * Crea un elemento con el formato contrasena
     */
    private function crearContrasena() {
        $this->_html = "<input" .
                " type='password'" .
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
                // Autofoco
                " {$this->_autofoco}" .
                // Ayuda visual
                $this->ayuda() .
                "/>";
        if ($this->_prop[ZC_LONGITUD_MAXIMA] !== -1) {
            // No es de la pantalla login
            $this->_html .= "<input" .
                    " type='password'" .
                    " class='form-control'" .
                    " placeholder='Confirmación {$this->_etiqueta}'" .
                    // Identificador
                    " id='x{$this->_id}'" .
                    " name='x{$this->_id}'" .
                    // Debe ser igual al otro campo
                    " data-parsley-validate-if-empty" .
                    " data-parsley-equalto='#{$this->_id}'" .
                    " data-parsley-equalto-message='La confirmación no coincide'" .
                    // Ayuda visual
                    $this->ayuda('Confirmación ' . $this->_etiqueta) .
                    "/>";
        }
        return $this;
    }

    /**
     * Crea un elemento tipo textarea
     */
    private function crearAreaDeTexto() {
        $this->_html = "<textarea" .
                " rows='3'" .
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
                // Autofoco
                " {$this->_autofoco}" .
                // Ayuda visual
                $this->ayuda() .
                "/>" .
                " </textarea>";
        return $this;
    }
    
}
