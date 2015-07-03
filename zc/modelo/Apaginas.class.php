<?php

/**
 * Crear compartida para la creacion de paginas html
 */
abstract class Apaginas {

    /**
     * Nombre del controlador, lo usa para crear archivos Ajax
     * @var string
     */
    public $_controlador;

    public function __construct($controlador) {
        $this->_controlador = $controlador;
    }
    /**
     * Plantilla a utilizar para crear la vista de creacion y modificacion de la tabla
     * @return string
     */
    public function devolverPlantillaVista() {
        $tpl = 'htmlFluid.tpl';
        return $tpl;
    }

    /**
     * Plantilla utilizada para crear el controlador
     * @return string
     */
    public function devolverPlantillaControlador() {
        $tpl = 'phpControladorSOAP.tpl';
        return $tpl;
    }

    /**
     * Plantilla utilizado para crear el archivo javascript
     * @return string
     */
    public function devolverPlantillaJavascript() {
        $tpl = 'jsInicializacionjQuery.js';
        return $tpl;
    }

    /**
     * Devuelve el comando de autenticacion de WS
     * @return string
     */
    public function devolverServidorAutenticacion() {
        $tpl = tabular('// Valida que el usuario este autenticado para poder usar los ws', 0);
        $tpl .= tabular('// solo si es un llamado remoto', 8);
        $tpl .= tabular('$this->load->library(\'zc\');', 8);
        $tpl .= tabular('if (!$this->zc->esWebService()) {', 8);
        $tpl .= tabular('$datos = $this->zc->validarSesion();', 12);
        $tpl .= tabular('$this->load->model(\'' . ZC_PREFIJO_MODELO . ZC_LOGIN_PAGINA . '\', \'zlogin\');', 12);
        $tpl .= tabular('$rpta = $this->zlogin->loginCliente($datos);', 12);
        $tpl .= tabular('if (isset($rpta[\'error\'])) {', 12);
        $tpl .= tabular('die($rpta[\'error\']);', 16);
        $tpl .= tabular('}', 12);
        $tpl .= tabular('}', 8);
        return $tpl;
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
        $tpl = tabular("<div class='row'>", 20);
        $tpl .= tabular("<div class='col-md-1'></div>", 24);
        $tpl .= tabular("<div class='col-md-5'>", 24);
        $tpl .= tabular("<div class='text-right'>", 28);
        $tpl .= tabular("{_elementoHTML_}", 0);
        $tpl .= tabular("</div>", 28);
        $tpl .= tabular("</div>", 24);
        $tpl .= tabular("<div class='col-md-5'></div>", 24);
        $tpl .= tabular("<div class='col-md-1'></div>", 24);
        $tpl .= tabular("</div>", 20);
        return $tpl;
    }

    /**
     * Devuelve el nombre de la funcion para plantilla formulario
     * @return string
     */
    public function devolverFuncionAgregar() {
        $tpl = 'devolver';
        return $tpl;
    }

    /**
     * Devuelve el nombre del archivo controlador
     * @return string
     */
    public function devolverArchivoControlador() {
        $tpl = $this->_controlador;
        return $tpl;
    }

    /**
     * Define si de se debe crear la vista de busqueda
     */
    public function esLogin(){}
}
