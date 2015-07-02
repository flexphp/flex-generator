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
     * Crea los elementos dentro del formulario segun las caracteristicas entregadas por el xml
     * @param array $elementos Caracteristicas de los elementos a entregar
     * @return \formulario
     */
    public function agregarElementoFormulario($elementos) {
        foreach ($elementos as $caracteristicas) {
            if ($this->_esLogin) {
                // A los campos de formularios NO se les valida la longitud
                $caracteristicas[ZC_LONGITUD_MAXIMA] = -1;
                $caracteristicas[ZC_LONGITUD_MINIMA] = -1;
            }
        }
        return $this;
    }

    /**
     * Devuelve la plantilla para la distribucion de los botones
     * @param array $elementos Elementos del formlario
     * @return string
     */
    public function devolverPlantillaBotones() {
        $tpl = tabular("<div class='row'>", 32);
        $tpl .= tabular("{_elementoHTML_}", 36);
        $tpl .= tabular("</div>", 32);
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
