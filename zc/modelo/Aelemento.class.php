<?php

/**
 * Clase base para la creacion de elemenetos HTML
 * Su modificacion implica los tipos de dato
 * caja (text)
 * lista (select)
 * radio (radio)
 * checkbox (checkbox)
 */
abstract class Aelemento {

    /**
     * Propiedades del elemento, las conoce el usuarios
     * @var array
     */
    protected $_prop = array();

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
     * Posicion en la que se ubicara el mensaje de ayuda que se muestra al posicionar
     * el puntero sobre el elemento. Valores: right|left|top|bottom
     * @var string
     */
    protected $_posicionTitle = 'top';

    /**
     * Opcion de autofo sobre el elemento
     * @var string
     */
    protected $_autofoco = '';

    /**
     * HTML creado durante el proceso
     * @var string
     */
    protected $_html;

    /**
     * Constructor, define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     * @throws Exception
     */
    function __construct($caracteristicas) {
        /**
         * Para evitar errores con valores vacios se hace la validacion previa de
         * los datos recibidos
         */
        $this->_prop = $caracteristicas;
        unset($caracteristicas);
        $this->verificar();
        $this->_id = $this->_prop[ZC_ID];
        $this->_etiqueta = $this->_prop[ZC_ETIQUETA];
        $this->autofoco($this->_prop[ZC_AUTOFOCO]);
    }

    /**
     * Verifica y establece valores predeterminados para cada uno de los elementos
     * @param mixed $this->_prop Argumentos pasados por el usuario para cada elemento
     * @throws Exception
     */
    protected function verificar() {
        // Id del objeto dentro del formulario
        if (!isset($this->_prop[ZC_ID]) || '' == trim($this->_prop[ZC_ID]) || !is_string($this->_prop[ZC_ID])) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": El campo NO tiene un identificador valido [a-Z_].");
        }
        // Identificadores del elemento
        // Si existe el nombre de campo en la base de datos lo asigna, el id debe conincidir con el nombre de campo para el resto de la funcionalidad
        // Por eso se deja en minucula, igual al procesamiento en bd
        $this->_prop[ZC_ID] = (isset($this->_prop[ZC_CAMPO_BD]) && '' != $this->_prop[ZC_CAMPO_BD]) ? trim($this->_prop[ZC_CAMPO_BD]) : trim($this->_prop[ZC_ID]);
        $this->_prop[ZC_ETIQUETA] = (isset($this->_prop[ZC_ETIQUETA]) && '' != trim($this->_prop[ZC_ETIQUETA])) ? ucfirst(trim($this->_prop[ZC_ETIQUETA])) : ucfirst($this->_prop[ZC_ID]);
        // Tipo Elemento
        $this->_prop[ZC_ELEMENTO] = (isset($this->_prop[ZC_ELEMENTO]) && '' != $this->_prop[ZC_ELEMENTO]) ? strtolower($this->_prop[ZC_ELEMENTO]) : null;
        // Distribucion en medidas bootstrap de las etiquetas (labels) col-xs-X col-sm-X col-md-X col-lg-X
        $this->_prop[ZC_DISTRIBUCION_ETIQUETA] = (isset($this->_prop[ZC_DISTRIBUCION_ETIQUETA]) && '' != $this->_prop[ZC_DISTRIBUCION_ETIQUETA]) ? $this->_prop[ZC_DISTRIBUCION_ETIQUETA] : 'col-sm-4 col-md-3 col-lg-4';
        // Distribucion en medidas bootstrap de los elementos (input, select, etc) col-xs-X col-sm-X col-md-X col-lg-X
        $this->_prop[ZC_DISTRIBUCION_ELEMENTO] = (isset($this->_prop[ZC_DISTRIBUCION_ELEMENTO]) && '' != $this->_prop[ZC_DISTRIBUCION_ELEMENTO]) ? $this->_prop[ZC_DISTRIBUCION_ELEMENTO] : 'col-sm-8 col-md-9 col-lg-8';

        // No todos los elementos necesitan todas las propedades, minimiza uso de memoria
        if (in_array($this->_prop[ZC_ELEMENTO], array(ZC_ELEMENTO_CAJA, ZC_ELEMENTO_AREA, ZC_ELEMENTO_CHECKBOX, ZC_ELEMENTO_RADIO, ZC_ELEMENTO_LISTA))) {
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
            // Si no se define longitud maxima se asume una por defecto
            $tieneLongitudAsignada = (isset($this->_prop[ZC_LONGITUD_MAXIMA]) && is_int((int) $this->_prop[ZC_LONGITUD_MAXIMA])) ? true : false;
            $this->_prop[ZC_LONGITUD_MAXIMA] = ($tieneLongitudAsignada) ? $this->_prop[ZC_LONGITUD_MAXIMA] : ZC_LONGITUD_PREDETERMINADA;
            $this->_prop[ZC_LONGITUD_MAXIMA_ERROR] = (isset($this->_prop[ZC_LONGITUD_MAXIMA_ERROR]) && '' != $this->_prop[ZC_LONGITUD_MAXIMA_ERROR]) ? $this->_prop[ZC_LONGITUD_MAXIMA_ERROR] : null;
            // Los campos tipo numerico no tienen que no tienen longitud definida no se les agrega, esto para evitar validaciones de datos erroneas del lado cliente  y lado servidor
            $this->_prop[ZC_LONGITUD_MAXIMA] = ($this->_prop[ZC_DATO] == ZC_DATO_NUMERICO && !$tieneLongitudAsignada) ? null : $this->_prop[ZC_LONGITUD_MAXIMA];
            $this->_prop[ZC_LONGITUD_MAXIMA_ERROR] = ($this->_prop[ZC_DATO] == ZC_DATO_NUMERICO && !$tieneLongitudAsignada) ? null : $this->_prop[ZC_LONGITUD_MAXIMA_ERROR];
            // Valor predeterminado, se utiliza en base de datos
            $this->_prop[ZC_VALOR_PREDETERMINADO] = (isset($this->_prop[ZC_VALOR_PREDETERMINADO])) ? $this->_prop[ZC_VALOR_PREDETERMINADO] : null;
            // Campo donde se posiciona el puntero al cargar el formulario
            $this->_prop[ZC_AUTOFOCO] = (isset($this->_prop[ZC_AUTOFOCO]) ) ? $this->_prop[ZC_AUTOFOCO] : null;
            // La longitud de los campos predefinidos
            // Fecha en formato     YYYY-MM-DD          = 10
            // FechaHora en formato YYYY-MM-DD HH:mm:ss = 19
            // Fecha hora           HH:mm:ss            = 8
            $this->_prop[ZC_LONGITUD_MAXIMA] = ($this->_prop[ZC_DATO] == ZC_DATO_FECHA) ? 10 : $this->_prop[ZC_LONGITUD_MAXIMA];
            $this->_prop[ZC_LONGITUD_MAXIMA] = ($this->_prop[ZC_DATO] == ZC_DATO_FECHA_HORA) ? 19 : $this->_prop[ZC_LONGITUD_MAXIMA];
            $this->_prop[ZC_LONGITUD_MAXIMA] = ($this->_prop[ZC_DATO] == ZC_DATO_HORA) ? 8 : $this->_prop[ZC_LONGITUD_MAXIMA];
            // Valida las longitudes
            if (isset($this->_prop[ZC_LONGITUD_MINIMA]) && isset($this->_prop[ZC_LONGITUD_MAXIMA]) && $this->_prop[ZC_LONGITUD_MINIMA] > $this->_prop[ZC_LONGITUD_MAXIMA]) {
                mostrarErrorZC(__FILE__, __FUNCTION__, ": El campo {$this->_prop[ZC_ETIQUETA]} tiene incoherencia en las longitudes.");
            }
            // Valida la longitud del campo, es obligatoria para las cajas
            if (isset($this->_prop[ZC_ELEMENTO]) && in_array($this->_prop[ZC_ELEMENTO], array(ZC_ELEMENTO_CAJA, ZC_ELEMENTO_AREA)) && !isset($this->_prop[ZC_LONGITUD_MAXIMA]) && $this->_prop[ZC_DATO] != ZC_DATO_NUMERICO) {
                mostrarErrorZC(__FILE__, __FUNCTION__, ": El campo {$this->_prop[ZC_ETIQUETA]} no tiene longitud maxima.");
            }
            $this->_prop[ZC_ELEMENTO_OPCIONES] = (isset($this->_prop[ZC_ELEMENTO_OPCIONES])) ? $this->_prop[ZC_ELEMENTO_OPCIONES] : null;
            // Se puede incluir caracteres HTML y saltos de linea
            $this->_prop[ZC_MENSAJE_AYUDA] = (isset($this->_prop[ZC_MENSAJE_AYUDA]) && '' != trim($this->_prop[ZC_MENSAJE_AYUDA])) ? htmlspecialchars_decode($this->_prop[ZC_MENSAJE_AYUDA]) : null;
        }
        return $this;
    }

    /**
     * Define el tipo de dato que le corresponde a nivel de webservice, util para el SOAP
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
            case ($dato == ZC_DATO_EMAIL):
            case ($dato == ZC_DATO_URL):
            case ($dato == ZC_DATO_NUMERICO):
            case ($dato == ZC_DATO_CONTRASENA):
            case ($dato == ZC_DATO_FECHA):
            case ($dato == ZC_DATO_FECHA_HORA):
            case ($dato == ZC_DATO_HORA):
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
     * Agrega una descripcion al interior del elemento
     * @return string
     */
    protected function placeholder() {
        return (isset($this->_prop[ZC_PLACEHOLDER])) ? "placeholder='{$this->_prop[ZC_PLACEHOLDER]}'" : '';
    }

    /**
     * Construye el html para el autofoco del campo, esto permite posicinar el puntero
     * en el primer campo editable del formulario
     * @param string $autofoco Bandera para saber si se debe crear o no: true|false
     */
    protected function autofoco($autofoco = false) {
        $this->_autofoco = ($autofoco) ? 'autofocus=\'autofocus\'' : '';
        return $this;
    }

    /**
     * Construye el mensaje de ayuda mostrado en los campos
     * @param string $msj Mensaje de ayuda a mostrar
     * @return string
     */
    protected function ayuda($msj = '') {
        return (trim($msj) != '') 
            ? " rel='tooltip' data-html='true'" .
              " data-placement='{$this->_posicionTitle}'" .
              " data-toggle='tooltip'" .
              " data-original-title='{$msj}'"
            : '';
    }

    /**
     * Plantilla para la ventanas de agregar y editar informacion
     * @return string
     */
    protected function plantilla() {
        $html = tabular("<div class='form-group'>", 20);
        $html .= tabular($this->devolverLabel($this->_prop[ZC_DISTRIBUCION_ETIQUETA]), 24);
        $html .= tabular("<div class='" . $this->_prop[ZC_DISTRIBUCION_ELEMENTO] . "'>", 24);
        $html .= tabular($this->devolverElemento(), 28);
        $html .= tabular("</div>", 24);
        $html .= tabular("</div>", 20);
        return $html;
    }

    /**
     * Plantilla para la ventana tipo login
     * @return string
     */
    protected function plantillaLogin() {
        return $this->plantilla();
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
        return $this->plantilla();
    }

    /**
     * Retorna el codigo HTML del elemento con la plantilla div para el login
     * Es un metodo publico, se utiliza desde fuera de la clase, ver class formulario
     * @return string
     */
    public function devolverLogin() {
        return $this->plantillaLogin();
    }

    /**
     * Devolver las propiedades del elemento
     * @return array
     */
    public function devolverProp() {
        // Etiqueta del elemento
        $this->_prop['e' . $this->devolverId()] = $this->devolverLabel();
        // Elemento tipo HTML
        $this->_prop['c' . $this->devolverId()] = $this->devolverElemento();
        // Label y Elemento
        $this->_prop['g' . $this->devolverId()] = $this->plantilla();
        // Mensaje de ayuda
        $this->_prop['a' . $this->devolverId()] = $this->devolverAyuda();
        // Pirpiedades del elemento
        return $this->_prop;
    }

    /**
     * Devolver el id del elemento
     * @return string
     */
    public function devolverId() {
        return $this->_id;
    }

    /**
     * Devolver la etiqueta creada para el elemento, se utiliza en la creacion de plantillas
     * personalizadas, la etiqueta puede estar ubicada en un sitio diferente al campo de entreda
     * Es un metodo publico, se utiliza desde fuera de la clase
     * @param string $dimensiones Define las dimensiones segun el tipo de dispositivo, ejm: col-xs-2 col-sm-3 col-md-4 col-lg-5
     * @return string
     */
    public function devolverLabel($dimensiones = '') {
        return "<label class='control-label {$dimensiones}' for='{$this->_id}'>{$this->_etiqueta}</label>";
    }

    /**
     * Retorna el codigo HTML del elemento SIN la plantilla
     * Es un metodo publico, se utiliza desde fuera de la clase, ver class buscar
     * @return string
     */
    public function devolverElemento() {
        return $this->_html;
    }

    /**
     * Retorna la ayuda para colocarla dentro de un elemento personalizado
     * Es un metodo publico, se utiliza desde fuera de la clase, ver class buscar
     * @return string
     */
    public function devolverAyuda() {
        $html = '';
        if(isset($this->_prop[ZC_MENSAJE_AYUDA])) {
            // Solo los campos tipo input tienen esta propiedad
            $html = $this->ayuda($this->_prop[ZC_MENSAJE_AYUDA]);
        }
        return $html;
    }
}
