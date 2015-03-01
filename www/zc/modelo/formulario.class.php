<?php

/**
 * Defien el fin de linea para los archivos creados
 */
define("FIN_DE_LINEA", "\n");
/**
 * Fin de linea para las pruebas
 */
define('FIN_DE_LINEA_HTML', '<br/>');

/**
 * Clase para la creacion de cajas de textos (input type=text)
 */
require RUTA_GENERADOR_CODIGO . '/modelo/cajaTexto.class.php';

/**
 * Clase para la creacion de botones (button)
 */
require RUTA_GENERADOR_CODIGO . '/modelo/acciones.class.php';

/**
 * Crear formulario html
 */
class formulario {

    /**
     * Tipo de WS a crear rest|soap, por defecto = rest
     * @var string
     */
    public $_tipoWS = ZC_WS_REST;

    /**
     * Identificador/Nombre del formulario creado
     * @var string
     */
    public $_id = '';

    /**
     * Bandera para saber si se debe crear el WS (1 = Si, 0 0 No)
     * @var int
     */
    public $_crearWS = 0;

    /**
     * Metodo a utilizar para el envio del formulario
     * GET, POST
     * @var string
     */
    public $_metodo = '';

    /**
     * Bandera pasar saber si se debe crear la validacion del lado servidor, por defecto = true
     * @var boolean
     */
    public $_validacionServidor = true;

    /**
     * Crear las acciones en el controlador del lado servidor
     * @var boolean
     */
    public $_crearAccionServidor = true;

    /**
     * Alamcena las acciones que se crearan en el controlador, debe estar habilitado
     * la creacion de acciones en el servidor: $_crearAccionServidor
     * @var string
     */
    public $_accionServidor = '';

    /**
     * Metodo por defecto para el envio de la informacion, en caso de no definirse _metodo, por defecto POST
     * @var string
     */
    protected $_metodoDefecto = 'POST';

    /**
     * Ruta completa de la ruta salida del formulario
     * @var string
     */
    protected $_salidaFormulario = '';

    /**
     * Directorio (carpeta) de salida del formulario
     * @var string
     */
    protected $_rutaFormulario = '';

    /**
     * Almacena cada uno de los elementos creados, se dejan en el orden de creacion
     * @var array
     */
    private $_formulario = array();

    /**
     * Resultado de unior todos los elementos del software
     * @var string
     */
    private $_textoFormulario = '';

    /**
     * Archivos javascript utilizados por el formulario
     * @var string
     */
    private $_js = '';

    /**
     * Almacena la plantilla html de la vista seleccionada
     * @var \plantilla
     */
    private $_plantillaHTML = null;

    /**
     * Elementos utilizados por el formulario, pueden ser text, button, radio, etc.
     * @var type
     */
    private $_elementos = array();

    /**
     * Acciones utilizados por el formulario, botones normalmente
     * @var type
     */
    private $_acciones = array();

    /**
     * Asignacion de variables en funciones javascript las usadas en el envio Ajax
     * @var string
     */
    private $_asignacionCliente = '';

    /**
     * Parametros pasados al servidor SOAP, se usan durante la definicion de los parametros que acepta la funcion servidor SOAP
     * @var string
     */
    private $_asignacionParametrosServidorSOAP = '';

    /**
     * Parametros pasados por el cliente segun los valores del Ajax, a ser enviados al servidor SOAP
     * @var string
     */
    private $_asignacionParametrosClienteSOAP = '';

    /**
     * Lista de parametros pasados a la funcion en el servidor SOAP
     * @var string
     */
    private $_asignacionParametrosFuncionServidorSOAP = '';

    /**
     * Lista de paramettros pasados a la funcion de servidor SOAP
     * @var string
     */
    private $_asignacionParametrosFuncionClienteSOAP = "\$datos['accion'] = \$this->input->post('accion');\n";

    /**
     * Inicializacion de parametros pasados al ajax
     * @var string
     */
    private $_inicializacionCliente = '';

    /**
     * Inicializacion la variable que contiene el codigo de la validacion del lado servidor
     * @var string
     */
    private $_validacion = '';

    /**
     * Inicializacion la variable que contiene el codigo para procesar la respuesta del servidor (SOAP)
     * @var string
     */
    private $_procesarWS = '';

    /**
     * Inicializacion la variable que contiene el codigo del llamado a al servidor (SOAP)
     * @var string
     */
    private $_clienteSOAP = '';

    /**
     * Inicializacion la variable que contiene el javascript (con AJAX) que hace uso del modelo en el servidor
     * @var string
     */
    private $_accionCliente = '';

    /**
     * Nombre del archivo modelo creado en /models
     * @var string
     */
    private $_nombreArchivoModelo = '';

    /**
     * Nombre del archivo controlador creado en /controllers
     * @var string
     */
    private $_nombreArchivoControlador = '';

    /**
     * Nombre del archivo vista creado en /views
     * @var string
     */
    private $_nombreArchivoVista = '';

    /**
     * Funcion de inicializacion del formulario, reqeuire que seha suministradas
     * unos datos basicos relacionados al formulario
     * array(
      'tipoWS' => 'rest|soap|',
      ZC_ID => 'operaciones',
      'crearWS' => 1|0,
      'metodo' => 'post|get',
      'validacionServidor' => 1|0
      );
     * @param array $caracteristicas
     * @throws Exception
     */
    function __construct($caracteristicas) {
        if (!is_array($caracteristicas)) {
            throw new Exception(__FUNCTION__ . ": Y las caracteristicas del formulario!?");
        } else {
            $this->_id = ucwords($caracteristicas[ZC_ID]);
            $this->_tipoWS = (isset($caracteristicas[ZC_TIPO_WS])) ? strtolower($caracteristicas[ZC_TIPO_WS]) : strtolower($this->_tipoWS);
//            $this->_crearWS = $caracteristicas['crearWS'];
            $this->_metodo = (isset($caracteristicas[ZC_FORMULARIO_METODO])) ? strtoupper($caracteristicas[ZC_FORMULARIO_METODO]) : $this->_metodoDefecto;
//            $this->_validacionServidor = (isset($caracteristicas['validacionServidor'])) ? $caracteristicas['validacionServidor'] : $this->_validacionServidor;
            $this->inicio();
        }
    }

    /**
     * Muestra en pantalla el formulario creado
     */
    public function imprimirFormulario() {
        echo $this->unirElementosFormulario($this->_formulario);
    }

    /**
     * Retorno en un string los elementos del formulario concatenado
     * @return string
     */
    public function devolverFormulario() {
        return $this->_plantillaHTML->devolverPlantilla();
    }

    /**
     * Crear un arhivo fisico para el el formulario en el sirectorio de salida definido
     * por defecto:
     *  $directorioSalida = dist/form (Ruta de salida)
     *  $tipoSalida = php (Extension)
     * @param string $directorioSalida Ruta donde se crear el archivo
     * @param string $extension Extension del archivo creado
     * @param array $opciones Opciones a aplicar a la plantilla del formulario creado
     * @return \formulario
     * @throws Exception
     */
    public function crearVistaFormulario($directorioSalida = '../application/views', $extension = 'html', $opciones = array()) {
        /**
         * Plantilla para la vista (view), se puede devolver, por eso se deja en una variable $this
         */
        $this->_plantillaHTML = new plantilla();
        $this->_plantillaHTML->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/html/htmlFluid.tpl');

        $this->_plantillaHTML->asignarEtiqueta('nombreFormulario', $this->_id);
        $this->_plantillaHTML->asignarEtiqueta('metodoFormulario', $this->_metodo);
        $this->_plantillaHTML->asignarEtiqueta('contenidoFormulario', $this->unirElementosFormulario($this->_formulario));
        $this->_plantillaHTML->asignarEtiqueta('archivoJavascript', $this->_js);

        if (isset($opciones['minimizar']) && $opciones['minimizar'] === true) {
            $this->_plantillaHTML->minimizarPlantilla();
        }

        $this->_textoFormulario = (0 == count($this->_formulario)) ? '<vacio>' : $this->_plantillaHTML->devolverPlantilla();

        $this->_plantillaHTML->crearPlantilla($directorioSalida, $extension, $this->_nombreArchivoVista);

        if (isset($opciones['abrir']) && $opciones['abrir'] === true) {
            $this->_plantillaHTML->abrirPlantilla();
        }

        return $this;
    }

    /**
     * Crear el archivo con el modelo (model) para el formulario
     * @param string $directorioSalida ruta donde se creara el archivo
     * @param string $extension Extension del archivo creado. por defecto PHP
     * @param array $opciones Opciones a aplicar a la plantilla creada
     * @return \formulario
     */
    private function crearModeloFormulario($directorioSalida = '../application/models', $extension = 'php', $opciones = array()) {
        /**
         * Plantilla para el modelo (model)
         */
        $plantilla = new plantilla();
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpModeloSOAP.tpl');

        $plantilla->asignarEtiqueta('nombreModelo', $this->_nombreArchivoModelo);
        $plantilla->asignarEtiqueta('validacion', $this->_validacion);
        $plantilla->asignarEtiqueta('procesarWS', $this->_procesarWS);
        $plantilla->asignarEtiqueta('clienteWS', $this->_clienteSOAP);

        if (isset($opciones['minimizar']) && $opciones['minimizar'] === true) {
            $plantilla->minimizarPlantilla();
        }

        $plantilla->crearPlantilla($directorioSalida, $extension, $this->_nombreArchivoModelo);

        return $this;
    }

    /**
     * Crea el controlador del formulario del tipo CodeIgniter
     * @param type $directorioSalida Ruta donde se creara el archivos
     * @param type $extension Extension con la cual se creara el archivo
     * @param type $opciones Opciones a aplicar a la plantilla creada
     * @return \formulario
     */
    private function crearControladorFormulario($directorioSalida = '../application/controllers', $extension = 'php', $opciones = array()) {
        /**
         * Plantilla para el controlador (controller)
         */
        $plantilla = new plantilla();
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpControladorSOAP.tpl');

        $plantilla->asignarEtiqueta('nombreControlador', $this->_nombreArchivoControlador);
        $plantilla->asignarEtiqueta('nombreVista', $this->_nombreArchivoVista . '.html');
        $plantilla->asignarEtiqueta('nombreModelo', $this->_nombreArchivoModelo);
        $plantilla->asignarEtiqueta('accionServidor', $this->_accionServidor);

        if (isset($opciones['minimizar']) && $opciones['minimizar'] === true) {
            $plantilla->minimizarPlantilla();
        }

        $plantilla->crearPlantilla($directorioSalida, $extension, $this->_nombreArchivoControlador);

        return $this;
    }

    /**
     * Crea el el archivo javascrip que jace el llamado al modelo para procesar los datos
     * @param type $directorioSalida Ruta donde se creara el archivos
     * @param type $extension Extension con la cual se creara el archivo
     * @param type $opciones Opciones a aplicar a la plantilla creada
     * @return \formulario
     */
    private function crearJavascriptFormulario($directorioSalida = '../publico/js', $extension = 'js', $opciones = array()) {
        /**
         * Plantilla para el manejo de javascript
         */
        $plantilla = new plantilla();
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/jsInicializacionjQuery.js');

        $plantilla->asignarEtiqueta('nombreFormulario', $this->_id);
        $plantilla->asignarEtiqueta('accionesCliente', $this->_accionCliente);

        if (isset($opciones['minimizar']) && $opciones['minimizar'] === true) {
            $plantilla->minimizarPlantilla();
        }

        $plantilla->crearPlantilla($directorioSalida, $extension, $this->_id);
        // Agregar archivo creado al javascript al formulario
        $this->javascriptFormulario($plantilla->_salidaPlantilla);

        return $this;
    }

    /**
     * Crea las cajas de texto en el formulario, segun las caracteristicas dadas
     * array(
      ZC_ID => '', // Id de la caja de texto
      'tipo' => 'cajaTexto',
      ZC_DATO => 'int|string', // integer no se ha contemplado
      ZC_ELEMENTO_ETIQUETA => '', // Etiqueta (descripcion relacionada a la caja de texto)
      ZC_OBLIGATORIO => 'true|false' // Define si el campo es obligatorio (para validacion cliente y servidor)
      )
     * @return \formulario
     */
    public function agregarCajaTextoFormulario() {
        $cajas = func_get_args();
        $this->agregarElementoFormulario($cajas);
        return $this;
    }

    /**
     * Crea los botones de accion definidos por el usuario segun las caracteristicas dadas
     * array(
      ZC_ID => 'sumar', // Identificador del boton
      'tipo' => 'boton',
      ZC_ELEMENTO_ETIQUETA => 'Sumar', // Nombre a mostrar en el boton
      'rutaWS' => 'http://soa.freddie.net/dist', // Ruta del WS a utlizar por la funcion
      'directorioWSS' => 'wss', // Directorio padre del WS a utilizar
      'accionServidor' => "
      // Accion a ejecutar en el servidor cuando se hace click en el boton
      ",
      'accionCliente' => "
      // Accion a ejecutar en el cliente cuando se tenga respuesta del servidor
      "
      )
     * @return \formulario
     */
    public function agregarBotonesFormulario() {
        $botones = func_get_args();
        $this->agregarElementoFormulario($botones);
        return $this;
    }

    /**
     * Alias de la funcion para agregar elementos desde otras clases
     * @param type $elementos
     */
    public function agregarElementoDesconocidoFormulario() {
        $elementos = func_get_args();
        $this->agregarElementoFormulario($elementos);
    }

    /**
     * Crea los elemntos dentro del formulario segun las caracteristicas entregadas
     * @param array $elementos Caracteristicas de los elementos a entregar
     * @throws Exception
     * @return \formulario
     */
    private function agregarElementoFormulario($elementos) {
        foreach ($elementos as $caracteristicas) {
            if (!is_array($caracteristicas)) {
                throw new Exception(__FUNCTION__ . ": Y las caracteristicas del elemento!?");
            }
            /**
             * Define el tipo de boton a crear
             * boton = button (Boton normal, tambien utilizado para el submit) default
             * restablecer = reset (Limpiar formulario)
             */
            $tipoAccion = 'boton';
            /**
             * Se valida en minuscula para evitar ambiguaedades: Boton, boton, BOTON, etc
             */
            $caracteristicas[ZC_ELEMENTO_TIPO] = (strtolower($caracteristicas[ZC_ELEMENTO_TIPO]));
            switch ($caracteristicas[ZC_ELEMENTO_TIPO]) {
                case ZC_ELEMENTO_CAJA_TEXTO:
                    $this->agregarElementoTextoFormulario($caracteristicas);
                    break;
                case ZC_ELEMENTO_RADIO:
                    break;
                case ZC_ELEMENTO_CHECKBOX:
                    break;
                case ZC_ELEMENTO_RESTABLECER:
                    $tipoAccion = 'restablecer';
                case ZC_ELEMENTO_BOTON:
                    $this->agregarElementoBotonFormulario($caracteristicas, $tipoAccion);
                    break;
                default:
                    throw new Exception(__FUNCTION__ . ": Tipo de elemento no definido: {$caracteristicas[ZC_ELEMENTO_TIPO]}!");
            }
        }
        return $this;
    }

    /**
     * Asigna las caracteristicas que tendra el formulario
     * @return \formulario
     */
    public function inicioFormulario() {
        return $this;
    }

    /**
     * Construye los archivos necesarios.
     * @return \formulario
     */
    public function finFormulario() {
        $this->procesarFormulario();
        $this->crearControladorFormulario();
        $this->crearModeloFormulario();
        // Las acciones del cliente se deben procesar despues de crear el controlador,
        // ya que este ultimo hace referencia a la URL
        $this->crearAccionesClienteFormulario();
        $this->crearJavascriptFormulario();
        // La vista se debe crear despues de los archivos javascript (cargar bien rutas js)
        $this->crearVistaFormulario();

        $this->fin();
        return $this;
    }

    /**
     * Une los elmentos de un formulario
     * @param array $elementos Elementos del formlario
     * @return string
     */
    private function unirElementosFormulario($elementos) {
        $elementosFormulario = '';
        foreach ($elementos as $elemento => $valor) {
            // Hasta el momento solo se utilizan para los botones
            $estiloInicio = $estiloFin = '';
            if ($elemento == 'acciones') {
                // Las acciones son muchas en una sola fila, se agrupan al final del proceso
                $estiloInicio = "
                    <div class='row'>
                        <div class='col-md-1'></div>
                        <div class='col-md-5 text-center'>
                        ";
                $estiloFin = "
                        </div>
                        <div class='col-md-5'></div>
                        <div class='col-md-1'></div>
                    </div>
                ";
            }
            if (is_array($elementos[$elemento])) {
                $elementosFormulario .= $estiloInicio.$this->unirElementosFormulario($elementos[$elemento]).$estiloFin;
            } else {
                $elementosFormulario .= $elementos[$elemento];
            }
        }
        return $elementosFormulario;
    }

    /**
     * Agrega las cajas de texto dentro del formulario, segun caracteristicas
     * @param string $caracteristicas
     * @return \formulario
     */
    private function agregarElementoTextoFormulario($caracteristicas) {
        // Para evitar errores con valores vacios se hace la validacion previa
        $this->verificarValoresArgumentos($caracteristicas);
        $texto = new cajaTexto($caracteristicas);
        $texto->crearCajaTexto();
        $this->_formulario['elementos'][$caracteristicas[ZC_ID]] = $texto->devolverCajaTexto();
        $this->_elementos[] = $caracteristicas;
        return $this;
    }

    /**
     * Agrega las botones dentro del formulario, segun caracteristicas
     * @param string $caracteristicas
     * @return \formulario
     */
    private function agregarElementoBotonFormulario($caracteristicas, $tipoAccion = 'boton') {
        // Para evitar errores con valores vacios se hace la validacion previa
        $this->verificarValoresArgumentos($caracteristicas);
        $boton = new acciones($caracteristicas, $tipoAccion);
        $boton->crearAccion();
        $this->_formulario['acciones'][$caracteristicas[ZC_ID]] = $boton->devolverAccion();
        $this->_acciones[] = $caracteristicas;
        return $this;
    }

    /**
     * Agrea los archivos javascript al formulario
     * @return \formulario
     */
    public function javascriptFormulario() {
        $javascript = func_get_args();
        $this->agregarJavascriptFormulario($javascript);
        return $this;
    }

    /**
     * Carga la plantilla seleccionada por el usuario, el metodo cargarPlantilla
     * se encarga de validar si es una ruta valida
     * @return \formulario
     */
    private function inicio() {

        /**
         * Define nombres de los archivos creados: modelo, vista, controlador
         * Los nombres se manejan en minuscula y seperados con underscore segun
         * recomendacion de CodeIgniter
         */
        $id = strtolower($this->_id);
        $this->_nombreArchivoModelo = 'modelo_' . $id;
        $this->_nombreArchivoVista = 'vista_' . $id;
        $this->_nombreArchivoControlador = $id;

        return $this;
    }

    /**
     * Define el fin del documento html
     * @return \formulario
     */
    private function fin() {
        return $this;
    }

    /**
     * Crear archivo encargado de hacer la validaciones del lado servidor
     * $directorioSalida = dist/serv
     * @param string $directorioSalida Ruta de salida del archivo
     * @return \formulario
     * @throws Exception
     */
    private function modeloValidacionFormulario() {
        if ($this->_validacionServidor) {

            $validacion = '';

            foreach ($this->_elementos as $nro => $caracteristicas) {

                $this->_inicializacionCliente .= "var {$caracteristicas[ZC_ID]} = \$.trim($('#{$caracteristicas[ZC_ID]}').val());" . FIN_DE_LINEA . insertarEspacios(16);
                $this->_asignacionCliente .= ('' == $this->_asignacionCliente) ? '' : ', ';
                $this->_asignacionCliente .= $caracteristicas[ZC_ID] . ': ' . $caracteristicas[ZC_ID];

                $this->_asignacionParametrosServidorSOAP .= ($this->_asignacionParametrosServidorSOAP == '') ? '' : ',' . FIN_DE_LINEA;
                $this->_asignacionParametrosServidorSOAP .= insertarEspacios(12) . "'{$caracteristicas[ZC_ID]}' => 'xsd:{$caracteristicas[ZC_DATO]}'";

                $this->_asignacionParametrosClienteSOAP .= ($this->_asignacionParametrosClienteSOAP == '') ? '' : ',' . FIN_DE_LINEA . insertarEspacios(12);
                $this->_asignacionParametrosClienteSOAP .= "'{$caracteristicas[ZC_ID]}' => \$datos['{$caracteristicas[ZC_ID]}']";

                $this->_asignacionParametrosFuncionServidorSOAP .= ($this->_asignacionParametrosFuncionServidorSOAP == '') ? '' : ', ';
                $this->_asignacionParametrosFuncionServidorSOAP .= "\${$caracteristicas[ZC_ID]}";

                $this->_asignacionParametrosFuncionClienteSOAP .= insertarEspacios(8) . "\$datos['{$caracteristicas[ZC_ID]}'] = \$this->input->post('{$caracteristicas[ZC_ID]}');" . FIN_DE_LINEA;

                // Validacion obligatoriedad
                $validacion .= validarArgumentoObligatorio($caracteristicas[ZC_ID], $caracteristicas[ZC_ELEMENTO_ETIQUETA], $caracteristicas[ZC_OBLIGATORIO], $caracteristicas[ZC_OBLIGATORIO_ERROR]);

                // Validacion tipo de dato Entero
                $validacion .= validarArgumentoTipo($caracteristicas[ZC_ID], $caracteristicas[ZC_ELEMENTO_ETIQUETA], $caracteristicas[ZC_DATO], $caracteristicas[ZC_DATO_MENSAJE_ERROR]);

                // Validacion longitud minima del campo
                $validacion .= validarArgumentoLongitudMinima($caracteristicas[ZC_ID], $caracteristicas[ZC_ELEMENTO_ETIQUETA], $caracteristicas[ZC_DATO], $caracteristicas[ZC_LONGITUD_MINIMA], $caracteristicas[ZC_LONGITUD_MINIMA_ERROR]);

                // Validacion longitud maxima del campo
                $validacion .= validarArgumentoLongitudMaxima($caracteristicas[ZC_ID], $caracteristicas[ZC_ELEMENTO_ETIQUETA], $caracteristicas[ZC_DATO], $caracteristicas[ZC_LONGITUD_MAXIMA], $caracteristicas[ZC_LONGITUD_MAXIMA_ERROR]);
            }

            $plantilla = new plantilla();
            $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpValidacionServidor.tpl');
            $plantilla->asignarEtiqueta('nombreFormulario', $this->_id);
            $plantilla->asignarEtiqueta('elementosFormulario', $validacion);

            // Concatena cada accion del cliente
            $this->_validacion = $plantilla->devolverPlantilla() . FIN_DE_LINEA;
        }
        return $this;
    }

    /**
     * Crea funcion estandar para el proceso de las respuestas entregadas por el WS
     * @return \formulario
     */
    private function modeloProcesarWS() {
        if ($this->_validacionServidor) {
            $plantilla = new plantilla();

            if ($this->_tipoWS == ZC_WS_SOAP) {
                /**
                 * Plantilla para procesar las respuesta tipo SOAP
                 */
                $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpProcesarSOAP.tpl');
            } elseif ($this->_tipoWS == ZC_WS_REST) {
                /**
                 * Plantilla para procesar las respuesta tipo REST
                 */
                $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpProcesarREST.tpl');
            }

            // Concatena el tipo de procesamiento segune el tipo de WS
            $this->_procesarWS .= $plantilla->devolverPlantilla() . FIN_DE_LINEA;
        }

        return $this;
    }

    /**
     * Acciones en el servidor
     * @param string $directorioSalida
     * @return \formulario
     */
    private function modeloControladorAcciones() {
        if ($this->_crearAccionServidor) {

            foreach ($this->_acciones as $nro => $caracteristicas) {
                if (ZC_ELEMENTO_RESTABLECER == $caracteristicas[ZC_ELEMENTO_TIPO]) {
                    // Los botones tipo restablecer no crean acciones
                    continue;
                }
                /**
                 * Plantilla para la creacion de acciones en el controlador
                 */
                $plantilla = new plantilla();
                if ($this->_tipoWS == ZC_WS_SOAP) {
                    $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpControladorAccionSOAP.tpl');
                    $plantilla->asignarEtiqueta('nombreAccion', $caracteristicas[ZC_ID]);
                    // Se crean durante el proceso de los elementos para las validaciones
                    $plantilla->asignarEtiqueta('asignacionCliente', $this->_asignacionParametrosFuncionClienteSOAP);
                    $plantilla->asignarEtiqueta('nombreValidacion', 'validacion' . $this->_id);
                    $plantilla->asignarEtiqueta('nombreModelo', $this->_nombreArchivoModelo);
                } else if ($this->_tipoWS == ZC_WS_REST) {
                    $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpControladorAccionREST.tpl');
                }
                // Concatena cada accion del cliente
                $this->_accionServidor .= $plantilla->devolverPlantilla() . FIN_DE_LINEA;
            }
        }
        return $this;
    }

    /**
     * Acciones del lado cliente Ajax
     * @param string $directorioSalida
     * @return \formulario
     */
    private function crearAccionesClienteFormulario() {
        if ($this->_crearAccionServidor) {

            foreach ($this->_acciones as $nro => $caracteristicas) {
                if (ZC_ELEMENTO_RESTABLECER == $caracteristicas[ZC_ELEMENTO_TIPO]) {
                    // Los botones tipo restablecer no crean accciones de envio, ya tiene la
                    // accion preferida
                    continue;
                }
                /**
                 * Plantilla para los envio con AJAX en javascript (jQuery)
                 */
                $plantilla = new plantilla();
                $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/jsLlamadosAjax.js');
                $plantilla->asignarEtiqueta('nombreAccion', $caracteristicas[ZC_ID]);
                $plantilla->asignarEtiqueta('nombreControlador', $this->_nombreArchivoControlador);
                // Se crean durante el proceso de los elementos para las validaciones
                $plantilla->asignarEtiqueta('inicializacionCliente', $this->_inicializacionCliente);
                $plantilla->asignarEtiqueta('asignacionCliente', $this->_asignacionCliente);
                // Se define en la creacion de la plantilla del controlador
                $plantilla->asignarEtiqueta('nombreFormulario', $this->_id);
                $plantilla->asignarEtiqueta('accionCliente', '//Accion Cliente va aqui');

                // Concatena cada accion del cliente
                $this->_accionCliente .= $plantilla->devolverPlantilla() . FIN_DE_LINEA;
            }
        }

        return $this;
    }

    /**
     * Crear cliente WS para el caso de WS SOAP
     * @return \formulario
     */
    private function modeloWsSOAPClienteFormulario() {
        if ($this->_crearAccionServidor && $this->_tipoWS == ZC_WS_SOAP) {
            foreach ($this->_acciones as $nro => $caracteristicas) {
                if (ZC_ELEMENTO_RESTABLECER == $caracteristicas[ZC_ELEMENTO_TIPO]) {
                    // Los botones tipo restablecer no crean acciones
                    continue;
                }
                /**
                 * Plantilla para la creacion de acciones en el cliente
                 */
                $plantilla = new plantilla();
                $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpCrearAccion.tpl');
                $plantilla->asignarEtiqueta('nombreModelo', $this->_id);
                $plantilla->asignarEtiqueta('nombreAccion', $caracteristicas[ZC_ID]);
                $plantilla->asignarEtiqueta('servidorAccion', $this->_id . '.' . $this->_tipoWS);
                $plantilla->asignarEtiqueta('asignacionCliente', $this->_asignacionParametrosClienteSOAP);

                // Concatena las acciones que se pueden llamar desde el cliente
                $this->_clienteSOAP .= $plantilla->devolverPlantilla() . FIN_DE_LINEA;
            }
        }

        return $this;
    }

    /**
     * Crear servidor WS de lado servidor
     * @param string $directorioSalida
     * @return \formulario
     */
    private function crearWsSOAPServidorFormulario($directorioSalida = 'dist/wss') {
        if ($this->_crearAccionServidor && $this->_tipoWS == ZC_WS_SOAP) {
            $nombre = $this->_id;
            $extension = 'php';
            $this->_nombre = $nombre . '.' . $this->_tipoWS . '.' . $extension;

            $servidorSOAP = "<?php" . FIN_DE_LINEA;
            $servidorSOAP .= "" . FIN_DE_LINEA;
            $servidorSOAP .= "// Solo muestra errores fatales" . FIN_DE_LINEA;
            $servidorSOAP .= "error_reporting(1);" . FIN_DE_LINEA;
            $servidorSOAP .= "// Fecha y hora del sistema" . FIN_DE_LINEA;
            $servidorSOAP .= "date_default_timezone_set('America/Bogota');" . FIN_DE_LINEA;
            $servidorSOAP .= "" . FIN_DE_LINEA;
            $servidorSOAP .= "/**" . FIN_DE_LINEA;
            $servidorSOAP .= " * Clase para el manejo de Servidor WS" . FIN_DE_LINEA;
            $servidorSOAP .= " */" . FIN_DE_LINEA;
            $servidorSOAP .= "if (!class_exists('soap_server')) {" . FIN_DE_LINEA;
            $servidorSOAP .= "    require '../nuSOAP/nusoap.php';" . FIN_DE_LINEA;
            $servidorSOAP .= "}" . FIN_DE_LINEA;

            $servidorSOAP .= "\$miURL = '';" . FIN_DE_LINEA;
            $servidorSOAP .= "\$_SRV_WS = new soap_server();" . FIN_DE_LINEA;
            $servidorSOAP .= "\$_SRV_WS->configureWSDL('{$nombre}', \$miURL);" . FIN_DE_LINEA;
            $servidorSOAP .= "\$_SRV_WS->wsdl->schemaTargetNamespace = \$miURL;" . FIN_DE_LINEA;
            $servidorSOAP .= "\$_SRV_WS->wsdl->setDebugLevel(9);" . FIN_DE_LINEA;
            $servidorSOAP .= "" . FIN_DE_LINEA;

            foreach ($this->_acciones as $nro => $caracteristicas) {
                $servidorSOAP .= "/**" . FIN_DE_LINEA;
                $servidorSOAP .= " * Definir los tipo de variables que se devolveran en la repuesta" . FIN_DE_LINEA;
                $servidorSOAP .= " */" . FIN_DE_LINEA;
                $servidorSOAP .= "\$_SRV_WS->wsdl->addComplexType(" . FIN_DE_LINEA;
                $servidorSOAP .= "    '{$caracteristicas[ZC_ID]}Respta', " . FIN_DE_LINEA;
                $servidorSOAP .= "    'complexType', " . FIN_DE_LINEA;
                $servidorSOAP .= "    'struct', " . FIN_DE_LINEA;
                $servidorSOAP .= "    'all', " . FIN_DE_LINEA;
                $servidorSOAP .= "    '', " . FIN_DE_LINEA;
                $servidorSOAP .= "    array(" . FIN_DE_LINEA;
                $servidorSOAP .= "        'cta' => array(" . FIN_DE_LINEA;
                $servidorSOAP .= "            'name' => 'cta'," . FIN_DE_LINEA;
                $servidorSOAP .= "            'type' => 'xsd:string'" . FIN_DE_LINEA;
                $servidorSOAP .= "        )," . FIN_DE_LINEA;
                $servidorSOAP .= "        'infoEncabezado' => array(" . FIN_DE_LINEA;
                $servidorSOAP .= "            'name' => 'infoEncabezado'," . FIN_DE_LINEA;
                $servidorSOAP .= "            'type' => 'xsd:string'" . FIN_DE_LINEA;
                $servidorSOAP .= "        )," . FIN_DE_LINEA;
                $servidorSOAP .= "        'error1' => array(" . FIN_DE_LINEA;
                $servidorSOAP .= "            'name' => 'error1'," . FIN_DE_LINEA;
                $servidorSOAP .= "            'type' => 'xsd:string'" . FIN_DE_LINEA;
                $servidorSOAP .= "        )" . FIN_DE_LINEA;
                $servidorSOAP .= "    )" . FIN_DE_LINEA;
                $servidorSOAP .= ");" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "/**" . FIN_DE_LINEA;
                $servidorSOAP .= " * Definir la manera de devolver el resultado" . FIN_DE_LINEA;
                $servidorSOAP .= " */" . FIN_DE_LINEA;
                $servidorSOAP .= "\$_SRV_WS->wsdl->addComplexType(" . FIN_DE_LINEA;
                $servidorSOAP .= "    '{$caracteristicas[ZC_ID]}ResptaArray', " . FIN_DE_LINEA;
                $servidorSOAP .= "    'complexType', " . FIN_DE_LINEA;
                $servidorSOAP .= "    'array', " . FIN_DE_LINEA;
                $servidorSOAP .= "    '', " . FIN_DE_LINEA;
                $servidorSOAP .= "    'SOAP-ENC:Array', " . FIN_DE_LINEA;
                $servidorSOAP .= "    array(), " . FIN_DE_LINEA;
                $servidorSOAP .= "    array(" . FIN_DE_LINEA;
                $servidorSOAP .= "        array(" . FIN_DE_LINEA;
                $servidorSOAP .= "            'ref' => 'SOAP-ENC:arrayType'," . FIN_DE_LINEA;
                $servidorSOAP .= "            'wsdl:arrayType' => 'tns:{$caracteristicas[ZC_ID]}Respta[]'" . FIN_DE_LINEA;
                $servidorSOAP .= "        )" . FIN_DE_LINEA;
                $servidorSOAP .= "    )," . FIN_DE_LINEA;
                $servidorSOAP .= "    'tns:{$caracteristicas[ZC_ID]}Respta'" . FIN_DE_LINEA;
                $servidorSOAP .= ");" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "/**" . FIN_DE_LINEA;
                $servidorSOAP .= " * Parametros que recibe la funcion" . FIN_DE_LINEA;
                $servidorSOAP .= " */" . FIN_DE_LINEA;
                $servidorSOAP .= "\${$caracteristicas[ZC_ID]}Parametros = array(" . FIN_DE_LINEA;
                $servidorSOAP .= "{$this->_asignacionParametrosServidorSOAP}" . FIN_DE_LINEA;
                $servidorSOAP .= ");" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "/**" . FIN_DE_LINEA;
                $servidorSOAP .= " * Definir el tipo de respuesta que devuelve el servidor" . FIN_DE_LINEA;
                $servidorSOAP .= " */" . FIN_DE_LINEA;
                $servidorSOAP .= "\${$caracteristicas[ZC_ID]}Returns = array(" . FIN_DE_LINEA;
                $servidorSOAP .= "    'return' => 'tns:{$caracteristicas[ZC_ID]}ResptaArray'" . FIN_DE_LINEA;
                $servidorSOAP .= ");" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "/**" . FIN_DE_LINEA;
                $servidorSOAP .= " * Registrar la funcion en el servidor" . FIN_DE_LINEA;
                $servidorSOAP .= " */" . FIN_DE_LINEA;
                $servidorSOAP .= "\$_SRV_WS->register(" . FIN_DE_LINEA;
                $servidorSOAP .= "        '{$caracteristicas[ZC_ID]}{$nombre}Servidor', // Nombre de la funcion" . FIN_DE_LINEA;
                $servidorSOAP .= "        \${$caracteristicas[ZC_ID]}Parametros, // Parametros de entrada" . FIN_DE_LINEA;
                $servidorSOAP .= "        \${$caracteristicas[ZC_ID]}Returns, // Parametros de salida" . FIN_DE_LINEA;
                $servidorSOAP .= "        \$miURL" . FIN_DE_LINEA;
                $servidorSOAP .= ");" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "function {$caracteristicas[ZC_ID]}{$nombre}Servidor({$this->_asignacionParametrosFuncionServidorSOAP}){" . FIN_DE_LINEA;
                $servidorSOAP .= "    // f_crmlog('Parametros WSS ' . __FUNCTION__ . preprint(get_defined_vars(), 1));" . FIN_DE_LINEA;
                $servidorSOAP .= "    // global \$conexionsql;" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "    // if (!class_exists('error')) {" . FIN_DE_LINEA;
                $servidorSOAP .= "    //     require(gLib::\$_webExtApp . archivosInclude::\$_error_class_php);" . FIN_DE_LINEA;
                $servidorSOAP .= "    // }" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "    // Clase para el manejo de errores" . FIN_DE_LINEA;
                $servidorSOAP .= "    // \$manejoError = new error();" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "    // Incializacion de variables de respuesta" . FIN_DE_LINEA;
                $servidorSOAP .= "    \$Resultado[0] = array(" . FIN_DE_LINEA;
                $servidorSOAP .= "        'cta' => 0," . FIN_DE_LINEA;
                $servidorSOAP .= "        'infoEncabezado' => ''," . FIN_DE_LINEA;
                $servidorSOAP .= "        'error1' => ''," . FIN_DE_LINEA;
                $servidorSOAP .= "    );" . FIN_DE_LINEA;
                $servidorSOAP .= "    \$i = 0;" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "    // if (!\$conexionsql) {" . FIN_DE_LINEA;
                $servidorSOAP .= "    //    \$manejoError->crearError('Error de conexion');" . FIN_DE_LINEA;
                $servidorSOAP .= "    // } else {" . FIN_DE_LINEA;
                $servidorSOAP .= "        " . $this->formatearAccionFormulario($caracteristicas['accionServidor']) . "" . FIN_DE_LINEA;
                $servidorSOAP .= "        if(is_array(\$row)){" . FIN_DE_LINEA;
                $servidorSOAP .= "            \$Resultado[\$i]['infoEncabezado'] = json_encode(\$row);" . FIN_DE_LINEA;
                $servidorSOAP .= "        } else {" . FIN_DE_LINEA;
                $servidorSOAP .= "            \$Resultado[\$i]['infoEncabezado'] = \$row;" . FIN_DE_LINEA;
                $servidorSOAP .= "        }" . FIN_DE_LINEA;
                $servidorSOAP .= "        \$Resultado[0]['cta'] = \$cta;" . FIN_DE_LINEA;
                $servidorSOAP .= "    // }" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "    // Validacion de posibles errores" . FIN_DE_LINEA;
                $servidorSOAP .= "    // if (\$manejoError->numeroDeErrores() > 0) {" . FIN_DE_LINEA;
                $servidorSOAP .= "    //     \$Resultado[0]['error1'] = json_encode(\$manejoError->devolverErrores());" . FIN_DE_LINEA;
                $servidorSOAP .= "    // }" . FIN_DE_LINEA;
                $servidorSOAP .= "" . FIN_DE_LINEA;
                $servidorSOAP .= "    return new soapval('return', 'tns:{$caracteristicas[ZC_ID]}ResptaArray', \$Resultado);" . FIN_DE_LINEA;
                $servidorSOAP .= "}" . FIN_DE_LINEA;
            }
            $servidorSOAP .= "" . FIN_DE_LINEA;
            $servidorSOAP .= "\$_SRV_WS->service(\$HTTP_RAW_POST_DATA);" . FIN_DE_LINEA;
            $servidorSOAP .= "?>" . FIN_DE_LINEA;

            $this->_ruta = $directorioSalida . '/';
            $this->_salida = $this->_ruta . crearArchivo($directorioSalida, $this->_nombre, $extension, $servidorSOAP);
        }

        return $this;
    }

    /**
     * Agrega los archivos javascript manejados por el formulario
     * @param array $javascript
     * @return \formulario
     */
    private function agregarJavascriptFormulario($javascript) {
        $rutaJavascript = (is_array($javascript)) ? $javascript : array($javascript);
        foreach ($rutaJavascript as $ruta) {
            if (!is_file($ruta)) {
                throw new Exception(__FUNCTION__ . 'cwd: ' . getcwd() . ": Ruta de archivo no valida: {$ruta}!?");
            }
            $this->_js .= "<!--Inclusion archivo js -->" . FIN_DE_LINEA . insertarEspacios(8);
            // Cambia la ruta relativa, por una ruta absoluta
            $this->_js .= "<script type='text/javascript' src='" . convertir2UrlLocal($ruta) . "'></script>" . FIN_DE_LINEA . insertarEspacios(8);
        }
        return $this;
    }

    /**
     * Formate la accion a procesar del lado servidor, pasando $ => \$
     * @param string $accion
     * @return string
     */
    private function formatearAccionFormulario($accion) {
        // $accion = str_replace('$', '\\$', $accion);
        return $accion;
    }

    /**
     * Ejecuta todas las acciones para crear los formularios
     */
    private function procesarFormulario() {
        $this->modeloProcesarWS();
        $this->modeloValidacionFormulario();
        $this->modeloWsSOAPClienteFormulario();
        $this->modeloControladorAcciones();
//        $this->crearWsSOAPServidorFormulario();
        return $this;
    }

    /**
     * Verifica y establece valores predeterminados para cada uno de los elementos
     * @param mixed $arg Argumentos pasados por el usuario para cada elemento
     * @return \formulario
     * @throws Exception
     */
    private function verificarValoresArgumentos(&$arg) {
        // Identificadores campos
        if (isset($arg[ZC_ID]) && '' != trim($arg[ZC_ID]) && is_string($arg[ZC_ID])) {
            $arg[ZC_ID] = trim($arg[ZC_ID]);
        } else {
            throw new Exception(__FUNCTION__ . ": El campo {$arg[ZC_ELEMENTO_ETIQUETA]} tiene un identificador no valido [a-Z_].");
        }
        $arg[ZC_ELEMENTO_ETIQUETA] = (isset($arg[ZC_ELEMENTO_ETIQUETA]) && '' != trim($arg[ZC_ELEMENTO_ETIQUETA])) ? trim($arg[ZC_ELEMENTO_ETIQUETA]) : $arg[ZC_ID];

        // Tipo de dato
        $arg[ZC_DATO] = (isset($arg[ZC_DATO])) ? $arg[ZC_DATO] : '';
        $arg[ZC_DATO_MENSAJE_ERROR] = (isset($arg[ZC_DATO_MENSAJE_ERROR])) ? $arg[ZC_DATO_MENSAJE_ERROR] : null;
        // Dato obligatorio
        $arg[ZC_OBLIGATORIO] = (isset($arg[ZC_OBLIGATORIO])) ? $arg[ZC_OBLIGATORIO] : ZC_OBLIGATORIO_NO;
        $arg[ZC_OBLIGATORIO_ERROR] = (isset($arg[ZC_OBLIGATORIO_ERROR])) ? $arg[ZC_OBLIGATORIO_ERROR] : null;
        // La longitud debe ser numerica
        $arg[ZC_LONGITUD_MINIMA] = (isset($arg[ZC_LONGITUD_MINIMA]) && is_int($arg[ZC_LONGITUD_MINIMA])) ? $arg[ZC_LONGITUD_MINIMA] : 0;
        $arg[ZC_LONGITUD_MINIMA_ERROR] = (isset($arg[ZC_LONGITUD_MINIMA_ERROR])) ? $arg[ZC_LONGITUD_MANIMA_ERROR] : null;
        // La longitud debe ser numerica
        $arg[ZC_LONGITUD_MAXIMA] = (isset($arg[ZC_LONGITUD_MAXIMA]) && is_int($arg[ZC_LONGITUD_MAXIMA])) ? $arg[ZC_LONGITUD_MAXIMA] : 0;
        $arg[ZC_LONGITUD_MAXIMA_ERROR] = (isset($arg[ZC_LONGITUD_MAXIMA_ERROR])) ? $arg[ZC_LONGITUD_MAXIMA_ERROR] : null;

        if (isset($arg[ZC_LONGITUD_MINIMA]) && isset($arg[ZC_LONGITUD_MAXIMA]) && $arg[ZC_LONGITUD_MINIMA] > $arg[ZC_LONGITUD_MAXIMA]) {
            throw new Exception(__FUNCTION__ . ": El campo {$arg[ZC_ELEMENTO_ETIQUETA]} tiene incoherencia en sus longitudes.");
        }

        return $this;
    }

}
