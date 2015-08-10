<?php

// Clase padre de donde herada los metodos
require_once RUTA_GENERADOR_CODIGO . '/modelo/Apaginas.class.php';

/**
 * Crear compartida para la creacion de paginas html
 */
class autenticacion extends Apaginas {

    /**
     * Plantilla a utilizar para crear la vista de creacion y modificacion de la tabla
     * @return string
     */
    public function devolverPlantillaVista() {
        $tpl = 'htmlLogin.tpl';
        return $tpl;
    }

    /**
     * Plantilla utilizada para crear el controlador
     * @return string
     */
    public function devolverPlantillaControlador() {
        $tpl = 'phpControladorLoginSOAP.tpl';
        return $tpl;
    }

    /**
     * Plantilla utilizado para crear el archivo javascript
     * @return string
     */
    public function devolverPlantillaJavascript() {
        $tpl = 'jsLoginjQuery.js';
        return $tpl;
    }

    /**
     * Devuelve el comando de autenticacion de WS
     * @return string
     */
    public function devolverServidorAutenticacion() {
        return '';
    }

    /**
     * Devuelve la plantilla para la distribucion de los botones
     * @param array $elementos Elementos del formlario
     * @return string
     */
    public function devolverPlantillaBotones() {
        $tpl = '{_elementoHTML_}';
        return $tpl;
    }

    /**
     * Devuelve el nombre de la funcion para plantilla formulario
     * @return string
     */
    public function devolverFuncionAgregar() {
        $tpl = 'devolverLogin';
        return $tpl;
    }

    /**
     * Devuelve el nombre del archivo controlador
     * @return string
     */
    public function devolverArchivoControlador() {
        $tpl = ZC_LOGIN_TABLA;
        return $tpl;
    }

    /**
     * Define si de se debe crear la vista de busqueda
     */
    public function esLogin(){
        return true;
    }
}
