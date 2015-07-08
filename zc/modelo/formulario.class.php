<?php

//Mediador para crear las paginas
require RUTA_GENERADOR_CODIGO . '/modelo/pagina.class.php';

//Mediador para crear elementos HTML caja|radio|lista|checkbox
require RUTA_GENERADOR_CODIGO . '/modelo/elemento.class.php';

//Clase para crear botones
require RUTA_GENERADOR_CODIGO . '/modelo/boton.class.php';

//Clase para la creacion de acciones segun el boton seleccionado
require RUTA_GENERADOR_CODIGO . '/modelo/accion.class.php';

/**
 * Crear formulario html
 */
class formulario {

    /**
     * Identificador del formulario creado
     * @var string
     */
    public $_id = '';
    
    /**
     * Nombre del formulario, corresponde a la descripcion mostrada en el menu de navegacion
     * y otras cabeceras descriptivas
     * @var string
     */
    public $_nombre = '';

    /**
     * Metodo a utilizar para el envio del formulario, por defecto POST
     * GET, POST
     * @var string
     */
    public $_metodo = 'POST';

    /**
     * Tipo de WS a crear rest|soap, por defecto = soap
     * @var string
     */
    private $_tipoWS = ZC_WS_SOAP;

    /**
     * Tipo de formulario a crear
     * @var string
     */
    private $_tipoFormulario = 'tabla';

    /**
     * Almacena las funciones que se crearan en el controlador, una funcion corresponde
     * la mayoria de las veces a un boton
     * @var string
     */
    private $_funcionControlador = '';

    /**
     * Almacena las funciones que se crearan en el servidor WS, una funcion corresponde
     * la mayoria de las veces a un boton
     * @var array
     */
    private $_funcionServidor = array();

    /**
     * Almacena cada uno de los elementos creados dentro del formulario HTML: botones, cajas, etc.
     * Se dejan en el orden de creacion
     * [acciones] = botones (unicamente)
     * [elementos] = cajas, select, radio, textarea, password
     * @var array
     */
    private $_formulario = array();

    /**
     * Archivos javascript creados durante el proceso, estos son utilizados por los archivos html
     * @var string
     */
    private $_js = '';

    /**
     * Almacena la plantilla html de la vista seleccionada, formulario de ingreso/modificacion
     * @var \plantilla
     */
    private $_plantilla = null;

    /**
     * Elementos utilizados por el formulario, pueden ser text, select, radio, checkbox.
     * Almacena las propiedades de cada uno de los elementos
     * @var array
     */
    private $_elementos = array();

    /**
     * Acciones utilizados por el formulario, botones normalmente
     * Almacena las propiedades de cada una de los acciones
     * @var array
     */
    private $_acciones = array();

    /**
     * Parametros pasados al servidor SOAP, se usan durante la definicion de los parametros
     * que acepta la funcion servidor SOAP, se definen los tipos xsd a manejar, depende de la
     * funcion que devuelve el tipo de datoWS, ejemplo: 'campo' => 'xsd:int'
     * @var array
     */
    private $_inicializarServidor = array();

    /**
     * Parametros pasados por el cliente segun los valores del AJAX, define la asignacion de
     * cada una de los campos,  se usa en el llamado del WS en el modelo, ejemplo:
     * 'campo' = $datos['campo']
     * @var array
     */
    private $_inicializarCliente = array();

    /**
     * Lista de parametros aceptados por la funcion del WS Servidor, corresponde a cada uno
     * de los campos de la tabla, ejemplo: $campo1, $campo2, $campon
     * @var array
     */
    private $_parametrosServidor = array();

    /**
     * Lista de valores pasados por el llamado ajax, asignan los valores a pasar al cliente WS,
     * se usa en la creacion de la funcion del controlador, ejemplo: $datos['campo'] = $this->input->post('campo');
     * @var array
     */
    private $_asignacionControlador = array();

    /**
     * Tipo de plantilla a utilizar para los llamados ajax, cada boton de accion utiliza una plantilla
     * especial, sin no se define la plantilla, no se crea la funcion en el archivos javascript
     * @var array
     */
    private $_tipoPlantilla = array();

    /**
     * Inicializacion la variable que contiene el codigo de la validacion del lado servidor.
     * La validacion se hace en el modelo
     * @var string
     */
    private $_validacionModelo = '';

    /**
     * Inicializacion la variable que contiene el codigo de las funciones creadas en el modelo
     * corresponde a cada uno de los llamados al servidor WS
     * @var array
     */
    private $_funcionesModelo = array();

    /**
     * Inicializacion la variable que contiene el codigo html de los filtros disponibles.
     * Se usa en la vista de busqueda.
     * @var array
     */
    private $_filtros = array();

    /**
     * Inicializacion la variable que contiene el codigo del llamado al servidor, cada una
     * de las acciones, se usa en el controlador
     * @var string
     */
    private $_llamadosModelo = '';

    /**
     * Inicializacion la variable que contiene el javascript (con AJAX) que hace uso del controlador
     * @var string
     */
    private $_llamadosAjax = '';

    /**
     * Variable para definir las acciones que se ejecutaran en el servidor WS
     * @var string
     */
    private $_accionesServidorWS = '';

    /**
     * Etiquetas de los campos, se utilizan en el modelo para asignar el nombre de los campos
     * en la respuesta del formulario de busqueda, son los encabezados de la tabla
     * Si se cambia el alias de id, se debe cambiar tambien en la funcion javascript de listado
     * @var string
     */
    private $_aliasCampos = '';

    /**
     * Nombre de las tablas relacionadas con el formulario, se usa para construir los join
     * entre las tablas
     * @var string
     */
    private $_tablasRelacionadas = '';

    /**
     * Nombre del archivo controlador del WS creado en /controllers
     * @var string
     */
    private $_nombreArchivoServidor = '';
    
    /**
     * Caracteristicas de la pagina en creacion
     * @var string
     */
    private $_pagina;

    /**
     * Nombre del archivo modelo creado en /models
     * @var string
     */
    private $_nombreArchivoModelo = '';

    /**
     * Nombre del archivo controlador creado en /controllers
     * tambien se usa para la creacion de los menus de navegacion
     * @var string
     */
    private $_nombreArchivoControlador = '';

    /**
     * Nombre del archivo vista creado en /views
     * @var string
     */
    private $_nombreArchivoVista = '';

    /**
     * Nombre del archivo vista del listado creado en /views
     * @var string
     */
    private $_nombreArchivoListar = '';

    /**
     * Nombre del archivo javascript relacionado al formulario
     * @var string
     */
    private $_nombreArchivoJs = '';

    /**
     * Nombre de la funcion que hace la validacion de datos del lado servidor
     * @var string
     */
    private $_nombreFuncionValidacion = '';

    /**
     * Flag para saber si ya se determino el campo inicial dentro del formulario
     */
    private $_autofocoAplicado = false;

    /**
     * Funcion de inicializacion del formulario, requiere que se han suministradas
     * unos datos basicos relacionados al formulario
     * @param array $caracteristicas Datos basicos, solo es obligatorio el ZC_ID
     * @throws Exception
     */
    function __construct($caracteristicas) {
        if (!is_array($caracteristicas)) {
            mostrarErrorZC(__FILE__, __FUNCTION__, 'Y las caracteristicas del formulario!?');
        } else {
            $this->_id = strtolower($caracteristicas[ZC_ID]);
            $this->_nombre = ($caracteristicas[ZC_FORMULARIO_NOMBRE] != '') ? ucwords($caracteristicas[ZC_FORMULARIO_NOMBRE]) : ucwords($this->_id);
            $this->_tipoWS = (isset($caracteristicas[ZC_WS_TIPO])) ? strtolower($caracteristicas[ZC_TIPO_WS]) : strtolower($this->_tipoWS);
            $this->_metodo = (isset($caracteristicas[ZC_FORMULARIO_METODO])) ? strtoupper($caracteristicas[ZC_FORMULARIO_METODO]) : strtoupper($this->_metodo);
            $this->_tipoFormulario = (isset($caracteristicas[ZC_FORMULARIO_TIPO])) ? strtolower($caracteristicas[ZC_FORMULARIO_TIPO]) : strtolower($this->_tipoFormulario);
            $this->inicio();
        }
    }

    /**
     * Define nombres de archivos utilizados por las plantillas a lo largo del proceso
     * Define nombres de los archivos creados: modelo, vista, controlador
     * Los nombres se manejan en minuscula y seperados con underscore segun
     * recomendacion de CodeIgniter
     * @return \formulario
     */
    private function inicio() {
        // Nombre vista
        $this->_nombreArchivoVista = ZC_PREFIJO_VISTA . $this->_id;
        // Nombre modelo
        $this->_nombreArchivoModelo = ZC_PREFIJO_MODELO . $this->_id;
        // Nombre modelo
        $this->_nombreArchivoControlador = ZC_PREFIJO_CONTROLADOR . $this->_id;
        // Nombre del listado
        $this->_nombreArchivoListar = ZC_PREFIJO_LISTA . $this->_id;
        // Nombre del archivo que guarda las funcionalidades del servidor
        $this->_nombreArchivoServidor = ZC_PREFIJO_WS . $this->_id;
        // Nombre javascript
        $this->_nombreArchivoJs = $this->_id;
        // Nombre de la funcion de validacion
        $this->_nombreFuncionValidacion = 'validarDatos';
        // Caracteristicas de la pagina en creacion
        $this->_pagina = new pagina($this->_tipoFormulario, $this->_nombreArchivoControlador, $this->_nombreArchivoModelo);
        return $this;
    }

    /**
     * Muestra en pantalla el formulario creado
     */
    public function imprimir() {
        echo $this->unirElementosFormulario($this->_formulario);
    }

    /**
     * Retorno en un string los elementos del formulario concatenado
     * @return string
     */
    public function devolver() {
        return $this->_plantilla->devolverPlantilla();
    }

    /**
     * Crear el archivo con el modelo (model) para el formulario
     * @param string $directorioSalida ruta donde se creara el archivo
     * @param string $extension Extension del archivo creado
     * @param array $opciones Opciones a aplicar a la plantilla creada
     * @return \formulario
     */
    private function crearModeloFormulario($directorioSalida = '../www/application/models', $extension = 'php', $opciones = array()) {
        // Plantilla para el modelo (model)
        $plantilla = new plantilla($opciones);
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpModeloSOAP.tpl');
        $plantilla->asignarEtiqueta('nombreModelo', $this->_nombreArchivoModelo);
        $plantilla->asignarEtiqueta('aliasCampos', $this->_aliasCampos);
        $plantilla->asignarEtiqueta('tablasRelacionadas', $this->_tablasRelacionadas);
        $plantilla->asignarEtiqueta('llamadosModelo', $this->_llamadosModelo);
        $plantilla->asignarEtiqueta('funcionesModelo', implode(FIN_DE_LINEA, $this->_funcionesModelo));
        $plantilla->asignarEtiqueta('validacionModelo', $this->_validacionModelo);
        $plantilla->crearPlantilla($directorioSalida, $extension, $this->_nombreArchivoModelo);
        return $this;
    }

    /**
     * Crear un arhivo html para la creacion/modificacion de un registro
     * @param string $directorioSalida Ruta donde se creara el archivo
     * @param string $extension Extension del archivo creado
     * @param array $opciones Opciones a aplicar a la plantilla del formulario creado, minimizar, abrir
     * @return \formulario
     */
    private function crearVistaFormulario($directorioSalida = '../www/application/views', $extension = 'html', $opciones = array()) {
        // Plantilla para la vista (view), se puede devolver, por eso se deja en una variable $this
        $this->_plantilla = new plantilla($opciones);
        $this->_plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/html/' . $this->_pagina->_modelo->devolverPlantillaVista());
        if (!$this->_pagina->_modelo->esLogin()){
            $this->crearListarFormulario($directorioSalida, $extension, $opciones);
        }
        $this->_plantilla->asignarEtiqueta('idFormulario', $this->_id);
        $this->_plantilla->asignarEtiqueta('nombreControlador', $this->_nombreArchivoControlador);
        $this->_plantilla->asignarEtiqueta('nombreFormulario', $this->_nombre);
        $this->_plantilla->asignarEtiqueta('metodoFormulario', $this->_metodo);
        $this->_plantilla->asignarEtiqueta('contenidoFormulario', $this->unirElementosFormulario($this->_formulario));
        $this->_plantilla->asignarEtiqueta('archivoJavascript', $this->_js);
        $this->_plantilla->crearPlantilla($directorioSalida, $extension, $this->_nombreArchivoVista);
        return $this;
    }

    /**
     * Crear un arhivo html para la busqueda de registros
     * @param string $directorioSalida Ruta donde se crear el archivo
     * @param string $extension Extension del archivo creado
     * @param array $opciones Opciones a aplicar a la plantilla del formulario creado
     * @return \formulario
     */
    public function crearListarFormulario($directorioSalida = '../www/application/views', $extension = 'html', $opciones = array()) {
        // Plantilla para la vista (view)
        $plantilla = new plantilla($opciones);
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/html/htmlListarFluid.tpl');
        $plantilla->asignarEtiqueta('idFormulario', $this->_id);
        $plantilla->asignarEtiqueta('nombreControlador', $this->_nombreArchivoControlador);
        $plantilla->asignarEtiqueta('nombreFormulario', $this->_nombre);
        $plantilla->asignarEtiqueta('metodoFormulario', $this->_metodo);
        $plantilla->asignarEtiqueta('contenidoFormulario', implode(FIN_DE_LINEA, $this->_filtros));
        $plantilla->asignarEtiqueta('archivoJavascript', $this->_js);
        $plantilla->crearPlantilla($directorioSalida, $extension, $this->_nombreArchivoListar);
        return $this;
    }

    /**
     * Crea el controlador del formulario del tipo CodeIgniter
     * @param string $directorioSalida Ruta donde se creara el archivos
     * @param string $extension Extension con la cual se creara el archivo
     * @param array $opciones Opciones a aplicar a la plantilla creada
     * @return \formulario
     */
    private function crearControladorFormulario($directorioSalida = '../www/application/controllers', $extension = 'php', $opciones = array()) {
        // Plantilla para el controlador (controller)
        $plantilla = new plantilla($opciones);
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/' . $this->_pagina->_modelo->devolverPlantillaControlador());
        $plantilla->asignarEtiqueta('nombreControlador', $this->_nombreArchivoControlador);
        $plantilla->asignarEtiqueta('idFormulario', $this->_id);
        $plantilla->asignarEtiqueta('nombreVista', $this->_nombreArchivoVista . '.html');
        $plantilla->asignarEtiqueta('nombreVistaListar', $this->_nombreArchivoListar . '.html');
        $plantilla->asignarEtiqueta('nombreModelo', $this->_nombreArchivoModelo);
        $plantilla->asignarEtiqueta('accionServidor', $this->_funcionControlador);
        $plantilla->asignarEtiqueta('paginaNavegacion', ZC_NAVEGACION_PAGINA);
        $plantilla->crearPlantilla($directorioSalida, $extension, $this->_nombreArchivoControlador);
        return $this;
    }

    /**
     * Crea el el archivo javascrip que jace el llamado al modelo para procesar los datos
     * @param string $directorioSalida Ruta donde se creara el archivos
     * @param string $extension Extension con la cual se creara el archivo
     * @param array $opciones Opciones a aplicar a la plantilla creada
     * @return \formulario
     */
    private function crearJavascriptFormulario($directorioSalida = '../www/publico/js', $extension = 'js', $opciones = array()) {
        // Plantilla para el manejo de javascript
        $plantilla = new plantilla($opciones);
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/' . $this->_pagina->_modelo->devolverPlantillaJavascript());
        $plantilla->asignarEtiqueta('idFormulario', $this->_id);
        $plantilla->asignarEtiqueta('accionAgregar', ZC_ACCION_AGREGAR);
        $plantilla->asignarEtiqueta('accionModificar', ZC_ACCION_MODIFICAR);
        $plantilla->asignarEtiqueta('accionBorrar', ZC_ACCION_BORRAR);
        $plantilla->asignarEtiqueta('accionPrecargar', ZC_ACCION_PRECARGAR);
        $plantilla->asignarEtiqueta('llamadosAjax', $this->_llamadosAjax);
        $plantilla->crearPlantilla($directorioSalida, $extension, $this->_nombreArchivoJs);
        // Agregar archivo creado al javascript al formulario
        $this->javascriptFormulario($plantilla->devolver());
        return $this;
    }

    /**
     * Crea el archivo controlador que manejan las funciones de WS del lado servidor
     * @param string $directorioSalida Ruta de salida donde se creara el archivo
     * @param string $extension Extension que tendra el archivo de salida
     * @param array $opciones Opciones de configuracion del arhcivo creado
     * @return \formulario
     */
    private function crearControladorServidorFormulario($directorioSalida = '../www/application/controllers', $extension = 'php', $opciones = array()) {
        // Plantilla para el modelo (model)
        $plantilla = new plantilla($opciones);
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpControladorServidorSOAP.tpl');
        $plantilla->asignarEtiqueta('comandoEspecial', $this->_pagina->_modelo->devolverServidorAutenticacion());
        $plantilla->asignarEtiqueta('nombreControlador', $this->_nombreArchivoServidor);
        $plantilla->asignarEtiqueta('accionesServidorWS', $this->_accionesServidorWS);
        $plantilla->crearPlantilla($directorioSalida, $extension, $this->_nombreArchivoServidor);
        return $this;
    }

    /**
     * Alias de la funcion para agregar elementos desde otras clases, se puede omitir el tipo
     * de elemento, la funcion se encarga de escoger el adecuado, ademas acepta multiles elementos separados por coma
     */
    public function agregarAtributo() {
        $elementos = func_get_args();
        $this->agregarAtributoFormulario($elementos);
    }

    /**
     * Crea los elementos dentro del formulario segun las caracteristicas entregadas por el xml
     * @param array $elementos Caracteristicas de los elementos a entregar
     * @return \formulario
     */
    private function agregarAtributoFormulario($elementos) {
        foreach ($elementos as $caracteristicas) {
            if (!is_array($caracteristicas)) {
                mostrarErrorZC(__FILE__, __FUNCTION__, ': Y las caracteristicas del elemento!?');
            }
            // Se valida en minuscula para evitar ambiguedades: Boton, boton, BOTON, etc
            // Se debe dejar, en este punto no ha pasado por la funcion elementos::verificar
            $caracteristicas[ZC_ELEMENTO] = (strtolower($caracteristicas[ZC_ELEMENTO]));
            if ($this->_pagina->_modelo->esLogin()) {
                // A los campos de formularios NO se les valida la longitud
                $caracteristicas[ZC_LONGITUD_MAXIMA] = -1;
                $caracteristicas[ZC_LONGITUD_MINIMA] = -1;
            }
            $caracteristicas[ZC_AUTOFOCO] = $this->aplicarAutofoco();
            switch ($caracteristicas[ZC_ELEMENTO]) {
                case ZC_ELEMENTO_CAJA:
                case ZC_ELEMENTO_RADIO:
                case ZC_ELEMENTO_CHECKBOX:
                case ZC_ELEMENTO_LISTA:
                    $this->agregarElementoFormulario($caracteristicas);
                    break;
                case ZC_ACCION_RESTABLECER:
                case ZC_ACCION_CANCELAR:
                case ZC_ACCION_BOTON:
                case ZC_ACCION_AGREGAR:
                case ZC_ACCION_BUSCAR:
                case ZC_ACCION_MODIFICAR:
                case ZC_ACCION_BORRAR:
                case ZC_ACCION_NUEVO:
                case ZC_ACCION_PRECARGAR:
                case ZC_ACCION_AJAX:
                case ZC_ACCION_LOGIN:
                    $this->agregarAccionFormulario($caracteristicas);
                    break;
                default:
                    mostrarErrorZC(__FILE__, __FUNCTION__, ": Tipo de atributo no definido: {$caracteristicas[ZC_ELEMENTO]}!");
            }
        }
        return $this;
    }

    /**
     * Construye los archivos necesarios para el proyecto.
     * @return \formulario
     */
    public function finFormulario() {
        $this->procesarFormulario();
        $this->crearControladorFormulario();
        $this->crearModeloFormulario();
        $this->crearControladorServidorFormulario();
        // Las acciones del cliente se deben procesar despues de crear el controlador,
        // ya que este ultimo hace referencia a la URL
        $this->crearAccionesClienteFormulario();
        $this->crearJavascriptFormulario();
        // La vista se debe crear despues de los archivos javascript (esto para cargar bien las rutas del js)
        // La vista de busqueda y listado de campos de la tabla
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
            $tpl = '{_elementoHTML_}';
            if ($elemento == 'acciones') {
                // Las acciones son muchas en una sola fila, se agrupan al final del proceso
                $tpl = $this->_pagina->_modelo->devolverPlantillaBotones();
            }
            if (is_array($elementos[$elemento])) {
                $elementosFormulario .= str_replace('{_elementoHTML_}', $this->unirElementosFormulario($elementos[$elemento]), $tpl);
            } else {
                $elementosFormulario .= $elementos[$elemento];
            }
        }
        return $elementosFormulario;
    }

    /**
     * Agrega las cajas, radios, checkboxs, select dentro del formulario, segun caracteristicas del XML
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \formulario
     */
    private function agregarElementoFormulario($caracteristicas) {
        $html = new elemento($caracteristicas);
        $html->propiedad('controlador', $this->_nombreArchivoControlador);
        // Instancia del elemento creado
        $elemento = $html->crear();
        $this->_formulario['elementos'][$elemento->devolverId()] = $elemento->{$this->_pagina->_modelo->devolverFuncionAgregar()}();
        $this->_elementos[] = $elemento->devolverProp();
        if (method_exists($elemento, 'devolverAjax')){
            // Si el metodo devolverAjax existe es una lista y verifica si se creo un archivo de consulta Ajax
            $this->javascriptFormulario($elemento->devolverAjax());
        }
        return $this;
    }

    /**
     * Agrega las botones dentro del formulario, segun caracteristicas
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \formulario
     */
    private function agregarAccionFormulario($caracteristicas) {
        $html = new boton($caracteristicas);
        $html->crear();
        // Devuelve el elemento, no usa devolver() ya que los botones no usan la plantilla
        $this->_formulario['acciones'][$html->devolverId()] = $html->devolverElemento();
        $this->_acciones[] = $html->devolverProp();
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
     * Define el fin del documento html
     * @return \formulario
     */
    private function fin() {
        return $this;
    }

    /**
     * Crea las variables necesarias para la ejecucion del proceso, ademas crea la funcion de
     * validacion de datos del formulario para el lado servidor
     * @return \formulario
     * @throws Exception
     */
    private function modeloValidacionFormulario() {
        $validacion = '';
        // Determina la tabla a la que pertenece el campo
        $tabla = $this->_pagina->_modelo->devolverArchivoControlador();
        // Agrega el id, necesario para busqueda y modificacion de registro
        $this->_aliasCampos .= aliasCampos('id', 'id', $tabla);
        foreach ($this->_elementos as $nro => $caracteristicas) {
            // Validacion obligatoriedad
            $validacion .= validarArgumentoObligatorio($caracteristicas[ZC_ID], $caracteristicas[ZC_ETIQUETA], $caracteristicas[ZC_OBLIGATORIO], $caracteristicas[ZC_OBLIGATORIO_ERROR]);
            // Validacion tipo de dato Entero
            $validacion .= validarArgumentoTipoDato($caracteristicas[ZC_ID], $caracteristicas[ZC_ETIQUETA], $caracteristicas[ZC_ELEMENTO], $caracteristicas[ZC_DATO], $caracteristicas[ZC_DATO_ERROR]);
            // Validacion longitud minima del campo
            $validacion .= validarArgumentoLongitudMinima($caracteristicas[ZC_ID], $caracteristicas[ZC_ETIQUETA], $caracteristicas[ZC_DATO], $caracteristicas[ZC_LONGITUD_MINIMA], $caracteristicas[ZC_LONGITUD_MINIMA_ERROR]);
            // Validacion longitud maxima del campo
            $validacion .= validarArgumentoLongitudMaxima($caracteristicas[ZC_ID], $caracteristicas[ZC_ETIQUETA], $caracteristicas[ZC_DATO], $caracteristicas[ZC_LONGITUD_MAXIMA], $caracteristicas[ZC_LONGITUD_MAXIMA_ERROR]);
            // Nombre de tablas utilizados, se usa para los join
            $joinTablas = joinTablas($caracteristicas[ZC_ELEMENTO_OPCIONES]);
            if (isset($joinTablas)) {
                $this->_tablasRelacionadas .= tablasRelacionadas($caracteristicas[ZC_ID], $joinTablas['tabla'], $joinTablas['join']);
                // Nombre de los campos usados
                $this->_aliasCampos .= aliasCampos($joinTablas['campo'], $caracteristicas[ZC_ETIQUETA], $joinTablas['tabla']);
            } elseif (($caracteristicas[ZC_DATO] !== ZC_DATO_CONTRASENA) || ($caracteristicas[ZC_DATO] === ZC_DATO_CONTRASENA && $this->_pagina->_modelo->esLogin())) {
                // Las contrasenas se omiten en el listado del formulario de busqueda, excepto para el caso de login
                // La contrasena se almacena en los datos de session para autencicarse con los WS
                $this->_aliasCampos .= aliasCampos($caracteristicas[ZC_ID], $caracteristicas[ZC_ETIQUETA], $tabla);
            }
        }

        $plantilla = new plantilla();
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpValidacionServidor.tpl');
        $plantilla->asignarEtiqueta('nombreValidacion', $this->_nombreFuncionValidacion);
        $plantilla->asignarEtiqueta('elementosFormulario', $validacion);
        $plantilla->asignarEtiqueta('accionesSinValidacion', ZC_ACCION_SIN_VALIDACION);
        $this->_validacionModelo = $plantilla->devolverPlantilla();
        return $this;
    }

    /**
     * Acciones en el servidor
     * @param string $directorioSalida
     * @return \formulario
     */
    private function modeloControladorAcciones() {
        foreach ($this->_acciones as $nro => $caracteristicas) {
            if (in_array($caracteristicas[ZC_ELEMENTO], array(ZC_ACCION_RESTABLECER, ZC_ACCION_CANCELAR))) {
                // Los botones tipo restablecer, cancelar no crean acciones
                continue;
            }
            // Determina la accion a ejecutar en el controlador
            $comando = tabular($this->_asignacionControlador[$caracteristicas[ZC_ID]], 8);
            $comandoEspecial = '';
            switch ($caracteristicas[ZC_ELEMENTO]) {
                case ZC_ACCION_BUSCAR:
                    $comando .= tabular('// Aplica filtros seleccionados', 8);
                    $comando .= tabular("\$rpta = \$this->modelo->validarFiltros(\$datos['filtros'], \$datos['accion']);", 8);
                    // Construir la paginacion
                    $comandoEspecial .= tabular('// Establece los valores para la paginacion', 12);
                    $comandoEspecial .= tabular('if (isset($rpta[\'cta\']) && $rpta[\'cta\'] > 0){', 12);
                    $comandoEspecial .= tabular('$rpta[\'paginacion\'] = $this->paginar($rpta[\'cta\']);', 16);
                    $comandoEspecial .= tabular('}', 12);
                    break;
                case ZC_ACCION_LOGIN:
                    // Registrar el inicio de sesion del usuario
                    $comandoEspecial = tabular('// Inicia sesion el sistema', 12);
                    $comandoEspecial .= tabular('$rpta = $this->loguear($rpta);', 12);
                    // Continua con la accion por defecto ya que es la misma
                default:
                    $comando .= tabular('// Valida los datos pasados por POST', 8);
                    $comando .= tabular("\$rpta = \$this->modelo->{$this->_nombreFuncionValidacion}(\$datos);", 8);
                    break;
            }
            // Plantilla para la creacion de acciones en el controlador
            $plantilla = new plantilla();
            $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpControladorAccionSOAP.tpl');
            $plantilla->asignarEtiqueta('nombreAccion', $caracteristicas[ZC_ID]);
            $plantilla->asignarEtiqueta('asignacionCliente', $comando);
            $plantilla->asignarEtiqueta('comandoEspecial', $comandoEspecial);
            // Concatena cada accion del cliente
            $this->_funcionControlador .= tabular($plantilla->devolverPlantilla(), 4);
        }
        return $this;
    }

    /**
     * Acciones del lado cliente Ajax
     * @param string $directorioSalida
     * @return \formulario
     */
    private function crearAccionesClienteFormulario() {
        foreach ($this->_acciones as $nro => $caracteristicas) {
            if (in_array($caracteristicas[ZC_ELEMENTO], array(ZC_ACCION_RESTABLECER, ZC_ACCION_CANCELAR)) || $this->_tipoPlantilla[$caracteristicas[ZC_ID]] == '') {
                // Los botones tipo restablecer no crean accciones de envio, ya tiene la
                // accion preferida
                // No siempre tienen accion predefinida, para el caso de precarga no debe crear una accion
                continue;
            }
            // Plantilla para los envio con AJAX en javascript (jQuery)
            $plantilla = new plantilla();
            $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/' . $this->_tipoPlantilla[$caracteristicas[ZC_ID]]);
            $plantilla->asignarEtiqueta('nombreAccion', $caracteristicas[ZC_ID]);
            $plantilla->asignarEtiqueta('nombreControlador', $this->_nombreArchivoControlador);
            // Se define en la creacion de la plantilla del controlador
            $plantilla->asignarEtiqueta('idFormulario', $this->_id);
            $plantilla->asignarEtiqueta('accionCliente', '//Accion Cliente va aqui');
            $plantilla->asignarEtiqueta('mensajeError', ZC_MENSAJE_ERROR_BUSCAR);
            // Concatena cada accion del cliente
            $this->_llamadosAjax .= $plantilla->devolverPlantilla();
        }
        return $this;
    }

    /**
     * Crear cliente WS para consumir el servicio web, se hace por cada accion
     * @return \formulario
     */
    private function modeloWsSOAPClienteFormulario() {
        foreach ($this->_acciones as $nro => $caracteristicas) {
            if (in_array($caracteristicas[ZC_ELEMENTO], array(ZC_ACCION_RESTABLECER, ZC_ACCION_CANCELAR))) {
                // Los botones tipo restablecer, cancelar no crean acciones
                continue;
            }
            // Plantilla para la creacion de acciones en el cliente
            $plantilla = new plantilla();
            $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpCrearAccion.tpl');
            $plantilla->asignarEtiqueta('nombreAccion', $caracteristicas[ZC_ID]);
            $plantilla->asignarEtiqueta('servidorAccion', $this->_nombreArchivoServidor);
            $plantilla->asignarEtiqueta('asignacionCliente', $this->_inicializarCliente[$caracteristicas[ZC_ID]]);
            // Concatena las acciones que se pueden llamar desde el cliente
            $this->_llamadosModelo .= tabular($plantilla->devolverPlantilla(), 4);
        }
        return $this;
    }

    /**
     * Crear servidor WS de lado servidor
     * @param string $directorioSalida
     * @return \formulario
     */
    private function controladorWsSOAPServidorFormulario() {
        foreach ($this->_acciones as $nro => $caracteristicas) {
            if (in_array($caracteristicas[ZC_ELEMENTO], array(ZC_ACCION_RESTABLECER, ZC_ACCION_CANCELAR))) {
                // Los botones tipo restablecer, cancelar no crean acciones
                continue;
            }
            // Plantilla para la creacion de acciones en el cliente
            $plantilla = new plantilla();
            $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpModeloServidorSOAP.tpl');
            $plantilla->asignarEtiqueta('nombreControlador', $this->_nombreArchivoControlador);
            $plantilla->asignarEtiqueta('nombreAccion', $caracteristicas[ZC_ID]);
            $plantilla->asignarEtiqueta('nombreFuncion', $caracteristicas[ZC_ID] . 'Servidor');
            $plantilla->asignarEtiqueta('asignacionCliente', $this->_inicializarServidor[$caracteristicas[ZC_ID]]);
            $plantilla->asignarEtiqueta('asignacionFuncion', $this->_parametrosServidor[$caracteristicas[ZC_ID]]);
            $plantilla->asignarEtiqueta('accionServidor', $this->_funcionServidor[$caracteristicas[ZC_ID]]);
            // Concatena las acciones que se pueden llamar desde el cliente
            $this->_accionesServidorWS .= tabular($plantilla->devolverPlantilla(), 4);
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
            if (isset($ruta) && !is_file($ruta)) {
                mostrarErrorZC(__FILE__, __FUNCTION__, " Ruta de archivo no valida: {$ruta}!");
            } else if (isset($ruta)) {
                $this->_js .= tabular('<!--Inclusion archivo js  -->', 8);
                // Cambia la ruta relativa, por una ruta absoluta
                $this->_js .= tabular("<script type='text/javascript' src='" . convertir2UrlLocal($ruta) . "'></script>", 8);
            }
        }
        return $this;
    }

    /**
     * Ejecuta todas las acciones para crear los formularios
     */
    private function procesarFormulario() {
        $this->modeloValidacionFormulario();
        $this->propiedadesAccion();
        $this->modeloWsSOAPClienteFormulario();
        $this->modeloControladorAcciones();
        $this->controladorWsSOAPServidorFormulario();
        return $this;
    }

    private function propiedadesAccion() {
        foreach ($this->_acciones as $nro => $caracteristicas) {
            if (in_array($caracteristicas[ZC_ELEMENTO], array(ZC_ACCION_RESTABLECER, ZC_ACCION_CANCELAR))) {
                // Los botones tipo restablecer, cancelar no crean acciones
                continue;
            }
            // Determina la accion a ejecutar en el cvontrolador
            $accion = new accion($this->_elementos, $this->_id, $caracteristicas[ZC_ELEMENTO], $this->_nombreFuncionValidacion);
            // Nombre de los archivos usados
            $accion->modelo($this->_nombreArchivoModelo);
            // Funciones creada en el servidor
            $this->_funcionServidor[$caracteristicas[ZC_ID]] = $accion->crear()->devolverElemento();
            // Concatena las funciones que se ejecutaran en el modelo
            $this->_funcionesModelo[$caracteristicas[ZC_ID]] = $accion->devolverFuncion();
            // Concatena las los filtros de los formularios de busqueda
            $this->_filtros[$caracteristicas[ZC_ID]] = $accion->devolverFiltro();
            // Asignacion variables en el modelo para el llamado WS
            $this->_inicializarCliente[$caracteristicas[ZC_ID]] = implode(',' . FIN_DE_LINEA . insertarEspacios(12), $accion->devolverInicializarCliente());
            // Inicializacion de variables en el servidor
            $this->_inicializarServidor[$caracteristicas[ZC_ID]] = implode(',' . FIN_DE_LINEA . insertarEspacios(12), $accion->devolverInicializarServidor());
            // Parametros recibidos por el servidor
            $this->_parametrosServidor[$caracteristicas[ZC_ID]] = implode(', ', $accion->devolverParametrosServidor());
            // Asignacion controlador
            $this->_asignacionControlador[$caracteristicas[ZC_ID]] = implode(FIN_DE_LINEA . insertarEspacios(8), $accion->devolverAsignacionControlador());
            // Tipo de plantilla javascript a utilizar
            $this->_tipoPlantilla[$caracteristicas[ZC_ID]] = $accion->devolverTipoPlantilla();
        }
        return $this;
    }

    /**
     * Devuelve la informacion necesaria para crear los menus de navegacion
     */
    public function infoNavegacion() {
        return array('formulario' => $this->_nombre, 'controlador' => $this->_nombreArchivoControlador);
    }

    /**
     * Determina si se debe agregar la opcion de autofoc al elemento
     * Solo mse aplica al primer elemento dentro del formulario
     */
    private function aplicarAutofoco() {
        if (!$this->_autofocoAplicado) {
            $this->_autofocoAplicado = true;
            return true;
        }
        return false;
    }
}
