<?php

/**
 * Crea la funcion de agregar (insert)
 */
require RUTA_GENERADOR_CODIGO . '/modelo/agregar.class.php';

/**
 * Crea la funcion de listar/buscar (select)
 */
require RUTA_GENERADOR_CODIGO . '/modelo/buscar.class.php';

/**
 * Crea la funcion de borrar (update a zc_eliminado)
 */
require RUTA_GENERADOR_CODIGO . '/modelo/borrar.class.php';

/**
 * Crea la funcion de modificar/actualizar (update)
 */
require RUTA_GENERADOR_CODIGO . '/modelo/modificar.class.php';

/**
 * Crea la funcion para precargar los datos a editar
 */
require RUTA_GENERADOR_CODIGO . '/modelo/precargar.class.php';

/**
 * Crea la ventana y acciones de logueo
 */
require RUTA_GENERADOR_CODIGO . '/modelo/login.class.php';

/**
 * Crea las funciones para precargar las listas de seleccion
 */
require RUTA_GENERADOR_CODIGO . '/modelo/ajax.class.php';

/**
 * Crea acciones: agregar, buscar, modificar, eliminar, cancelar, defecto
 */
class accion extends Aelemento {

    /**
     * Formulario o tabla relacionado
     * @var string
     */
    protected $_tabla = null;

    /**
     * Tipo de accion a ejecutar
     * @var string
     */
    protected $_accion = null;

    /**
     * Conjunto de elementos tipo input del formulario, corresponde a los campos
     * @var array
     */
    protected $_campos = array();

    /**
     * Almacena la funcion que ejecuta todo el proceso en el modelo
     * @var string
     */
    protected $_funcion = '';

    /**
     * Almacena la el html de los filtros creados por cada formulario
     * @var string
     */
    protected $_filtro = '';

    /**
     * Inicializa los parametros recibidos por el cliente
     * @var array
     */
    protected $_inicializarCliente = array();

    /**
     * Inicializa los parametros recibidos por el servidor
     * @var array
     */
    protected $_inicializarServidor = array();

    /**
     * Inicializa los parametros recibidos por la funcion del servidor
     * @var array
     */
    protected $_parametrosServidor = array();

    /**
     * Inicializa los valores recibidos por el controlador
     * @var array
     */
    protected $_asignacionControlador = array(
        "// Aplica filtro XSS a todo el POST",
        "\$datos = \$this->input->post(null, true);",
        "// Establece la accion a aplicar",
        "\$datos['accion'] = \$this->input->post('accion');",
    );

    /**
     * Tipo de plantilla a utilizar para los llamoados ajax
     * @var array
     */
    protected $_tipoPlantilla = '';

    /**
     * Determina si las varables ya fueron inicializadas, por defecto no se ha inicializado
     * @var bool
     */
    protected $_yaInicio = null;

    /**
     * Nombre de la funcion de validacion
     */ 
    protected $_funcionValidacion;

    /**
     * Crear las acciones, segun el tipo de elemento
     * @param array $caracteristicas Caracteristicas de la accion a crear
     * @param string $tabla Nombre de la tabla a manejar
     * @param string $accion Tipo de boton a crear
     */
    function __construct($caracteristicas, $tabla, $accion, $funcionValidacion = null) {
        $this->_accion = $accion;
        $this->_tabla = strtolower($tabla);
        $this->_campos = $caracteristicas;
        $this->_funcionValidacion = $funcionValidacion;
    }

    /**
     * Selecciona el tipo de accion crear segun el tipo de boton seleccionado
     */
    public function crear() {
        switch ($this->_accion) {
            case ZC_ACCION_AGREGAR:
                $accion = new agregar($this->_campos, $this->_tabla, $this->_accion, $this->_funcionValidacion);
                break;
            case ZC_ACCION_BUSCAR:
                $accion = new buscar($this->_campos, $this->_tabla, $this->_accion);
                break;
            case ZC_ACCION_MODIFICAR:
                $accion = new modificar($this->_campos, $this->_tabla, $this->_accion, $this->_funcionValidacion);
                break;
            case ZC_ACCION_BORRAR:
                $accion = new borrar($this->_campos, $this->_tabla, $this->_accion);
                break;
            case ZC_ACCION_PRECARGAR:
                $accion = new precargar($this->_campos, $this->_tabla, $this->_accion);
                break;
            case ZC_ACCION_AJAX:
                $accion = new ajax($this->_campos, $this->_tabla, $this->_accion);
                break;
            case ZC_ACCION_LOGIN:
                $accion = new login($this->_campos, $this->_tabla, $this->_accion);
                break;
            case ZC_ACCION_RESTABLECER:
            case ZC_ACCION_CANCELAR:
            case ZC_ACCION_BOTON:
            default :
                break;
        }
        $accion->modelo($this->_modelo);
        // Establece la accion creada
        $this->_html = (isset($accion)) ? $accion->crear()->devolverElemento() : $this->comando('$resultado = implode(\'+\', func_get_args());$cta = 1;', 12);
        $this->_funcion = (isset($accion)) ? $accion->funcion()->devolverFuncion() : $this->comando('//$rpta[\'resultado\'] = implode(\'|\', $datos);');
        $this->_filtro = (isset($accion)) ? $accion->filtro()->devolverFiltro() : '';
        $this->_inicializarCliente = (isset($accion)) ? $accion->inicializarAccion()->devolverInicializarCliente() : $this->inicializarAccion()->devolverInicializarCliente();
        $this->_inicializarServidor = (isset($accion)) ? $accion->inicializarAccion()->devolverInicializarServidor() : $this->inicializarAccion()->devolverInicializarServidor();
        $this->_parametrosServidor = (isset($accion)) ? $accion->inicializarAccion()->devolverParametrosServidor() : $this->inicializarAccion()->devolverParametrosServidor();
        $this->_asignacionControlador = (isset($accion)) ? $accion->inicializarAccion()->devolverAsignacionControlador() : $this->inicializarAccion()->devolverAsignacionControlador();
        $this->_tipoPlantilla = (isset($accion)) ? $accion->inicializarAccion()->devolverTipoPlantilla() : $this->inicializarAccion()->devolverTipoPlantilla();
        return $this;
    }

    /**
     * Adecua la sintaxis del comando pasado por parametro
     * @param string $cmd Comando a agregar
     * @param int $espacios Numero de espacios en la sangria izquierda
     * @return string
     */
    protected function comando($cmd, $espacios = 0) {
        return tabular($cmd, $espacios);
    }

    /**
     * Asigna los variables segun tipo, se usa en la creacion de acciones, son los parametros entregados al servidor web
     * @param array $campos Elementos a inicializar
     */
    protected function inicializar() {
        $cmd = $this->comando('$data = array();', 12);
        foreach ($this->_campos as $nro => $campo) {
            switch (true) {
                case $campo[ZC_ELEMENTO] == ZC_ELEMENTO_CHECKBOX:
                    // Devuelve el alemento al array original
                    $cmd .= $this->comando("\$data['{$this->_campos[$nro][ZC_ID]}'] = implode(',', json_decode(\${$campo[ZC_ID]}, true));", 12);
                    break;
                case $campo[ZC_DATO] == ZC_DATO_CONTRASENA:
                    // Encripta las contrasenas
                    $cmd .= $this->comando("\$data['{$this->_campos[$nro][ZC_ID]}'] = sha1(\${$campo[ZC_ID]});", 12);
                    break;
                default:
                    $cmd .= $this->comando("\$data['{$this->_campos[$nro][ZC_ID]}'] = \${$campo[ZC_ID]};", 12);
                    break;
            }
        }
        return $cmd;
    }

    protected function filtro() {
        return $this;
    }

    protected function funcion() {
        return $this;
    }

    protected function inicializarAccion() {
        if (isset($this->_yaInicio)) {
            // Ya esta definido, no los vuelve a cargar
            return $this;
        }

        foreach ($this->_campos as $nro => $campo) {
            // Los datos se envia codificados para evitar errores con caracteres especiales, ademas
            //permite enviar 'cualquier' tipo de dato
            $this->_inicializarCliente[] = ($campo[ZC_ELEMENTO] != ZC_ELEMENTO_CHECKBOX) ? "'{$campo[ZC_ID]}' => \$datos['{$campo[ZC_ID]}']" : "'{$campo[ZC_ID]}' => json_encode(\$datos['{$campo[ZC_ID]}'])";
            // Inicilizar servidor
            $this->_inicializarServidor[] = "'{$campo[ZC_ID]}' => 'xsd:{$campo[ZC_DATO_WS]}'";

            $this->_parametrosServidor[] = "\${$campo[ZC_ID]}";
        }
        $this->_tipoPlantilla = 'jsLlamadosDefaultAjax.js';

        // Desactiva nuevas solicitudes de inicializacion
        $this->_yaInicio = true;
        return $this;
    }

    public function devolverFuncion() {
        return $this->_funcion;
    }

    public function devolverFiltro() {
        return $this->_filtro;
    }

    public function devolverInicializarCliente() {
        return $this->_inicializarCliente;
    }

    public function devolverInicializarServidor() {
        return $this->_inicializarServidor;
    }

    public function devolverParametrosServidor() {
        return $this->_parametrosServidor;
    }

    public function devolverAsignacionControlador() {
        return $this->_asignacionControlador;
    }

    public function devolverTipoPlantilla() {
        return $this->_tipoPlantilla;
    }

    public function modelo($modelo) {
        $this->_modelo = $modelo;
    }

}
