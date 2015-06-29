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
        cargarClase(__FILE__, __FUNCTION__, $tipo);
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
    function esLogin(){
        return $this->_modelo->esLogin();
    }
}
