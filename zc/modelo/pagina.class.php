<?php

/**
 * Mediador para la creacion de paginas html
 */
class pagina {

    /**
     * Instancia de la pagina creada
     * @var \modelo
     */
    public $_modelo;

    /**
     * Crea una instancia del tipo de pagina a crear
     * @return string
     */
    public function __construct($tipo, $controlador = '') {
        if (!class_exists($tipo)) {
            // Si la clase a un no se ha definido carga la definicion
            $rutaClase = RUTA_GENERADOR_CODIGO . '/modelo/' . $tipo . '.class.php';
            if (file_exists($rutaClase)) {
                require $rutaClase;
            } else {
                mostrarErrorZC(__FILE__, __FUNCTION__, ': Tipo de pagina no soportada: ' . $tipo);
            }
        }
        // Asigna el modelo a crear
        $this->_modelo = new $tipo($controlador);
    }

    function devolverPlantillaVista() {
        return $this->_modelo->devolverPlantillaVista();
    }
    function devolverPlantillaControlador() {
        return $this->_modelo->devolverPlantillaControlador();
    }
    function devolverPlantillaJavascript() {
        return $this->_modelo->devolverPlantillaJavascript();
    }
    function devolverServidorAutenticacion() {
        return $this->_modelo->devolverServidorAutenticacion();
    }
    function agregarElementoFormulario($elementos) {
        return $this->_modelo->agregarElementoFormulario();
    }
    function devolverPlantillaBotones() {
        return $this->_modelo->devolverPlantillaBotones();
    }
    function devolverFuncionAgregar() {
        return $this->_modelo->devolverFuncionAgregar();
    }
    function devolverArchivoControlador() {
        return $this->_modelo->devolverArchivoControlador();
    }
    function devolverClienteAutenticacion() {
        return $this->_modelo->devolverClienteAutenticacion();
    }
    function esLogin(){
        return $this->_modelo->esLogin();
    }
}
