<?php

/**
 * Clase base para la creacion de elemenetos HTML
 * Su modificacion implica los tipos de dato
 * cajas (text)
 * listas (select)
 */
class elementos {

    /**
     * Propiedades del elemento, las conoce el usuarios
     * @var array
     */
    public $_prop = array();

    /**
     * Identificador unico del elemento dentro del formulario
     * @var string
     */
    protected $_id;

    /**
     * Etiqueta que acompana la caja de texto, descripcion
     * @var string
     */
    protected $_etiqueta;

    /**
     * Bandera para definir si es un camp obligatorio
     * @var string
     */
    protected $_obligatorio = '';

    /**
     * Posicion en la que se ubicara el mensaje de ayuda que se muestra al posicionar
     * el puntero sobre el elemento. Valores: right | left | top | bottom
     * @var string
     */
    protected $_posicionTitle = 'right';

    /**
     * Signo que identifica los campos obligatorios
     * @var string
     */
    protected $_signoObligatorio = '';

    /**
     * Mensaje mostrado al cliente si el campo es obligatorio y no se ha diligenciado
     * @var string
     */
    protected $_msjObligatorio = '';

    /**
     * Longitud (minima, maxima) del campo
     * @var string
     */
    protected $_longitud = '';

    /**
     * Mensaje mostrado al cliente si el campo no cumple con las longitudes esperadas
     * @var string
     */
    protected $_msjLongitud = '';

    /**
     * HTML creado durante el proceso
     * @var string
     */
    protected $_html;

    /**
     * Contrucutor de la caja de texto, define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     * @throws Exception
     */
    function __construct($caracteristicas) {
        /**
         * Para evitar errores con valores vacios se hace la validacion previa
         */
        $this->_prop = $caracteristicas;
        unset($caracteristicas);
        $this->verificar();
        $this->_id = $this->_prop[ZC_ID];
        $this->_etiqueta = $this->_prop[ZC_ETIQUETA];
    }

    /**
     * Verifica y establece valores predeterminados para cada uno de los elementos
     * @param mixed $this->_prop Argumentos pasados por el usuario para cada elemento
     * @throws Exception
     */
    protected function verificar() {
        /**
         * Id del objeto dentro del formulario
         */
        if (!isset($this->_prop[ZC_ID]) || '' == trim($this->_prop[ZC_ID]) || !is_string($this->_prop[ZC_ID])) {
            throw new Exception(__FUNCTION__ . ": El campo NO tiene un identificador valido [a-Z_].");
        }
        // Identificadores del elemento
        $this->_prop[ZC_ID] = strtolower(trim($this->_prop[ZC_ID]));
        $this->_prop[ZC_ETIQUETA] = (isset($this->_prop[ZC_ETIQUETA]) && '' != trim($this->_prop[ZC_ETIQUETA])) ? $this->_prop[ZC_ETIQUETA] : $this->_prop[ZC_ID];
        $this->_prop[ZC_ETIQUETA] = ucfirst(trim($this->_prop[ZC_ETIQUETA]));
        // Tipo Elemento
        $this->_prop[ZC_ELEMENTO] = (isset($this->_prop[ZC_ELEMENTO]) && '' != $this->_prop[ZC_ELEMENTO]) ? strtolower($this->_prop[ZC_ELEMENTO]) : null;

        /**
         * No todos los elementos necesitan todas las propedades, minimiza uso de memoria
         */
        if (in_array($this->_prop[ZC_ELEMENTO], array(ZC_ELEMENTO_CAJA_TEXTO, ZC_ELEMENTO_CHECKBOX, ZC_ELEMENTO_RADIO, ZC_ELEMENTO_SELECT))) {
            // Tipo de dato
            $this->_prop[ZC_DATO] = (isset($this->_prop[ZC_DATO]) && '' != $this->_prop[ZC_DATO]) ? $this->_prop[ZC_DATO] : null;
            $this->_prop[ZC_DATO_ERROR] = (isset($this->_prop[ZC_DATO_ERROR]) && '' != $this->_prop[ZC_DATO_ERROR]) ? $this->_prop[ZC_DATO_ERROR] : null;
            // Traduccion del tipo de datos escogido por el cliente para el servidor
            $this->_prop[ZC_DATO_WS] = $this->datoWS($this->_prop[ZC_ELEMENTO], $this->_prop[ZC_DATO]);
            // Dato obligatorio
            $this->_prop[ZC_OBLIGATORIO] = (isset($this->_prop[ZC_OBLIGATORIO])) ? $this->_prop[ZC_OBLIGATORIO] : null;
            $this->_prop[ZC_OBLIGATORIO_ERROR] = (isset($this->_prop[ZC_OBLIGATORIO_ERROR]) && '' != $this->_prop[ZC_OBLIGATORIO_ERROR]) ? $this->_prop[ZC_OBLIGATORIO_ERROR] : null;
            // La longitud debe ser numerica
            $this->_prop[ZC_LONGITUD_MINIMA] = (isset($this->_prop[ZC_LONGITUD_MINIMA]) && is_int((int) $this->_prop[ZC_LONGITUD_MINIMA])) ? $this->_prop[ZC_LONGITUD_MINIMA] : null;
            $this->_prop[ZC_LONGITUD_MINIMA_ERROR] = (isset($this->_prop[ZC_LONGITUD_MINIMA_ERROR]) && '' != $this->_prop[ZC_LONGITUD_MINIMA_ERROR]) ? $this->_prop[ZC_LONGITUD_MINIMA_ERROR] : null;
            // La longitud debe ser numerica
            $this->_prop[ZC_LONGITUD_MAXIMA] = (isset($this->_prop[ZC_LONGITUD_MAXIMA]) && is_int((int) $this->_prop[ZC_LONGITUD_MAXIMA])) ? $this->_prop[ZC_LONGITUD_MAXIMA] : null;
            $this->_prop[ZC_LONGITUD_MAXIMA_ERROR] = (isset($this->_prop[ZC_LONGITUD_MAXIMA_ERROR]) && '' != $this->_prop[ZC_LONGITUD_MAXIMA_ERROR]) ? $this->_prop[ZC_LONGITUD_MAXIMA_ERROR] : null;
            // La longitud de los campos predefinidos
            // Fecha en formato YYYY-MM-DD = 10
            $this->_prop[ZC_LONGITUD_MAXIMA] = ($this->_prop[ZC_DATO] == ZC_DATO_FECHA) ? 10 : $this->_prop[ZC_LONGITUD_MAXIMA];
            // Valida las longitudes
            if (isset($this->_prop[ZC_LONGITUD_MINIMA]) && isset($this->_prop[ZC_LONGITUD_MAXIMA]) && $this->_prop[ZC_LONGITUD_MINIMA] > $this->_prop[ZC_LONGITUD_MAXIMA]) {
                throw new Exception(__FUNCTION__ . ": El campo {$this->_prop[ZC_ETIQUETA]} tiene incoherencia en las longitudes.");
            }
            // Valida la longitud del campo, es obligatoria para las cajas
            if (isset($this->_prop[ZC_ELEMENTO]) && $this->_prop[ZC_ELEMENTO] == ZC_ELEMENTO_CAJA_TEXTO && !isset($this->_prop[ZC_LONGITUD_MAXIMA])) {
                throw new Exception(__FUNCTION__ . ": El campo {$this->_prop[ZC_ETIQUETA]} no tiene longitud maxima.");
            }
        }
        return $this;
    }

    /**
     * Defiene el tipo de dato que le corresponde a nivel de webservice, util para el SOAP
     * @param string $dato Tipo de dato definido por el cliente
     * @return string
     */
    private function datoWS($elemento, $dato) {
        $xsd = '';
        switch (true) {
            case ($dato == ZC_DATO_NUMERICO && $elemento != ZC_ELEMENTO_CHECKBOX):
                // Los checkbox se manejan como string, haciendo json_encode
                $xsd = 'int';
                break;
            case ($dato == ZC_DATO_TEXTO):
            case ($dato == ZC_DATO_AREA_TEXTO):
            case ($dato == ZC_DATO_EMAIL):
            case ($dato == ZC_DATO_URL):
            case ($dato == ZC_DATO_NUMERICO):
            case ($dato == ZC_DATO_CONTRASENA):
            case ($dato == ZC_DATO_FECHA):
                $xsd = 'string';
                break;
            default:
                // Botones y otros no definidos no se configura
                $xsd = null;
                break;
        }
        return $xsd;
    }

    /**
     * Determina si el campo es obligatorio, de ser asigna el signo de obligatoriedad y el mensaje de error
     * @param string $obligatorio Valor true|false o 1|0
     * @param string $error Mensaje de error definido por el usuario
     */
    protected function obligatorio($obligatorio, $error) {
        if (isset($obligatorio) && $obligatorio == ZC_OBLIGATORIO_SI) {
            $this->_signoObligatorio = '*';
            $this->_obligatorio = "data-parsley-required='true'";
            $this->_msjObligatorio = (isset($error)) ? "data-parsley-required-message='{$error}'" : "data-parsley-required-message='" . ZC_OBLIGATORIO_ERROR_PREDETERMINADO . "'";
        }
    }

    /**
     * Hace las validaciones de la longitud en el campo
     * @param string $minima Longitud minima del campo
     * @param string $maxima Longitud maxima del campo
     * @param string $errorMinima Error de la longitud minima del campo
     * @param string $errorMaxima Error de la longitud maxima del campo
     */
    protected function longitud($minima, $maxima, $errorMinima, $errorMaxima) {
        $errorMin = (isset($errorMinima)) ? $errorMinima : str_replace('&[Longitud]&', $minima, ZC_LONGITUD_MINIMA_ERROR_PREDETERMINADO);
        $errorMax = (isset($errorMaxima)) ? $errorMaxima : str_replace('&[Longitud]&', $maxima, ZC_LONGITUD_MAXIMA_ERROR_PREDETERMINADO);
        switch (true) {
            // Tiene longitud maxima y minima
            case isset($minima) && $minima > 0 && isset($maxima) && $maxima > 0:
                $this->_longitud = "data-parsley-length='[$minima,$maxima]'";
                $this->_msjLongitud = "data-parsley-length-message='Longitud esta entre ($minima,$maxima)'";
                break;
            // Tiene longitud minima
            case isset($minima) && $minima > 0:
                $this->_longitud = "data-parsley-minlength='$minima'";
                $this->_msjLongitud = "data-parsley-minlength-message='$errorMin'";
                break;
            // Tiene longitud maxima
            case isset($maxima) && $maxima > 0:
                $this->_longitud = "data-parsley-maxlength='$maxima'";
                $this->_msjLongitud = "data-parsley-maxlength-message='$errorMax'";
                break;
            // No se validan longitudes
            default:
                $this->_longitud = '';
                $this->_msjLongitud = '';
                break;
        }
    }

    /**
     * Construye el mensaje de ayuda mostrado en los campos
     * @param string $msj Mensaje de ayuda a mostrar, por defecto es la etiqueta del campo
     */
    protected function ayuda($msj = '') {
        $msj = ($msj == '') ? $this->_etiqueta : $msj;
        $html = " data-placement='{$this->_posicionTitle}'" .
                " data-toggle='tooltip'" .
                " data-original-title='{$msj}'";
        return $html;
    }

    protected function plantilla($campo) {
        $html = "
            <div class='row'>
                <div class='col-md-1'></div>
                <div class='col-md-2 text-right'>
                    <label for='{$this->_id}'>{$this->_etiqueta}{$this->_signoObligatorio}</label>
                </div>
                <div class='col-md-3'>
                    {$campo}
                </div>
                <div class='col-md-5'></div>
                <div class='col-md-1'></div>
            </div>
        ";
        return $html;
    }

    /**
     * Crear el elemento HTML
     * Es un metodo publico, se utiliza desde fuera de la clase, ver class formulario
     */
    public function crear() {

    }

    /**
     * Muestra el elemento en pantalla
     * Es un metodo publico, se utiliza desde fuera de la clase, ver class formulario
     */
    public function imprimir() {
        echo $this->_html;
    }

    /**
     * Retorna el codigo HTML del elemento con la plantilla div
     * Es un metodo publico, se utiliza desde fuera de la clase, ver class formulario
     * @return string
     */
    public function devolver() {
        return $this->plantilla($this->_html);
    }

    /**
     * Retorna el codigo HTML del elemento SIN la plantilla div
     * Es un metodo publico, se utiliza desde fuera de la clase, ver class buscar
     * @return string
     */
    public function devolverElemento() {
        return $this->_html;
    }

}